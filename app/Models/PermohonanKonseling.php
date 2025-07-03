<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanKonseling extends Model
{
    use HasFactory;

    protected $table = 'permohonan_konseling';

    protected $fillable = [
        'siswa_id',
        'jenis_konseling',
        'topik_konseling',
        'ringkasan_masalah',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jadwalKonseling()
    {
        return $this->hasMany(JadwalKonseling::class);
    }

    public function isMenunggu()
    {
        return $this->status === 'menunggu';
    }

    public function isDisetujui()
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak()
    {
        return $this->status === 'ditolak';
    }

    public function isSelesai()
    {
        return $this->status === 'selesai';
    }
}