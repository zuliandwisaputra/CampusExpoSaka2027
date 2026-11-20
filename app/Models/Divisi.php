<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use FlushesPublicCache, HasSlug;

    protected $table = 'divisi';

    protected $fillable = ['nama', 'slug', 'deskripsi', 'urutan'];

    protected function casts(): array
    {
        return ['urutan' => 'integer'];
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(Anggota::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** Koordinator divisi = anggota yang jabatannya "Ketua Divisi". */
    public function koordinator(): ?Anggota
    {
        return $this->anggota->first(fn (Anggota $a) => $a->is_ketua && $a->ditampilkan);
    }
}
