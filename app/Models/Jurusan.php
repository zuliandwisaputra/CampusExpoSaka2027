<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jurusan extends Model
{
    use FlushesPublicCache;

    protected $table = 'jurusan';

    protected $fillable = ['kampus_id', 'nama', 'jenjang'];

    public function kampus(): BelongsTo
    {
        return $this->belongsTo(Kampus::class);
    }
}
