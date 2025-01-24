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
        Schema::table('kegiatan_mitras', function (Blueprint $table) {
            $table->boolean('is_pml')->default(false)->after('estimasi_honor');
        });

        Schema::create('sbks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('tugas');
            $table->string('satuan');
            $table->unsignedBigInteger('honor_per_satuan');
            $table->string('tim', 5);
            $table->boolean('ada_di_simeulue');
            $table->unsignedBigInteger('pjk');
            $table->string('singkatan_resmi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_mitras', function (Blueprint $table) {
            $table->dropColumn('is_pml');
        });

        Schema::dropIfExists('sbks');
    }
};
