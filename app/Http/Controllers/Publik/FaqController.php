<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Support\PublicCache;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faq = PublicCache::remember('faq', fn () => Faq::orderBy('urutan')->orderBy('id')->get());

        return view('publik.faq', compact('faq'));
    }
}
