<?php

namespace App\Models;

use App\Models\Concerns\FlushesPublicCache;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use FlushesPublicCache;

    protected $table = 'faq';

    protected $fillable = ['pertanyaan', 'jawaban', 'urutan'];

    protected function casts(): array
    {
        return ['urutan' => 'integer'];
    }
}
