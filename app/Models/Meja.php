<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_meja',
        'kapasitas',
        'status',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reservasis(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    public function orderAktif()
    {
        return $this->orders()->whereIn('status', ['pending', 'diproses'])->latest()->first();
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'available');
    }
}
