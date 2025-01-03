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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 100);
            $table->string('jenis_surat', 20);
            $table->integer('no_terakhir');
            $table->string('tgl_kegiatan')->nullable();
            $table->string('perihal')->nullable();
            $table->string('no_surat_masuk', 100)->nullable();
            $table->string('dinas_surat_masuk', 100)->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
