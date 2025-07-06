<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\PermohonanKonseling;
use App\Models\JadwalKonseling;
use App\Models\LaporanBimbingan;
use App\Models\GuruBK;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics Cards
        $statistics = $this->getStatistics();
        
        // Recent Activities
        $recentActivities = $this->getRecentActivities();
        
        // Quick Tables
        $pendingPermohonan = $this->getPendingPermohonan();
        $jadwalHariIni = $this->getJadwalHariIni();
        
        return view('administrator.dashboard', compact(
            'statistics',
            'recentActivities', 
            'pendingPermohonan',
            'jadwalHariIni'
        ));
    }

    private function getStatistics()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            // Permohonan hari ini
            'permohonan_hari_ini' => PermohonanKonseling::whereDate('created_at', $today)->count(),
            
            // Permohonan minggu ini  
            'permohonan_minggu_ini' => PermohonanKonseling::where('created_at', '>=', $thisWeek)->count(),
            
            // Konseling berlangsung saat ini
            'konseling_berlangsung' => JadwalKonseling::where('status', 'berlangsung')->count(),
            
            // Jadwal hari ini
            'jadwal_hari_ini' => JadwalKonseling::whereDate('tanggal_konseling', $today)->count(),
            
            // Total permohonan pending
            'permohonan_pending' => PermohonanKonseling::where('status', 'menunggu')->count(),
            
            // Tingkat penyelesaian bulan ini (%)
            'tingkat_penyelesaian' => $this->getTingkatPenyelesaian($thisMonth),
            
            // Guru BK paling aktif bulan ini
            'guru_bk_aktif' => $this->getGuruBKAktif($thisMonth),
            
            // Rata-rata waktu response (hari)
            'rata_response' => $this->getRataResponse(),
        ];
    }

    private function getTingkatPenyelesaian($startDate)
    {
        $totalKonseling = JadwalKonseling::where('created_at', '>=', $startDate)->count();
        $konselingSelesai = JadwalKonseling::where('created_at', '>=', $startDate)
                                         ->where('status', 'selesai')
                                         ->count();
        
        return $totalKonseling > 0 ? round(($konselingSelesai / $totalKonseling) * 100, 1) : 0;
    }

    private function getGuruBKAktif($startDate)
    {
        $guruBK = GuruBK::withCount(['jadwalKonseling' => function ($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }])
        ->orderBy('jadwal_konseling_count', 'desc')
        ->first();

        return $guruBK ? [
            'nama' => $guruBK->nama_lengkap,
            'jumlah' => $guruBK->jadwal_konseling_count
        ] : ['nama' => 'Belum ada', 'jumlah' => 0];
    }

    private function getRataResponse()
    {
        $avgDays = PermohonanKonseling::whereNotNull('diproses_at')
            ->selectRaw('AVG(DATEDIFF(diproses_at, created_at)) as avg_days')
            ->first()
            ->avg_days;

        return $avgDays ? round($avgDays, 1) : 0;
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Recent Permohonan (3 hari terakhir)
        $recentPermohonan = PermohonanKonseling::with(['siswa', 'guruBK'])
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'permohonan_baru',
                    'title' => 'Permohonan Konseling Baru',
                    'description' => "{$item->siswa->nama_lengkap} mengajukan konseling {$item->jenis_konseling}",
                    'time' => $item->created_at,
                    'icon' => 'fas fa-file-plus',
                    'color' => 'blue',
                    'meta' => "Ke: {$item->guruBK->nama_lengkap}"
                ];
            });

        // Recent Status Changes (3 hari terakhir)
        $recentStatusChanges = PermohonanKonseling::with(['siswa', 'diprosesoleh'])
            ->whereNotNull('diproses_at')
            ->where('diproses_at', '>=', Carbon::now()->subDays(3))
            ->get()
            ->map(function ($item) {
                $colors = [
                    'disetujui' => 'green',
                    'ditolak' => 'red'
                ];
                
                return [
                    'type' => 'status_change',
                    'title' => 'Permohonan ' . ucfirst($item->status),
                    'description' => "Permohonan {$item->siswa->nama_lengkap} {$item->status}",
                    'time' => $item->diproses_at,
                    'icon' => $item->status === 'disetujui' ? 'fas fa-check-circle' : 'fas fa-times-circle',
                    'color' => $colors[$item->status] ?? 'gray',
                    'meta' => $item->diprosesoleh ? "Oleh: {$item->diprosesoleh->nama_lengkap}" : ''
                ];
            });

        // Recent Jadwal (3 hari terakhir)
        $recentJadwal = JadwalKonseling::with(['siswa', 'guruBK'])
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'jadwal_baru',
                    'title' => 'Jadwal Konseling Dibuat',
                    'description' => "Jadwal untuk {$item->siswa->nama_lengkap}",
                    'time' => $item->created_at,
                    'icon' => 'fas fa-calendar-plus',
                    'color' => 'purple',
                    'meta' => "{$item->tanggal_konseling->format('d/m/Y')} oleh {$item->guruBK->nama_lengkap}"
                ];
            });

        // Recent Konseling Selesai (3 hari terakhir)
        $recentSelesai = JadwalKonseling::with(['siswa', 'guruBK'])
            ->where('status', 'selesai')
            ->where('updated_at', '>=', Carbon::now()->subDays(3))
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'konseling_selesai',
                    'title' => 'Konseling Selesai',
                    'description' => "Konseling {$item->siswa->nama_lengkap} telah selesai",
                    'time' => $item->updated_at,
                    'icon' => 'fas fa-check-double',
                    'color' => 'green',
                    'meta' => "Dengan {$item->guruBK->nama_lengkap}"
                ];
            });

        // Recent Laporan (3 hari terakhir)
        $recentLaporan = LaporanBimbingan::with(['jadwalKonseling.siswa', 'jadwalKonseling.guruBK'])
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'laporan_baru',
                    'title' => 'Laporan Bimbingan Dibuat',
                    'description' => "Laporan untuk {$item->jadwalKonseling->siswa->nama_lengkap}",
                    'time' => $item->created_at,
                    'icon' => 'fas fa-file-alt',
                    'color' => 'orange',
                    'meta' => "Oleh {$item->jadwalKonseling->guruBK->nama_lengkap}"
                ];
            });

        // Combine dan sort by time
        $activities = $activities
            ->concat($recentPermohonan)
            ->concat($recentStatusChanges)
            ->concat($recentJadwal)
            ->concat($recentSelesai)
            ->concat($recentLaporan)
            ->sortByDesc('time')
            ->take(10);

        return $activities;
    }

    private function getPendingPermohonan()
    {
        return PermohonanKonseling::with(['siswa', 'guruBK'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();
    }

    private function getJadwalHariIni()
    {
        return JadwalKonseling::with(['siswa', 'guruBK', 'permohonanKonseling'])
            ->whereDate('tanggal_konseling', Carbon::today())
            ->orderBy('jam_mulai', 'asc')
            ->get();
    }
}