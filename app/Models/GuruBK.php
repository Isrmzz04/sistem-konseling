<?php

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
        'is_active',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function jadwalKonseling()
    // {
    //     return $this->hasMany(JadwalKonseling::class);
    // }

    public function getFullNameAttribute()
    {
        return $this->nama_lengkap . ' (NIP: ' . $this->nip . ')';
    }

    public function getAgeAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }
}