<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('anggota_pendakian', function (Blueprint $table) {
            $table->id('id_anggota');

            // Relasi ke pendaftaran
            $table->unsignedBigInteger('pendaftaran_id');
            $table->foreign('pendaftaran_id')->references('id_pendaftaran')->on('pendaftaran')->onDelete('cascade');

            $table->string('nama');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->integer('usia');
            $table->string('no_telepon');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('anggota_pendakian');
    }
};
