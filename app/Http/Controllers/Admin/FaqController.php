<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faq = Faq::orderBy('urutan')->orderBy('id')->get();

        return view('admin.faq.index', compact('faq'));
    }

    public function create(): View
    {
        return view('admin.faq.form', ['item' => new Faq(['urutan' => (int) Faq::max('urutan') + 1])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Faq::create($this->validated($request));

        return redirect()->route('admin.faq.index')->with('status', 'Pertanyaan ditambahkan.');
    }

    public function edit(Faq $item): View
    {
        return view('admin.faq.form', compact('item'));
    }

    public function update(Request $request, Faq $item): RedirectResponse
    {
        $item->update($this->validated($request));

        return redirect()->route('admin.faq.index')->with('status', 'Pertanyaan disimpan.');
    }

    public function destroy(Faq $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('admin.faq.index')->with('status', 'Pertanyaan dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'pertanyaan' => ['required', 'string', 'max:255'],
            'jawaban' => ['required', 'string', 'max:3000'],
            'urutan' => ['required', 'integer', 'min:0', 'max:999'],
        ]);
    }
}
