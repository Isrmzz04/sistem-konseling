<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_konseling_id')->constrained('jadwal_konseling')->onDelete('cascade');
            $table->string('dokumen_laporan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_bimbingan');
    }
};