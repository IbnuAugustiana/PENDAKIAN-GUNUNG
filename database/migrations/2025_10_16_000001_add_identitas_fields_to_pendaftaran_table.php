<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran', 'jenis_identitas')) {
                $table->enum('jenis_identitas', ['KTP', 'SIM', 'KK'])->nullable()->after('jumlah_pendaki');
            }
            if (!Schema::hasColumn('pendaftaran', 'no_identitas')) {
                $table->string('no_identitas', 30)->nullable()->after('jenis_identitas');
            }
            if (!Schema::hasColumn('pendaftaran', 'foto_identitas')) {
                $table->string('foto_identitas')->nullable()->after('no_identitas');
            }
            if (!Schema::hasColumn('pendaftaran', 'metode_pembayaran')) {
                $table->string('metode_pembayaran', 50)->nullable()->after('status_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['jenis_identitas', 'no_identitas', 'foto_identitas', 'metode_pembayaran']);
        });
    }
};
