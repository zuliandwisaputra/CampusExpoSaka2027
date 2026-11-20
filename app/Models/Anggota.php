<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Anggota extends Model
{
    use FlushesPublicCache;

    protected $table = 'anggota';

    protected $fillable = [
        'nama', 'foto', 'kampus_id', 'divisi_id', 'jurusan', 'angkatan', 'jabatan',
        'instagram', 'linkedin', 'no_hp', 'ditampilkan',
    ];

    /** Nomor HP tidak pernah ikut terserialisasi ke luar panel admin. */
    protected $hidden = ['no_hp'];

    protected function casts(): array
    {
        return ['ditampilkan' => 'boolean'];
    }

    public function kampus(): BelongsTo
    {
        return $this->belongsTo(Kampus::class);
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    /** Hanya anggota berstatus tampil yang muncul di halaman publik. */
    public function scopeTampil(Builder $query): Builder
    {
        return $query->where('ditampilkan', true);
    }

    /** Ketua divisi tampil lebih dulu, lalu urut nama. */
    public function scopeUrutJabatan(Builder $query): Builder
    {
        return $query->orderByRaw("CASE WHEN jabatan LIKE 'Ketua%' THEN 0 ELSE 1 END")->orderBy('nama');
    }

    public function getIsKetuaAttribute(): bool
    {
        return Str::startsWith(Str::lower((string) $this->jabatan), 'ketua');
    }

    public function getFotoUrlAttribute(): string
    {
        return Media::url($this->foto, 'anggota');
    }

    public function getInstagramUrlAttribute(): ?string
    {
        return $this->instagram ? 'https://www.instagram.com/'.$this->instagram.'/' : null;
    }

    public function getInisialAttribute(): string
    {
        return collect(explode(' ', $this->nama))->filter()->take(2)
            ->map(fn ($w) => Str::upper(Str::substr($w, 0, 1)))->implode('');
    }

    public function getDataBelumLengkapAttribute(): array
    {
        return array_keys(array_filter([
            'foto' => Media::isDummy($this->foto),
            'jurusan' => blank($this->jurusan),
            'angkatan' => blank($this->angkatan),
        ]));
    }
}
