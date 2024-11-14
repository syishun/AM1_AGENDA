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
        Schema::create('absen_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nama_mapel')->constrained('mapels')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tgl');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('keterangan');
            $table->string('tugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_gurus');
    }
};
