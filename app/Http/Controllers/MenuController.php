<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $query = Menu::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->string('kategori'));
        }

        if ($request->filled('cari')) {
            $query->where('nama_menu', 'like', '%'.$request->string('cari').'%');
        }

        $menus = $query->orderBy('kategori')->orderBy('nama_menu')->paginate(10)->withQueryString();

        return view('menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('menus.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'in:Appetizer,Main Course,Dessert,Drink'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'tersedia' => ['sometimes', 'boolean'],
        ]);

        $validated['tersedia'] = $request->boolean('tersedia', true);

        Menu::create($validated);

        return redirect()->route('menus.index')->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'in:Appetizer,Main Course,Dessert,Drink'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'tersedia' => ['sometimes', 'boolean'],
        ]);

        $validated['tersedia'] = $request->boolean('tersedia', true);

        $menu->update($validated);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->detailPesanans()->exists()) {
            return back()->with('error', 'Menu tidak dapat dihapus karena sudah pernah dipesan.');
        }

        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
