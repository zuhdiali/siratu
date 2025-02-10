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
        Schema::table('surats', function (Blueprint $table) {
            $table->unsignedInteger('mitra_spk')->nullable()->after('flag');
            $table->unsignedTinyInteger('bulan_spk')->nullable()->after('flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn('mitra_spk');
            $table->dropColumn('bulan_spk');
        });
    }
};
