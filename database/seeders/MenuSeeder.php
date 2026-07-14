<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Appetizer
            ['nama_menu' => 'Bruschetta Truffle', 'kategori' => 'Appetizer', 'harga' => 85000, 'stok' => 30, 'tersedia' => true],
            ['nama_menu' => 'Carpaccio Wagyu', 'kategori' => 'Appetizer', 'harga' => 125000, 'stok' => 20, 'tersedia' => true],
            ['nama_menu' => 'Salmon Tartare', 'kategori' => 'Appetizer', 'harga' => 110000, 'stok' => 25, 'tersedia' => true],
            ['nama_menu' => 'French Onion Soup', 'kategori' => 'Appetizer', 'harga' => 75000, 'stok' => 35, 'tersedia' => true],
            ['nama_menu' => 'Caesar Salad Premium', 'kategori' => 'Appetizer', 'harga' => 65000, 'stok' => 40, 'tersedia' => true],

            // Main Course
            ['nama_menu' => 'Wagyu Steak A5', 'kategori' => 'Main Course', 'harga' => 550000, 'stok' => 10, 'tersedia' => true],
            ['nama_menu' => 'Grilled Salmon Fillet', 'kategori' => 'Main Course', 'harga' => 285000, 'stok' => 15, 'tersedia' => true],
            ['nama_menu' => 'Lobster Thermidor', 'kategori' => 'Main Course', 'harga' => 450000, 'stok' => 8, 'tersedia' => true],
            ['nama_menu' => 'Rack of Lamb', 'kategori' => 'Main Course', 'harga' => 380000, 'stok' => 12, 'tersedia' => true],
            ['nama_menu' => 'Risotto Al Tartufo', 'kategori' => 'Main Course', 'harga' => 195000, 'stok' => 20, 'tersedia' => true],
            ['nama_menu' => 'Duck Confit', 'kategori' => 'Main Course', 'harga' => 275000, 'stok' => 15, 'tersedia' => true],
            ['nama_menu' => 'Pasta Aglio Olio Seafood', 'kategori' => 'Main Course', 'harga' => 165000, 'stok' => 25, 'tersedia' => true],

            // Dessert
            ['nama_menu' => 'Crème Brûlée', 'kategori' => 'Dessert', 'harga' => 75000, 'stok' => 30, 'tersedia' => true],
            ['nama_menu' => 'Tiramisu Classico', 'kategori' => 'Dessert', 'harga' => 85000, 'stok' => 25, 'tersedia' => true],
            ['nama_menu' => 'Chocolate Lava Cake', 'kategori' => 'Dessert', 'harga' => 90000, 'stok' => 20, 'tersedia' => true],
            ['nama_menu' => 'Panna Cotta Berry', 'kategori' => 'Dessert', 'harga' => 70000, 'stok' => 25, 'tersedia' => true],

            // Drink
            ['nama_menu' => 'Espresso Doppio', 'kategori' => 'Drink', 'harga' => 45000, 'stok' => 50, 'tersedia' => true],
            ['nama_menu' => 'Mocktail Sunset Bliss', 'kategori' => 'Drink', 'harga' => 65000, 'stok' => 40, 'tersedia' => true],
            ['nama_menu' => 'Fresh Juice Seasonal', 'kategori' => 'Drink', 'harga' => 55000, 'stok' => 45, 'tersedia' => true],
            ['nama_menu' => 'Mineral Water Premium', 'kategori' => 'Drink', 'harga' => 35000, 'stok' => 100, 'tersedia' => true],
            ['nama_menu' => 'Matcha Latte', 'kategori' => 'Drink', 'harga' => 55000, 'stok' => 40, 'tersedia' => true],
            ['nama_menu' => 'Hot Chocolate Valrhona', 'kategori' => 'Drink', 'harga' => 60000, 'stok' => 35, 'tersedia' => true],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
