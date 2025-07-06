<?php
// Pastikan di Model JadwalKonseling ada relationship ini:

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKonseling extends Model
{
    use HasFactory;

    protected $table = 'jadwal_konseling';

    protected $fillable = [
        'permohonan_konseling_id',
        'guru_bk_id',
        'siswa_id',
        'tanggal_konseling',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'status',
        'dokumentasi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_konseling' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];
    
    // PASTIKAN RELATIONSHIP INI ADA DAN BENAR
    public function permohonanKonseling()
    {
        return $this->belongsTo(PermohonanKonseling::class, 'permohonan_konseling_id');
    }

    public function guruBK()
    {
        return $this->belongsTo(GuruBK::class, 'guru_bk_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function laporanBimbingan()
    {
        return $this->hasOne(LaporanBimbingan::class, 'jadwal_konseling_id');
    }

    // Methods
    public function isDijadwalkan()
    {
        return $this->status === 'dijadwalkan';
    }

    public function isBerlangsung()
    {
        return $this->status === 'berlangsung';
    }

    public function isSelesai()
    {
        return $this->status === 'selesai';
    }

    public function isDibatalkan()
    {
        return $this->status === 'dibatalkan';
    }
}