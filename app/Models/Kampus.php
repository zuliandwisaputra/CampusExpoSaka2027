<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kampus extends Model
{
    use FlushesPublicCache, HasSlug;

    public const JENIS = [
        'ptn' => 'PTN',
        'pts' => 'PTS',
        'politeknik' => 'Politeknik',
        'kedinasan' => 'Kedinasan',
    ];

    protected $table = 'kampus';

    protected $fillable = [
        'nama', 'slug', 'singkatan', 'jenis', 'kota', 'provinsi', 'logo', 'deskripsi',
        'jalur_masuk', 'kisaran_biaya', 'beasiswa', 'website', 'instagram',
    ];

    public function jurusan(): HasMany
    {
        return $this->hasMany(Jurusan::class)->orderBy('jenjang')->orderBy('nama');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(Anggota::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? strtoupper((string) $this->jenis);
    }

    public function getLogoUrlAttribute(): string
    {
        return Media::url($this->logo, 'logo');
    }

    public function getLokasiAttribute(): string
    {
        return collect([$this->kota, $this->provinsi])->filter()->implode(', ');
    }

    public function getInstagramUrlAttribute(): ?string
    {
        return $this->instagram ? 'https://www.instagram.com/'.$this->instagram.'/' : null;
    }
}
