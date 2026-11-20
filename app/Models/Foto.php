<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Foto extends Model
{
    use FlushesPublicCache;

    protected $table = 'foto';

    protected $fillable = ['album_id', 'file', 'thumb', 'keterangan'];

    protected static function booted(): void
    {
        static::deleted(function (Foto $foto) {
            Media::delete($foto->file);
            Media::delete($foto->thumb);
        });
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function getUrlAttribute(): string
    {
        return Media::url($this->file, 'galeri');
    }

    public function getThumbUrlAttribute(): string
    {
        return Media::url($this->thumb ?: $this->file, 'galeri');
    }
}
