<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MejaController extends Controller
{
    public function index(): View
    {
        $mejas = Meja::withCount('orders')
            ->with(['reservasis' => function ($query) {
                $query->whereIn('status', ['menunggu', 'dikonfirmasi'])
                      ->latest()
                      ->limit(1);
            }])
            ->orderBy('nomor_meja')
            ->paginate(12);

        return view('mejas.index', compact('mejas'));
    }

    public function create(): View
    {
        return view('mejas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_meja' => ['required', 'string', 'max:10', 'unique:mejas,nomor_meja'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['required', 'in:available,occupied,reserved'],
        ]);

        Meja::create($validated);

        return redirect()->route('mejas.index')->with('success', 'Meja baru berhasil ditambahkan.');
    }

    public function edit(Meja $meja): View
    {
        return view('mejas.edit', compact('meja'));
    }

    public function update(Request $request, Meja $meja): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_meja' => ['required', 'string', 'max:10', 'unique:mejas,nomor_meja,'.$meja->id],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['required', 'in:available,occupied,reserved'],
        ]);

        $meja->update($validated);

        return redirect()->route('mejas.index')->with('success', 'Data meja berhasil diperbarui.');
    }

    public function destroy(Meja $meja): RedirectResponse
    {
        if ($meja->orders()->whereIn('status', ['pending', 'diproses'])->exists()) {
            return back()->with('error', 'Meja tidak dapat dihapus karena memiliki order aktif.');
        }

        $meja->delete();

        return redirect()->route('mejas.index')->with('success', 'Meja berhasil dihapus.');
    }
}
