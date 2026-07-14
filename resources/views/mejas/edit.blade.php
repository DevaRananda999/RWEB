@extends('layouts.app')

@section('title', 'Edit Meja')
@section('page-title', 'Edit Meja ' . $meja->nomor_meja)

@section('content')
<div style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h3>✏️ Edit Meja {{ $meja->nomor_meja }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('mejas.update', $meja) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="nomor_meja">Nomor Meja</label>
                    <input type="text" id="nomor_meja" name="nomor_meja" class="form-control"
                           value="{{ old('nomor_meja', $meja->nomor_meja) }}" required>
                    @error('nomor_meja') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="kapasitas">Kapasitas (Orang)</label>
                    <input type="number" id="kapasitas" name="kapasitas" class="form-control"
                           min="1" max="50" value="{{ old('kapasitas', $meja->kapasitas) }}" required>
                    @error('kapasitas') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="available" {{ old('status', $meja->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="occupied" {{ old('status', $meja->status) == 'occupied' ? 'selected' : '' }}>Terisi</option>
                        <option value="reserved" {{ old('status', $meja->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    </select>
                    @error('status') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">💾 Update</button>
                    <a href="{{ route('mejas.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
