<?php

namespace Database\Seeders;

use App\Models\Meja;
use Illuminate\Database\Seeder;

class MejaSeeder extends Seeder
{
    public function run(): void
    {
        $mejas = [
            ['nomor_meja' => 'A1', 'kapasitas' => 2, 'status' => 'available'],
            ['nomor_meja' => 'A2', 'kapasitas' => 2, 'status' => 'available'],
            ['nomor_meja' => 'A3', 'kapasitas' => 2, 'status' => 'available'],
            ['nomor_meja' => 'B1', 'kapasitas' => 4, 'status' => 'available'],
            ['nomor_meja' => 'B2', 'kapasitas' => 4, 'status' => 'available'],
            ['nomor_meja' => 'B3', 'kapasitas' => 4, 'status' => 'available'],
            ['nomor_meja' => 'C1', 'kapasitas' => 6, 'status' => 'available'],
            ['nomor_meja' => 'C2', 'kapasitas' => 6, 'status' => 'available'],
            ['nomor_meja' => 'D1', 'kapasitas' => 8, 'status' => 'available'],
            ['nomor_meja' => 'D2', 'kapasitas' => 8, 'status' => 'available'],
            ['nomor_meja' => 'VIP1', 'kapasitas' => 10, 'status' => 'available'],
            ['nomor_meja' => 'VIP2', 'kapasitas' => 12, 'status' => 'available'],
        ];

        foreach ($mejas as $meja) {
            Meja::create($meja);
        }
    }
}
