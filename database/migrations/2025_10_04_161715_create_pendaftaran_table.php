<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');

            // Relasi ke user
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Relasi ke rute pendakian
            $table->unsignedBigInteger('rute_pendakian_id');
            $table->foreign('rute_pendakian_id')
                  ->references('id_rute')
                  ->on('rute_pendakian')
                  ->onDelete('cascade');

            // Informasi pendakian
            $table->date('tanggal_pendakian');
            $table->date('tanggal_turun')->nullable();
            $table->integer('jumlah_pendaki')->default(1);

            // Status dan pembayaran
            $table->string('status')->default('Menunggu');
            $table->string('status_pembayaran')->default('Belum');
            $table->decimal('total_biaya', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pendaftaran');
    }
};
