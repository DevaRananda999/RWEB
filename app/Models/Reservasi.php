<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'meja_id',
        'nama_pemesan',
        'no_telepon',
        'jumlah_tamu',
        'tanggal_reservasi',
        'waktu_reservasi',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_reservasi' => 'date',
        ];
    }

    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class);
    }
}
