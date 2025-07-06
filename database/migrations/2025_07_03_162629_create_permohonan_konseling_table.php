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
        Schema::create('permohonan_konseling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->enum('jenis_konseling', ['pribadi', 'sosial', 'akademik', 'karir', 'lainnya']);
            $table->string('topik_konseling');
            $table->text('ringkasan_masalah');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'selesai'])->default('menunggu');
            
            // Field tambahan untuk tracking approval
            $table->foreignId('guru_bk_id')->nullable()->constrained('guru_bks')->onDelete('set null');
            $table->foreignId('diproses_oleh')->nullable()->constrained('guru_bks')->onDelete('set null');
            $table->text('catatan_guru_bk')->nullable();
            $table->timestamp('diproses_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['siswa_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('guru_bk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_konseling');
    }
};