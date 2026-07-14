<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_order',
        'meja_id',
        'user_id',
        'status',
        'total_harga',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'total_harga' => 'decimal:2',
        ];
    }

    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function hitungTotal(): int|float
    {
        return $this->detailPesanans()->sum('subtotal');
    }

    public function refreshTotal(): void
    {
        $this->update(['total_harga' => $this->hitungTotal()]);
    }

    public static function generateKodeOrder(): string
    {
        $tanggal = now()->format('Ymd');
        $urutan = static::whereDate('created_at', now())->count() + 1;

        return sprintf('ORD-%s-%04d', $tanggal, $urutan);
    }
}
