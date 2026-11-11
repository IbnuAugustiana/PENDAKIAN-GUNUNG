<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gunung', function (Blueprint $table) {
            $table->id('id_gunung');
            $table->string('nama_gunung');
            $table->string('lokasi');
             $table->integer('harga_simaksi')->default(0);
            $table->integer('ketinggian');
            $table->text('deskripsi')->nullable(); // ✅ tambahkan kolom deskripsi
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gunung');
    }
};
