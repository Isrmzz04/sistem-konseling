<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function isAdministrator(): bool
    {
        return $this->role === 'administrator';
    }

    public function isGuruBK(): bool
    {
        return $this->role === 'guru_bk';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'administrator' => 'administrator.dashboard',
            'guru_bk' => 'guru_bk.dashboard',
            'siswa' => 'siswa.dashboard',
            default => 'home'
        };
    }

    public function guruBK()
    {
        return $this->hasOne(GuruBK::class);
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function getProfile()
    {
        return match($this->role) {
            'guru_bk' => $this->guruBK,
            'siswa' => $this->siswa,
            default => null
        };
    }

    public function getDisplayName(): string
    {
        $profile = $this->getProfile();
        return $profile ? $profile->nama_lengkap : $this->username;
    }
}