<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalKonseling;

class JadwalKonselingController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa belum dilengkapi. Silakan hubungi administrator.');
        }

        // Ambil jadwal konseling siswa yang masih aktif (belum selesai)
        $jadwalKonseling = JadwalKonseling::with([
                'permohonanKonseling',
                'guruBK.user'
            ])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->orderBy('tanggal_konseling', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get(); // Ubah ke get() agar bisa menggunakan collection methods
        
        return view('siswa.jadwal.index', compact('jadwalKonseling'));
    }

    public function riwayat()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa belum dilengkapi.');
        }

        // Ambil riwayat konseling siswa (semua status) dengan eager loading yang lebih robust
        $riwayatKonseling = JadwalKonseling::with([
                'permohonanKonseling',
                'guruBK.user',
                'laporanBimbingan'
            ])
            ->where('siswa_id', $siswa->id)
            ->whereHas('guruBK') // Pastikan ada guru BK
            ->whereHas('permohonanKonseling') // Pastikan ada permohonan
            ->orderBy('tanggal_konseling', 'desc')
            ->paginate(10);
        
        return view('siswa.riwayat.index', compact('riwayatKonseling'));
    }
}