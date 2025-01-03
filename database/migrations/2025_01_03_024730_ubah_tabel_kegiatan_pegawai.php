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
        Schema::table('kegiatan_pegawais', function (Blueprint $table) {
            $table->unsignedInteger('bukti_pembayaran_id')->after('translok')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_pegawais', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran_id');
        });
    }
};
