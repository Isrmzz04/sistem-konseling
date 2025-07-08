<?php

namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use App\Models\PermohonanKonseling;
use App\Models\JadwalKonseling;
use App\Models\LaporanBimbingan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi. Silakan hubungi administrator.');
        }

        $statistics = $this->getStatistics($guruBK);
        
        $permohonanPending = $this->getPermohonanPending($guruBK);
        $jadwalHariIni = $this->getJadwalHariIni($guruBK);
        
        return view('guru_bk.dashboard', compact(
            'statistics',
            'permohonanPending',
            'jadwalHariIni'
        ));
    }

    private function getStatistics($guruBK)
    {
        $today = Carbon::today();

        return [
            'permohonan_pending' => PermohonanKonseling::where('guru_bk_id', $guruBK->id)
                ->where('status', 'menunggu')
                ->count(),
            
            'jadwal_hari_ini' => JadwalKonseling::where('guru_bk_id', $guruBK->id)
                ->whereDate('tanggal_konseling', $today)
                ->count(),
            
            'selesai_tanpa_laporan' => JadwalKonseling::where('guru_bk_id', $guruBK->id)
                ->where('status', 'selesai')
                ->whereDoesntHave('laporanBimbingan')
                ->count(),
        ];
    }

    private function getPermohonanPending($guruBK)
    {
        return PermohonanKonseling::with(['siswa.user'])
            ->where('guru_bk_id', $guruBK->id)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();
    }

    private function getJadwalHariIni($guruBK)
    {
        return JadwalKonseling::with(['siswa.user', 'permohonanKonseling'])
            ->where('guru_bk_id', $guruBK->id)
            ->whereDate('tanggal_konseling', Carbon::today())
            ->orderBy('jam_mulai', 'asc')
            ->get();
    }
}