<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nisn',
        'nama_lengkap',
        'kelas',
        'jurusan',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'no_telp',
        'alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permohonanKonseling()
    {
        return $this->hasMany(PermohonanKonseling::class, 'siswa_id');
    }

    public function jadwalKonseling()
    {
        return $this->hasMany(JadwalKonseling::class, 'siswa_id');
    }

    public function getFullNameAttribute()
    {
        return $this->nama_lengkap . ' (NISN: ' . $this->nisn . ')';
    }

    public function getAgeAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }
}