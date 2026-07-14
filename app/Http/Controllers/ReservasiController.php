<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use App\Models\Reservasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservasiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Reservasi::with('meja');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_reservasi', $request->string('tanggal'));
        }

        $reservasis = $query->latest()->paginate(10)->withQueryString();

        return view('reservasis.index', compact('reservasis'));
    }

    public function create(): View
    {
        $mejas = Meja::orderBy('nomor_meja')->get();

        return view('reservasis.create', compact('mejas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'meja_id' => ['required', 'exists:mejas,id'],
            'nama_pemesan' => ['required', 'string', 'max:100'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'jumlah_tamu' => ['required', 'integer', 'min:1', 'max:50'],
            'tanggal_reservasi' => ['required', 'date', 'after_or_equal:today'],
            'waktu_reservasi' => ['required', 'date_format:H:i'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['status'] = 'menunggu';

        Reservasi::create($validated);

        $meja = Meja::find($validated['meja_id']);
        if ($meja && $meja->status === 'available') {
            $meja->update(['status' => 'reserved']);
        }

        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil dibuat.');
    }

    public function edit(Reservasi $reservasi): View
    {
        $mejas = Meja::orderBy('nomor_meja')->get();

        return view('reservasis.edit', compact('reservasi', 'mejas'));
    }

    public function update(Request $request, Reservasi $reservasi): RedirectResponse
    {
        $validated = $request->validate([
            'meja_id' => ['required', 'exists:mejas,id'],
            'nama_pemesan' => ['required', 'string', 'max:100'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'jumlah_tamu' => ['required', 'integer', 'min:1', 'max:50'],
            'tanggal_reservasi' => ['required', 'date'],
            'waktu_reservasi' => ['required', 'date_format:H:i'],
            'status' => ['required', 'in:menunggu,dikonfirmasi,dibatalkan,selesai'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $reservasi->update($validated);

        if ($validated['status'] === 'dibatalkan' || $validated['status'] === 'selesai') {
            $meja = Meja::find($validated['meja_id']);
            if ($meja && $meja->status === 'reserved') {
                $meja->update(['status' => 'available']);
            }
        }

        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy(Reservasi $reservasi): RedirectResponse
    {
        if ($reservasi->status === 'dikonfirmasi') {
            return back()->with('error', 'Reservasi yang sudah dikonfirmasi tidak dapat dihapus.');
        }

        $meja = $reservasi->meja;
        $reservasi->delete();

        if ($meja && $meja->status === 'reserved') {
            $hasOtherReservation = Reservasi::where('meja_id', $meja->id)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->exists();

            if (! $hasOtherReservation) {
                $meja->update(['status' => 'available']);
            }
        }

        return redirect()->route('reservasis.index')->with('success', 'Reservasi berhasil dihapus.');
    }
}
