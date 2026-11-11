<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rute_pendakian', function (Blueprint $table) {
            $table->id('id_rute');
            
            // ✅ relasi ke tabel gunung
            $table->unsignedBigInteger('gunung_id');
            $table->foreign('gunung_id')
                  ->references('id_gunung')
                  ->on('gunung')
                  ->onDelete('cascade');

            $table->string('nama_rute');
            $table->text('deskripsi')->nullable();
            $table->integer('harga')->default(50000);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rute_pendakian');
    }
};
