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
        Schema::create('foto_surat_masuk', function (Blueprint $table) {
            $table->increments('id');
            // $table->bigInteger('id_surat')->unsigned();
            $table->foreignId('id_surat')->constrained('surats')->onDelete('cascade');
            $table->string('filename');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_surat_masuk');
    }
};
