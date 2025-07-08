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

        $currentStatus = $this->getCurrentStatus($siswa);
        
        $riwayatKonseling = $this->getRiwayatKonseling($siswa);
        
        $laporanTersedia = $this->getLaporanTersedia($siswa);
        
        return view('siswa.dashboard', compact(
            'currentStatus',
            'riwayatKonseling',
            'laporanTersedia'
        ));
    }

    private function getCurrentStatus($siswa)
    {
        $permohonanMenunggu = PermohonanKonseling::with(['guruBK'])
            ->where('siswa_id', $siswa->id)
            ->where('status', 'menunggu')
            ->first();

        $permohonanDisetujui = PermohonanKonseling::with(['guruBK'])
            ->where('siswa_id', $siswa->id)
            ->where('status', 'disetujui')
            ->whereDoesntHave('jadwalKonseling', function($query) {
                $query->whereIn('status', ['dijadwalkan', 'berlangsung']);
            })
            ->first();

        $jadwalMendatang = JadwalKonseling::with(['guruBK', 'permohonanKonseling'])
            ->where('siswa_id', $siswa->id)
            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->orderBy('tanggal_konseling', 'asc')
            ->first();

        if ($jadwalMendatang) {
            return [
                'permohonan_aktif' => null,
                'permohonan_disetujui' => null,
                'jadwal_mendatang' => $jadwalMendatang,
                'can_create_new' => false
            ];
        } elseif ($permohonanMenunggu) {
            return [
                'permohonan_aktif' => $permohonanMenunggu,
                'permohonan_disetujui' => null,
                'jadwal_mendatang' => null,
                'can_create_new' => false
            ];
        } elseif ($permohonanDisetujui) {
            return [
                'permohonan_aktif' => null,
                'permohonan_disetujui' => $permohonanDisetujui,
                'jadwal_mendatang' => null,
                'can_create_new' => false
            ];
        } else {
            return [
                'permohonan_aktif' => null,
                'permohonan_disetujui' => null,
                'jadwal_mendatang' => null,
                'can_create_new' => true
            ];
        }
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