<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    use FlushesPublicCache;

    protected $table = 'jadwal';

    protected $fillable = ['judul', 'waktu_mulai', 'waktu_selesai', 'lokasi', 'kampus_id', 'keterangan', 'acara_utama'];

    protected function casts(): array
    {
        return ['acara_utama' => 'boolean'];
    }

    public function kampus(): BelongsTo
    {
        return $this->belongsTo(Kampus::class);
    }

    public function getJamAttribute(): string
    {
        $mulai = substr((string) $this->waktu_mulai, 0, 5);
        $selesai = $this->waktu_selesai ? substr((string) $this->waktu_selesai, 0, 5) : null;

        return $selesai ? "{$mulai}–{$selesai}" : $mulai;
    }
}
