<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gunung', function (Blueprint $table) {
            // nullable in case existing records don't have coords yet
            $table->decimal('latitude', 10, 7)->nullable()->after('lokasi');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('gunung', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
