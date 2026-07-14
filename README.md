# 🍽️ Fine Dining POS (Point of Sale)

Sistem Point of Sale modern dan elegan yang dirancang khusus untuk restoran Fine Dining. Dibangun menggunakan framework **Laravel 11**, sistem ini menawarkan antarmuka UI/UX premium berbasis *Glassmorphism* dan manajemen operasional restoran yang terintegrasi penuh.

## ✨ Fitur Utama

Sistem ini memiliki 5 modul utama yang dirancang untuk mempercepat dan mempermudah operasional restoran:

### 1. 📊 Dashboard Interaktif
* **Statistik Realtime:** Pantau jumlah meja tersedia, total order yang sedang diproses, pendapatan hari ini, dan jumlah reservasi hari ini secara instan.
* **Tinjauan Order:** Melihat sekilas order terbaru beserta statusnya.
* **Performa Menu:** Menampilkan daftar menu terlaris untuk analisis penjualan.
* **Peringatan Stok:** Memberikan notifikasi otomatis (badge peringatan) untuk menu yang stoknya menipis (≤ 5 item).

### 2. 🪑 Manajemen Meja
* **CRUD Meja:** Menambah, mengedit, dan menghapus data meja.
* **Kapasitas Tamu:** Menentukan kapasitas maksimal untuk masing-masing meja.
* **Status Otomatis & Manual:** 
  * Meja otomatis berstatus `occupied` (Terisi) saat ada order aktif.
  * Meja otomatis kembali menjadi `available` (Tersedia) setelah pembayaran lunas atau order dibatalkan.
  * Mendukung status `reserved` (Dipesan) untuk tamu reservasi.

### 3. 📖 Manajemen Menu
* **Kategorisasi Menu:** Pisahkan menu menjadi *Appetizer*, *Main Course*, *Dessert*, dan *Drink*.
* **Manajemen Harga & Stok:** Atur harga dan jumlah ketersediaan stok fisik.
* **Ketersediaan Menu:** Tombol *toggle* cepat untuk menonaktifkan menu yang habis tanpa harus mengubah stok ke 0.
* **Pencarian Cepat:** Filter menu berdasarkan nama dan kategori.

### 4. 🛒 Sistem POS (Point of Sale) & Order
* **Antarmuka Kasir (POS):** Layar pemesanan satu pintu yang membagi area pemilihan meja, pemilihan menu (dengan *Live Search* dan filter kategori), dan keranjang belanja (Cart) di sisi kanan.
* **Sinkronisasi Stok:** Stok menu otomatis berkurang (dikunci dengan `lockForUpdate`) saat order diproses, dan otomatis kembali jika order dibatalkan.
* **Manajemen Status Order:** Lacak status pesanan (`Pending`, `Diproses`, `Selesai`, `Dibatalkan`).
* **Nota Catatan:** Tambahkan catatan khusus pada tiap pesanan (misal: "Kurangi garam", "Alergi kacang").
* **Ubah Order Aktif:** Tambah atau hapus item dari order yang sedang berjalan sebelum dibayar.

### 5. 💰 Sistem Pembayaran
* **Multiple Payment Methods:** Mendukung metode pembayaran Tunai, Kartu Debit, Kartu Kredit, dan QRIS.
* **Quick Cash Amount:** Tombol cepat (Quick Amount) untuk pembayaran tunai berdasar kelipatan uang (misal: bayar dengan Rp 100.000 atau Rp 50.000).
* **Kalkulasi Otomatis:** Perhitungan otomatis untuk kembalian pelanggan.
* **Cetak Struk:** Halaman struk minimalis (*print-ready*) lengkap dengan rincian pesanan dan status lunas.

---

## 🎨 UI/UX Design

* **Premium Theme:** Menggunakan palet warna Midnight Blue dipadukan dengan aksen Emas (Gold) untuk kesan eksklusif.
* **Glassmorphism:** Elemen transparan pada kartu (*cards*) untuk nuansa modern.
* **Micro-Animations:** Efek sentuhan lembut pada *hover* tombol, kartu statistik, dan item menu di area POS.
* **Responsive Layout:** Tampilan *Sidebar* dan Grid sistem menyesuaikan otomatis pada perangkat Mobile, Tablet, maupun Desktop.

---

## 🚀 Instalasi & Menjalankan Project

Ikuti panduan berikut untuk menjalankan project di sistem lokal Anda:

### Prasyarat
* PHP 8.2 atau lebih tinggi
* Composer
* MySQL / MariaDB

### Langkah Instalasi
1. Clone repository ini:
   ```bash
   git clone <url-repository>
   cd pos-restaurant
   ```

2. Copy file konfigurasi *environment*:
   ```bash
   cp .env.example .env
   ```

3. Install dependensi PHP via Composer:
   ```bash
   composer install
   ```

4. Generate App Key Laravel:
   ```bash
   php artisan key:generate
   ```

5. Konfigurasi `.env`:
   Buka file `.env` dan pastikan pengaturan database Anda sudah benar:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pos_restaurant
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Jalankan Migrasi & Seeder Database:
   *(Perintah ini akan membuat semua struktur tabel dan mengisi data awal (dummy) termasuk akun Admin, Meja, dan Menu)*
   ```bash
   php artisan migrate:fresh --seed
   ```

7. Jalankan Server:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di: **http://localhost:8000**

---

## 🔑 Kredensial Login (Seeder)

Anda dapat menggunakan akun berikut untuk masuk ke sistem:

* **Username:** `admin`
* **Password:** `admin123`

---
*Dibuat untuk memenuhi tugas Rekayasa Web Semester 6 — Sistem Point of Sale Restoran.*
