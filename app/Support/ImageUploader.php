<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

/**
 * Mengecilkan dan mengompres foto saat diunggah (PRD: foto dikompres saat upload
 * supaya halaman tetap ringan di jaringan seluler). Hasilnya disimpan sebagai WebP.
 * Hanya memakai ekstensi GD bawaan PHP, jadi aman untuk shared hosting.
 */
class ImageUploader
{
    /** Ukuran maksimal tiap jenis gambar (lebar, tinggi) mengikuti tabel foto dummy di PRD. */
    public const SIZES = [
        'sekolah' => [1920, 1080],
        'anggota' => [600, 600],
        'logo' => [400, 400],
        'galeri' => [1200, 900],
        'galeri_thumb' => [600, 450],
    ];

    /** Foto di atas ini (megapiksel) ditolak supaya memori server tidak habis saat diolah. */
    public const MAKS_MEGAPIKSEL = 50;

    public static function batasMb(): int
    {
        return (int) round(config('campus.upload_max_kb', 7168) / 1024);
    }

    /**
     * @param  string  $field  nama kolom formulir, untuk menampilkan pesan galat di dekatnya
     */
    public static function store(UploadedFile $file, string $folder, string $size, int $quality = 80, string $field = 'foto'): string
    {
        [$maxWidth, $maxHeight] = self::SIZES[$size];

        try {
            $source = self::load($file);
        } catch (RuntimeException $e) {
            throw ValidationException::withMessages([$field => $e->getMessage()]);
        }

        $path = trim($folder, '/').'/'.Str::random(32).'.webp';
        Storage::disk('public')->put($path, self::encode($source, $maxWidth, $maxHeight, $quality));
        imagedestroy($source);

        return $path;
    }

    /**
     * Menyimpan dua versi sekaligus: foto besar dan thumbnail untuk grid galeri.
     *
     * @return array{0: string, 1: string}
     */
    public static function storeWithThumb(UploadedFile $file, string $folder): array
    {
        $source = self::load($file);
        $name = trim($folder, '/').'/'.Str::random(32);

        [$w, $h] = self::SIZES['galeri'];
        Storage::disk('public')->put("{$name}.webp", self::encode($source, $w, $h, 80));

        [$tw, $th] = self::SIZES['galeri_thumb'];
        Storage::disk('public')->put("{$name}-thumb.webp", self::encode($source, $tw, $th, 72));

        imagedestroy($source);

        return ["{$name}.webp", "{$name}-thumb.webp"];
    }

    private static function load(UploadedFile $file): \GdImage
    {
        $info = @getimagesize($file->getRealPath());
        if ($info === false) {
            throw new RuntimeException('File bukan gambar yang valid.');
        }

        if (self::MAKS_MEGAPIKSEL * 1_000_000 < $info[0] * $info[1]) {
            throw new RuntimeException('Resolusi foto terlalu besar. Perkecil dulu, maksimal '.self::MAKS_MEGAPIKSEL.' megapiksel.');
        }

        // Foto 7 MB dari kamera HP bisa butuh ratusan MB memori saat dibuka GD.
        self::naikkanBatasMemori('512M');

        $data = file_get_contents($file->getRealPath());
        $image = $data === false ? false : @imagecreatefromstring($data);

        if ($image === false) {
            throw new RuntimeException('File bukan gambar yang valid.');
        }

        return self::applyExifOrientation($image, $file);
    }

    private static function encode(\GdImage $source, int $maxWidth, int $maxHeight, int $quality): string
    {
        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(1, $maxWidth / $width, $maxHeight / $height);
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagewebp($canvas, null, $quality);
        $binary = (string) ob_get_clean();
        imagedestroy($canvas);

        return $binary;
    }

    private static function naikkanBatasMemori(string $target): void
    {
        $sekarang = ini_get('memory_limit');
        $keByte = fn (string $v) => $v === '-1' ? PHP_INT_MAX : (int) $v * match (strtoupper(substr($v, -1))) {
            'G' => 1024 ** 3, 'M' => 1024 ** 2, 'K' => 1024, default => 1,
        };

        if ($sekarang !== false && $keByte($sekarang) < $keByte($target)) {
            @ini_set('memory_limit', $target);
        }
    }

    /** Foto dari HP sering tersimpan miring; putar sesuai data EXIF bila tersedia. */
    private static function applyExifOrientation(\GdImage $image, UploadedFile $file): \GdImage
    {
        if (! function_exists('exif_read_data') || ! in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)) {
            return $image;
        }

        $exif = @exif_read_data($file->getRealPath());
        $angle = match ($exif['Orientation'] ?? 1) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle === 0) {
            return $image;
        }

        $rotated = imagerotate($image, $angle, 0);

        return $rotated === false ? $image : $rotated;
    }
}
