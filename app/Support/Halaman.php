<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/** Pagination untuk koleksi yang sudah diambil dari cache. */
class Halaman
{
    public static function dari(Collection $items, Request $request, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage ??= (int) config('campus.per_page', 12);
        $page = max(1, (int) $request->integer('page', 1));

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );
    }
}
