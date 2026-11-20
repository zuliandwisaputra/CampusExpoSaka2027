<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    use FlushesPublicCache, HasSlug;

    protected $table = 'album';

    protected $fillable = ['judul', 'slug', 'tanggal'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    protected function slugSource(): string
    {
        return 'judul';
    }

    public function foto(): HasMany
    {
        return $this->hasMany(Foto::class)->orderBy('id');
    }
}
