@extends('layouts.app')

@section('title', 'Edit Reservasi')
@section('page-title', 'Edit Reservasi')

@section('content')
<div style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h3>✏️ Edit Reservasi — {{ $reservasi->nama_pemesan }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reservasis.update', $reservasi) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="nama_pemesan">Nama Pemesan</label>
                    <input type="text" id="nama_pemesan" name="nama_pemesan" class="form-control"
                           value="{{ old('nama_pemesan', $reservasi->nama_pemesan) }}" required>
                    @error('nama_pemesan') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="no_telepon">No. Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" class="form-control"
                           value="{{ old('no_telepon', $reservasi->no_telepon) }}" required>
                    @error('no_telepon') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="meja_id">Meja</label>
                    <select id="meja_id" name="meja_id" class="form-control" required>
                        @foreach($mejas as $meja)
                            <option value="{{ $meja->id }}" {{ old('meja_id', $reservasi->meja_id) == $meja->id ? 'selected' : '' }}>
                                Meja {{ $meja->nomor_meja }} ({{ $meja->kapasitas }} orang)
                            </option>
                        @endforeach
                    </select>
                    @error('meja_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="jumlah_tamu">Jumlah Tamu</label>
                    <input type="number" id="jumlah_tamu" name="jumlah_tamu" class="form-control"
                           min="1" max="50" value="{{ old('jumlah_tamu', $reservasi->jumlah_tamu) }}" required>
                    @error('jumlah_tamu') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="tanggal_reservasi">Tanggal</label>
                    <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" class="form-control"
                           value="{{ old('tanggal_reservasi', $reservasi->tanggal_reservasi->format('Y-m-d')) }}" required>
                    @error('tanggal_reservasi') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="waktu_reservasi">Waktu</label>
                    <input type="time" id="waktu_reservasi" name="waktu_reservasi" class="form-control"
                           value="{{ old('waktu_reservasi', \Carbon\Carbon::parse($reservasi->waktu_reservasi)->format('H:i')) }}" required>
                    @error('waktu_reservasi') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="menunggu" {{ old('status', $reservasi->status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="dikonfirmasi" {{ old('status', $reservasi->status) == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="selesai" {{ old('status', $reservasi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ old('status', $reservasi->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="catatan">Catatan</label>
                    <textarea id="catatan" name="catatan" class="form-control" rows="3">{{ old('catatan', $reservasi->catatan) }}</textarea>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                    <a href="{{ route('reservasis.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
