@extends('layouts.app')

@section('title', 'Reservasi')
@section('page-title', 'Manajemen Reservasi')

@section('content')
<div class="d-flex justify-between align-center mb-3">
    <h2>📅 Daftar Reservasi</h2>
    <a href="{{ route('reservasis.create') }}" class="btn btn-primary">+ Reservasi Baru</a>
</div>

{{-- Filter --}}
<div class="filter-bar">
    <form action="{{ route('reservasis.index') }}" method="GET" class="d-flex gap-1 align-center" style="flex-wrap: wrap;">
        <select name="status" class="form-control" style="max-width: 180px;">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" style="max-width: 180px;">
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        @if(request()->hasAny(['status', 'tanggal']))
            <a href="{{ route('reservasis.index') }}" class="btn btn-sm btn-outline-gold">Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Pemesan</th>
                        <th>No. Telepon</th>
                        <th>Meja</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Tamu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $r)
                    <tr>
                        <td class="fw-bold">{{ $r->nama_pemesan }}</td>
                        <td>{{ $r->no_telepon }}</td>
                        <td>{{ $r->meja->nomor_meja ?? '-' }}</td>
                        <td>{{ $r->tanggal_reservasi->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ \Carbon\Carbon::parse($r->waktu_reservasi)->format('H:i') }}</td>
                        <td>{{ $r->jumlah_tamu }} orang</td>
                        <td>
                            @if($r->status === 'dikonfirmasi')
                                <span class="badge badge-success">Dikonfirmasi</span>
                            @elseif($r->status === 'menunggu')
                                <span class="badge badge-warning">Menunggu</span>
                            @elseif($r->status === 'dibatalkan')
                                <span class="badge badge-danger">Dibatalkan</span>
                            @else
                                <span class="badge badge-info">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('reservasis.edit', $r) }}" class="btn btn-sm btn-secondary">✏️</a>
                                <form action="{{ route('reservasis.destroy', $r) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus reservasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                            Belum ada data reservasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top: 16px;">
    {{ $reservasis->links() }}
</div>
@endsection
