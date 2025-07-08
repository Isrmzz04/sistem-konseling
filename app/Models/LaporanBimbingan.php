<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanBimbingan extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'laporan_bimbingan';

    protected $fillable = [
        'jadwal_konseling_id',
        'dokumen_laporan',
    ];

    public function jadwalKonseling()
    {
        return $this->belongsTo(JadwalKonseling::class, 'jadwal_konseling_id');
    }

    public function getFileNameAttribute()
    {
        return basename($this->dokumen_laporan);
    }

    public function getDownloadFileNameAttribute()
    {
        if ($this->jadwalKonseling && $this->jadwalKonseling->siswa) {
            $siswa = $this->jadwalKonseling->siswa;
            $tanggal = $this->jadwalKonseling->tanggal_konseling->format('Y-m-d');
            
            $namaLengkap = str_replace('_', ' ', $siswa->nama_lengkap);
            $kelas = str_replace('_', ' ', $siswa->kelas);
            $jurusan = str_replace('_', ' ', $siswa->jurusan);
            
            $extension = pathinfo($this->dokumen_laporan, PATHINFO_EXTENSION);
            
            return "Laporan_{$namaLengkap}_{$kelas}_{$jurusan}_{$tanggal}.{$extension}";
        }
        
        return basename($this->dokumen_laporan);
    }

    public function getFilePathAttribute()
    {
        return storage_path('app/' . $this->dokumen_laporan);
    }

    public function getDownloadUrlAttribute()
    {
        return route('guru_bk.laporan.download', $this);
    }

    public function fileExists()
    {
        return file_exists($this->file_path);
    }
}