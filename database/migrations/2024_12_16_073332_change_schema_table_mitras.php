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
        Schema::table('mitras', function (Blueprint $table) {
            $table->string('kec_asal', 3)->change();
            $table->string('id_mitra', 20)->change();
            $table->string('flag', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->string('kec_asal', 255)->change();
            $table->string('id_mitra', 255)->change();
            $table->dropColumn('flag');
        });
    }
};
