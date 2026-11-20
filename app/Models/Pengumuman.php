<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Pengumuman extends Model
{
    use FlushesPublicCache, HasSlug;

    protected $table = 'pengumuman';

    protected $fillable = ['judul', 'slug', 'isi', 'status', 'disematkan', 'terbit_pada'];

    protected function casts(): array
    {
        return [
            'disematkan' => 'boolean',
            'terbit_pada' => 'datetime',
        ];
    }

    protected function slugSource(): string
    {
        return 'judul';
    }

    /** Hanya pengumuman berstatus terbit yang waktunya sudah tiba. */
    public function scopeTerbit(Builder $query): Builder
    {
        return $query->where('status', 'terbit')
            ->where(fn ($q) => $q->whereNull('terbit_pada')->orWhere('terbit_pada', '<=', now()));
    }

    /** Pengumuman yang disematkan tampil paling atas. */
    public function scopeUrut(Builder $query): Builder
    {
        return $query->orderByDesc('disematkan')->orderByDesc('terbit_pada')->orderByDesc('id');
    }

    public function getRingkasanAttribute(): string
    {
        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($this->isi))), 140);
    }

    public function getTanggalAttribute(): ?Carbon
    {
        return $this->terbit_pada ?? $this->created_at;
    }
}
