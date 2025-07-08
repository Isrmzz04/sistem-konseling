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

        $jadwalKonseling = JadwalKonseling::with([
                'permohonanKonseling',
                'guruBK.user'
            ])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->orderBy('tanggal_konseling', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();
        
        return view('siswa.jadwal.index', compact('jadwalKonseling'));
    }

    public function riwayat()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa belum dilengkapi.');
        }

        $riwayatKonseling = JadwalKonseling::with([
                'permohonanKonseling',
                'guruBK.user',
                'laporanBimbingan'
            ])
            ->where('siswa_id', $siswa->id)
            ->whereHas('guruBK')
            ->whereHas('permohonanKonseling')
            ->orderBy('tanggal_konseling', 'desc')
            ->paginate(10);
        
        return view('siswa.riwayat.index', compact('riwayatKonseling'));
    }
}