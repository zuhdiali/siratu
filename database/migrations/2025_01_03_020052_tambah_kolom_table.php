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
            $table->renameColumn('tgl_kegiatan', 'tgl_awal_kegiatan');
            $table->date('tgl_akhir_kegiatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->dropColumn('tgl_akhir_kegiatan');
            $table->renameColumn('tgl_awal_kegiatan', 'tgl_kegiatan');
        });
    }
};
