<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PermohonanKonseling;
use App\Models\JadwalKonseling;
use App\Models\LaporanBimbingan;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa belum dilengkapi. Silakan hubungi administrator.');
        }

        // Current Status
        $currentStatus = $this->getCurrentStatus($siswa);
        
        // Riwayat Konseling
        $riwayatKonseling = $this->getRiwayatKonseling($siswa);
        
        // Laporan Tersedia
        $laporanTersedia = $this->getLaporanTersedia($siswa);
        
        return view('siswa.dashboard', compact(
            'currentStatus',
            'riwayatKonseling',
            'laporanTersedia'
        ));
    }

    private function getCurrentStatus($siswa)
    {
        // Cek permohonan aktif (menunggu atau disetujui)
        $permohonanAktif = PermohonanKonseling::with(['guruBK'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->first();

        // Cek jadwal konseling mendatang
        $jadwalMendatang = JadwalKonseling::with(['guruBK', 'permohonanKonseling'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->orderBy('tanggal_konseling', 'asc')
            ->first();

        return [
            'permohonan_aktif' => $permohonanAktif,
            'jadwal_mendatang' => $jadwalMendatang,
            'can_create_new' => !$permohonanAktif && !$jadwalMendatang
        ];
    }

    private function getRiwayatKonseling($siswa)
    {
        return JadwalKonseling::with(['guruBK', 'permohonanKonseling', 'laporanBimbingan'])
            ->where('siswa_id', $siswa->id)
            ->where('status', 'selesai')
            ->orderBy('tanggal_konseling', 'desc')
            ->take(5)
            ->get();
    }

    private function getLaporanTersedia($siswa)
    {
        return LaporanBimbingan::with(['jadwalKonseling.guruBK', 'jadwalKonseling.permohonanKonseling'])
            ->whereHas('jadwalKonseling', function($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
}