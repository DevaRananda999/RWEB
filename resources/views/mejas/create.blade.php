@extends('layouts.app')

@section('title', 'Tambah Meja')
@section('page-title', 'Tambah Meja Baru')

@section('content')
<div style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h3>🪑 Tambah Meja Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('mejas.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="nomor_meja">Nomor Meja</label>
                    <input type="text" id="nomor_meja" name="nomor_meja" class="form-control"
                           placeholder="Contoh: A1, VIP1" value="{{ old('nomor_meja') }}" required>
                    @error('nomor_meja') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="kapasitas">Kapasitas (Orang)</label>
                    <input type="number" id="kapasitas" name="kapasitas" class="form-control"
                           min="1" max="50" value="{{ old('kapasitas', 2) }}" required>
                    @error('kapasitas') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Terisi</option>
                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    </select>
                    @error('status') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="{{ route('mejas.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
