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
        'guru_bk_id',
        'jenis_konseling',
        'topik_konseling',
        'ringkasan_masalah',
        'status',
        'diproses_oleh',
        'catatan_guru_bk',
        'diproses_at'
    ];

    protected $casts = [
        'diproses_at' => 'datetime',
    ];

    // Existing relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guruBK()
    {
        return $this->belongsTo(GuruBK::class, 'guru_bk_id');
    }

    // PASTIKAN RELATIONSHIP INI ADA:
    public function diprosesoleh()
    {
        return $this->belongsTo(GuruBK::class, 'diproses_oleh');
    }

    public function jadwalKonseling()
    {
        return $this->hasMany(JadwalKonseling::class);
    }

    // Methods
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

    // Scope untuk filter
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_konseling', $jenis);
    }

    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    // Accessor untuk status dengan warna
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'menunggu' => 'yellow',
            'disetujui' => 'green',
            'ditolak' => 'red',
            'selesai' => 'blue',
            default => 'gray'
        };
    }

    // Accessor untuk jenis konseling dengan warna
    public function getJenisColorAttribute()
    {
        return match ($this->jenis_konseling) {
            'pribadi' => 'blue',
            'sosial' => 'green',
            'akademik' => 'purple',
            'karir' => 'orange',
            'lainnya' => 'gray',
            default => 'gray'
        };
    }
}