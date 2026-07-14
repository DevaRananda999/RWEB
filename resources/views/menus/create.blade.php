@extends('layouts.app')

@section('title', 'Tambah Menu')
@section('page-title', 'Tambah Menu Baru')

@section('content')
<div style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h3>📖 Tambah Menu Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('menus.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="nama_menu">Nama Menu</label>
                    <input type="text" id="nama_menu" name="nama_menu" class="form-control"
                           placeholder="Contoh: Wagyu Steak A5" value="{{ old('nama_menu') }}" required>
                    @error('nama_menu') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Appetizer" {{ old('kategori') == 'Appetizer' ? 'selected' : '' }}>Appetizer</option>
                        <option value="Main Course" {{ old('kategori') == 'Main Course' ? 'selected' : '' }}>Main Course</option>
                        <option value="Dessert" {{ old('kategori') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="Drink" {{ old('kategori') == 'Drink' ? 'selected' : '' }}>Drink</option>
                    </select>
                    @error('kategori') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="harga">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga" class="form-control"
                           min="0" step="1000" placeholder="0" value="{{ old('harga') }}" required>
                    @error('harga') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" class="form-control"
                           min="0" value="{{ old('stok', 0) }}" required>
                    @error('stok') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="hidden" name="tersedia" value="0">
                        <input type="checkbox" name="tersedia" value="1" {{ old('tersedia', true) ? 'checked' : '' }}>
                        <span style="font-size: 0.85rem; color: var(--color-text-secondary);">Menu tersedia untuk dipesan</span>
                    </label>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
