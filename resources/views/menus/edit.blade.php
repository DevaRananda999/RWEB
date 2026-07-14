@extends('layouts.app')

@section('title', 'Edit Menu')
@section('page-title', 'Edit Menu — ' . $menu->nama_menu)

@section('content')
<div style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h3>✏️ Edit Menu</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('menus.update', $menu) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="nama_menu">Nama Menu</label>
                    <input type="text" id="nama_menu" name="nama_menu" class="form-control"
                           value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                    @error('nama_menu') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" class="form-control" required>
                        <option value="Appetizer" {{ old('kategori', $menu->kategori) == 'Appetizer' ? 'selected' : '' }}>Appetizer</option>
                        <option value="Main Course" {{ old('kategori', $menu->kategori) == 'Main Course' ? 'selected' : '' }}>Main Course</option>
                        <option value="Dessert" {{ old('kategori', $menu->kategori) == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="Drink" {{ old('kategori', $menu->kategori) == 'Drink' ? 'selected' : '' }}>Drink</option>
                    </select>
                    @error('kategori') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="harga">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga" class="form-control"
                           min="0" step="1000" value="{{ old('harga', $menu->harga) }}" required>
                    @error('harga') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" class="form-control"
                           min="0" value="{{ old('stok', $menu->stok) }}" required>
                    @error('stok') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="hidden" name="tersedia" value="0">
                        <input type="checkbox" name="tersedia" value="1" {{ old('tersedia', $menu->tersedia) ? 'checked' : '' }}>
                        <span style="font-size: 0.85rem; color: var(--color-text-secondary);">Menu tersedia untuk dipesan</span>
                    </label>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
