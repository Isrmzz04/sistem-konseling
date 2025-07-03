<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanBimbingan extends Model
{
    use HasFactory;

    protected $table = 'laporan_bimbingan';

    protected $fillable = [
        'jadwal_konseling_id',
        'dokumen_laporan',
    ];

    public function jadwalKonseling()
    {
        return $this->belongsTo(JadwalKonseling::class);
    }
}