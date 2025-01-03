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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('satuan_honor', 20);
            $table->bigInteger('honor');
            $table->string('flag', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('kegiatan_pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->bigInteger('translok')->nullable();
            $table->timestamps();
        });

        Schema::create('kegiatan_mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
            $table->foreignId('mitra_id')->constrained('mitras')->onDelete('cascade');
            $table->bigInteger('honor')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });

        Schema::table('pegawais', function (Blueprint $table) {
            $table->string('nip_bps', 9)->nullable();
            $table->string('kode_orang', 5)->nullable();
            $table->string('jabatan', 50)->nullable();
            $table->string('golongan', 5)->nullable();
            $table->string('status', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_mitras');
        Schema::dropIfExists('kegiatan_pegawais');
        Schema::dropIfExists('kegiatans');
    }
};
