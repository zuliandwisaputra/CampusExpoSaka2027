<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

/**
 * Mengubah teks biasa dari panel admin menjadi paragraf HTML yang aman.
 * Semua karakter di-escape dulu, jadi admin tidak bisa menyisipkan HTML/script.
 * Baris kosong = paragraf baru, baris diawali "- " = butir daftar.
 */
class Teks
{
    public static function paragraf(?string $teks): HtmlString
    {
        $blok = preg_split('/\R{2,}/', trim((string) $teks)) ?: [];
        $html = '';

        foreach ($blok as $b) {
            $baris = preg_split('/\R/', trim($b));

            if (collect($baris)->every(fn ($l) => str_starts_with(ltrim($l), '- '))) {
                $html .= '<ul>'.collect($baris)
                    ->map(fn ($l) => '<li>'.e(substr(ltrim($l), 2)).'</li>')
                    ->implode('').'</ul>';

                continue;
            }

            $html .= '<p>'.collect($baris)->map(fn ($l) => e($l))->implode('<br>').'</p>';
        }

        return new HtmlString($html);
    }
}
