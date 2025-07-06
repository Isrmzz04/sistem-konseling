<?php
// Model GuruBK.php - Tambahkan relationship ini

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruBK extends Model
{
    use HasFactory;

    protected $table = 'guru_bks';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telp',
        'alamat',
        'is_active'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    // Existing relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // TAMBAHKAN RELATIONSHIPS INI:
    
    public function permohonanKonseling()
    {
        return $this->hasMany(PermohonanKonseling::class, 'guru_bk_id');
    }

    public function jadwalKonseling()
    {
        return $this->hasMany(JadwalKonseling::class, 'guru_bk_id');
    }

    public function laporanBimbingan()
    {
        return $this->hasManyThrough(LaporanBimbingan::class, JadwalKonseling::class, 'guru_bk_id', 'jadwal_konseling_id');
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    // Scope untuk filter aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}