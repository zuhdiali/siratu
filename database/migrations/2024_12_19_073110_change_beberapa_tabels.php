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
        // Schema::create('surats', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nomor_surat', 100);
        //     $table->string('jenis_surat', 20);
        //     $table->integer('no_terakhir');
        //     $table->string('tgl_kegiatan')->nullable();
        //     $table->string('perihal')->nullable();
        //     $table->string('no_surat_masuk', 100)->nullable();
        //     $table->string('dinas_surat_masuk', 100)->nullable();
        //     $table->string('file')->nullable();
        //     $table->foreignId('id_kegiatan')->constrained('kegiatans')->onDelete('cascade');
        //     $table->foreignId('id_pembuat_surat')->constrained('pegawais')->onDelete('cascade');
        //     $table->timestamps();
        // });

        // Schema::table('kegiatans', function (Blueprint $table) {
        //     $table->renameColumn('satuan_honor', 'satuan_honor_pengawasan');
        //     $table->string('satuan_honor_pencacahan', 20)->nullable();
        //     $table->renameColumn('honor', 'honor_pengawasan');
        //     $table->string('honor_pencacahan', 20)->nullable();
        // });

        Schema::table('pegawais', function (Blueprint $table) {
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('role', 20);
            $table->string('tim', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
