@extends('layouts.app')

@section('title', 'Manajemen Menu')
@section('page-title', 'Manajemen Menu')

@section('content')
<div class="d-flex justify-between align-center mb-3">
    <h2>📖 Daftar Menu</h2>
    <a href="{{ route('menus.create') }}" class="btn btn-primary">+ Tambah Menu</a>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <form action="{{ route('menus.index') }}" method="GET" class="d-flex gap-1 align-center" style="flex-wrap: wrap;">
        <input type="text" name="cari" class="form-control" placeholder="🔍 Cari menu..."
               value="{{ request('cari') }}" style="max-width: 220px;">
        <select name="kategori" class="form-control" style="max-width: 180px;">
            <option value="">Semua Kategori</option>
            <option value="Appetizer" {{ request('kategori') == 'Appetizer' ? 'selected' : '' }}>Appetizer</option>
            <option value="Main Course" {{ request('kategori') == 'Main Course' ? 'selected' : '' }}>Main Course</option>
            <option value="Dessert" {{ request('kategori') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
            <option value="Drink" {{ request('kategori') == 'Drink' ? 'selected' : '' }}>Drink</option>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        @if(request()->hasAny(['cari', 'kategori']))
            <a href="{{ route('menus.index') }}" class="btn btn-sm btn-outline-gold">Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr>
                        <td class="fw-bold">{{ $menu->nama_menu }}</td>
                        <td><span class="badge badge-info">{{ $menu->kategori }}</span></td>
                        <td class="text-gold fw-bold">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($menu->stok <= 5)
                                <span class="text-danger fw-bold">{{ $menu->stok }}</span>
                            @else
                                {{ $menu->stok }}
                            @endif
                        </td>
                        <td>
                            @if($menu->tersedia)
                                <span class="badge badge-success">Tersedia</span>
                            @else
                                <span class="badge badge-danger">Habis</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('menus.edit', $menu) }}" class="btn btn-sm btn-secondary">✏️</a>
                                <form action="{{ route('menus.destroy', $menu) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus menu {{ $menu->nama_menu }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                            Belum ada data menu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top: 16px;">
    {{ $menus->links() }}
</div>
@endsection
