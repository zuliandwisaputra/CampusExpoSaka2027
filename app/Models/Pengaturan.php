<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use App\Support\Media;
use App\Support\PublicCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/** Pengaturan acara dalam bentuk kunci–nilai (nama acara, tanggal, lokasi, foto beranda, kontak). */
class Pengaturan extends Model
{
    use FlushesPublicCache;

    public const DEFAULTS = [
        'nama_acara' => 'Campus Expo SMAN 1 Kandanghaur 2027',
        'tagline' => 'Explore the Landscape of Your Future',
        'deskripsi' => 'Kumpul alumni SMAN 1 Kandanghaur dari berbagai kampus dengan sesi tanya jawab seputar kampus, tanpa stand. Tanya langsung soal jurusan, jalur masuk, dan kehidupan kuliah.',
        'tanggal_acara' => '2027-01-18',
        'jam_mulai' => '',
        'jam_selesai' => '',
        'lokasi' => 'SMA Negeri 1 Kandanghaur',
        'alamat' => 'Jl. Pantura Karanganyar, Kec. Kandanghaur, Kab. Indramayu, Jawa Barat 45254',
        'peta_lokasi' => 'SMA Negeri 1 Kandanghaur, Indramayu',
        'penyelenggara' => 'Ikatan Keluarga Alumni SMA Negeri 1 Kandanghaur',
        'foto_beranda' => '',
        'instagram' => 'campusexposaka_',
        'tiktok' => 'campusexposaka_',
        'email' => 'campusexposman1kandanghaur@gmail.com',
    ];

    protected $table = 'pengaturan';

    protected $fillable = ['kunci', 'nilai'];

    /** @var array<string, string|null>|null */
    private static ?array $memo = null;

    protected static function booted(): void
    {
        static::saved(fn () => self::$memo = null);
    }

    public static function semua(): array
    {
        if (self::$memo === null) {
            $tersimpan = PublicCache::remember('pengaturan', fn () => static::query()->pluck('nilai', 'kunci')->all(), 3600);
            self::$memo = array_merge(self::DEFAULTS, array_filter($tersimpan, fn ($v) => $v !== null));
        }

        return self::$memo;
    }

    public static function ambil(string $kunci, ?string $default = null): ?string
    {
        $nilai = self::semua()[$kunci] ?? null;

        return blank($nilai) ? $default : $nilai;
    }

    public static function simpan(string $kunci, ?string $nilai): void
    {
        static::updateOrCreate(['kunci' => $kunci], ['nilai' => $nilai]);
        self::$memo = null;
    }

    public static function lupakan(): void
    {
        self::$memo = null;
    }

    /** Waktu mulai acara untuk hitung mundur. Jam belum diputuskan → dianggap 07.00 WIB. */
    public static function waktuAcara(): Carbon
    {
        $tanggal = self::ambil('tanggal_acara', self::DEFAULTS['tanggal_acara']);
        $jam = self::ambil('jam_mulai', '07:00');

        return Carbon::parse("{$tanggal} {$jam}", config('app.timezone'));
    }

    public static function acaraSudahLewat(): bool
    {
        $tanggal = self::ambil('tanggal_acara', self::DEFAULTS['tanggal_acara']);

        return now()->greaterThan(Carbon::parse($tanggal, config('app.timezone'))->endOfDay());
    }

    /** Kata kunci atau koordinat lokasi untuk Google Maps (diatur di Pengaturan acara). */
    public static function petaKueri(): string
    {
        return self::ambil('peta_lokasi', self::DEFAULTS['peta_lokasi']);
    }

    /** Peta yang ditanam di beranda. Tidak butuh API key. */
    public static function petaEmbedUrl(): string
    {
        return 'https://maps.google.com/maps?q='.rawurlencode(self::petaKueri()).'&z=16&output=embed';
    }

    /**
     * Akun media sosial panitia yang sudah diisi, untuk footer dan bagian "Ikuti kami".
     *
     * @return list<array{nama: string, ikon: string, akun: string, url: string, ajakan: string}>
     */
    public static function sosmed(): array
    {
        $daftar = [];

        if ($ig = self::ambil('instagram')) {
            $daftar[] = ['nama' => 'Instagram', 'ikon' => 'instagram', 'akun' => '@'.$ig,
                'url' => 'https://www.instagram.com/'.rawurlencode($ig).'/', 'ajakan' => 'Info dan pengumuman terbaru'];
        }

        if ($tiktok = self::ambil('tiktok')) {
            $daftar[] = ['nama' => 'TikTok', 'ikon' => 'tiktok', 'akun' => '@'.$tiktok,
                'url' => 'https://www.tiktok.com/@'.rawurlencode($tiktok), 'ajakan' => 'Video seputar Campus Expo'];
        }

        return $daftar;
    }

    public static function petaUrl(): string
    {
        return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode(self::petaKueri());
    }

    public static function petaArahUrl(): string
    {
        return 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode(self::petaKueri());
    }

    public static function fotoBerandaUrl(): string
    {
        return Media::url(self::ambil('foto_beranda'), 'sekolah');
    }
}
