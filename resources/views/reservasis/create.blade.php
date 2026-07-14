@extends('layouts.app')

@section('title', 'Reservasi Baru')
@section('page-title', 'Buat Reservasi Baru')

@section('content')
<div style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h3>📅 Reservasi Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reservasis.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="nama_pemesan">Nama Pemesan</label>
                    <input type="text" id="nama_pemesan" name="nama_pemesan" class="form-control"
                           placeholder="Nama lengkap" value="{{ old('nama_pemesan') }}" required>
                    @error('nama_pemesan') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="no_telepon">No. Telepon</label>
                    <input type="text" id="no_telepon" name="no_telepon" class="form-control"
                           placeholder="08xxxxxxxxxx" value="{{ old('no_telepon') }}" required>
                    @error('no_telepon') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="meja_id">Meja</label>
                    <select id="meja_id" name="meja_id" class="form-control" required>
                        <option value="">— Pilih Meja —</option>
                        @foreach($mejas as $meja)
                            <option value="{{ $meja->id }}" {{ old('meja_id') == $meja->id ? 'selected' : '' }}>
                                Meja {{ $meja->nomor_meja }} ({{ $meja->kapasitas }} orang)
                                {{ $meja->status !== 'available' ? '— '.$meja->status : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('meja_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="jumlah_tamu">Jumlah Tamu</label>
                    <input type="number" id="jumlah_tamu" name="jumlah_tamu" class="form-control"
                           min="1" max="50" value="{{ old('jumlah_tamu', 2) }}" required>
                    @error('jumlah_tamu') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="tanggal_reservasi">Tanggal Reservasi</label>
                    <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" class="form-control"
                           value="{{ old('tanggal_reservasi', now()->format('Y-m-d')) }}" required>
                    @error('tanggal_reservasi') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="waktu_reservasi">Waktu Reservasi</label>
                    <input type="time" id="waktu_reservasi" name="waktu_reservasi" class="form-control"
                           value="{{ old('waktu_reservasi', '19:00') }}" required>
                    @error('waktu_reservasi') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="catatan">Catatan</label>
                    <textarea id="catatan" name="catatan" class="form-control" rows="3"
                              placeholder="Catatan khusus (opsional)">{{ old('catatan') }}</textarea>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Simpan Reservasi</button>
                    <a href="{{ route('reservasis.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
