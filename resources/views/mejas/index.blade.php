@extends('layouts.app')

@section('title', 'Manajemen Meja')
@section('page-title', 'Manajemen Meja')

@section('content')
<div class="d-flex justify-between align-center mb-3">
    <h2>🪑 Daftar Meja</h2>
    <a href="{{ route('mejas.create') }}" class="btn btn-primary">+ Tambah Meja</a>
</div>

<div class="meja-grid">
    @forelse($mejas as $meja)
    <div class="meja-card {{ $meja->status }}">
        <div class="meja-number">{{ $meja->nomor_meja }}</div>
        <div class="meja-capacity">👤 Kapasitas {{ $meja->kapasitas }} orang</div>

        @if($meja->status === 'available')
            <span class="badge badge-success">Tersedia</span>
        @elseif($meja->status === 'occupied')
            <span class="badge badge-danger">Terisi</span>
        @else
            <span class="badge badge-warning">Reserved</span>
        @endif

        <div class="meja-actions">
            <a href="{{ route('mejas.edit', $meja) }}" class="btn btn-sm btn-secondary">✏️ Edit</a>
            <form action="{{ route('mejas.destroy', $meja) }}" method="POST"
                  onsubmit="return confirm('Yakin hapus meja {{ $meja->nomor_meja }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state" style="grid-column: 1 / -1;">
        <div class="empty-icon">🪑</div>
        <p>Belum ada data meja. Silakan tambah meja baru.</p>
    </div>
    @endforelse
</div>

<div style="margin-top: 24px;">
    {{ $mejas->links() }}
</div>
@endsection
