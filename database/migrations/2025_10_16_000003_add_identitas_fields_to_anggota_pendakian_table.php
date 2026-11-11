<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggota_pendakian', function (Blueprint $table) {
            if (!Schema::hasColumn('anggota_pendakian', 'jenis_identitas')) {
                $table->enum('jenis_identitas', ['KTP', 'SIM', 'KK'])->nullable()->after('usia');
            }
            if (!Schema::hasColumn('anggota_pendakian', 'no_identitas')) {
                $table->string('no_identitas', 30)->nullable()->after('jenis_identitas');
            }
            if (!Schema::hasColumn('anggota_pendakian', 'foto_identitas')) {
                $table->string('foto_identitas')->nullable()->after('no_identitas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('anggota_pendakian', function (Blueprint $table) {
            $table->dropColumn(['jenis_identitas', 'no_identitas', 'foto_identitas']);
        });
    }
};
