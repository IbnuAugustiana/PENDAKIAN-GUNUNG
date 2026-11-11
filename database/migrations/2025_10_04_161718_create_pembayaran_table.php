<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');

            // Relasi ke pendaftaran
            $table->unsignedBigInteger('id_pendaftaran');
            $table->foreign('id_pendaftaran')->references('id_pendaftaran')->on('pendaftaran')->onDelete('cascade');

            $table->decimal('jumlah_bayar', 10, 2); // contoh: 150000.00
            $table->date('tanggal_bayar');
            $table->enum('status', ['pending', 'lunas', 'gagal'])->default('pending');
            $table->string('bukti_bayar')->nullable(); // opsional

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pembayaran');
    }
};
