<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')->constrained('mejas')->cascadeOnDelete();
            $table->string('nama_pemesan');
            $table->string('no_telepon');
            $table->unsignedTinyInteger('jumlah_tamu');
            $table->date('tanggal_reservasi');
            $table->time('waktu_reservasi');
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'dibatalkan', 'selesai'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
