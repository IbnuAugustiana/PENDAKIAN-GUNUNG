<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->decimal('biaya_alat', 10, 2)->nullable()->after('total_biaya');
            $table->enum('status_peminjaman', ['menunggu', 'disetujui', 'ditolak', 'selesai'])->nullable()->after('biaya_alat');
            $table->enum('status_verifikasi_peminjaman', ['belum_verifikasi', 'sedang_verifikasi', 'terverifikasi'])->nullable()->after('status_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['biaya_alat', 'status_peminjaman', 'status_verifikasi_peminjaman']);
        });
    }
};
