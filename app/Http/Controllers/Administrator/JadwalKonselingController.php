<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\JadwalKonseling;
use App\Models\GuruBK;
use App\Models\Siswa;
use Illuminate\Http\Request;

class JadwalKonselingController extends Controller
{
    public function index(Request $request)
    {
        // Query builder dengan eager loading
        $query = JadwalKonseling::with([
            'permohonanKonseling.siswa.user',
            'siswa.user',
            'guruBK.user',
            'laporanBimbingan'
        ]);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($siswaQuery) use ($search) {
                    $siswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                              ->orWhere('nisn', 'like', "%{$search}%")
                              ->orWhere('kelas', 'like', "%{$search}%");
                })->orWhereHas('guruBK', function($guruQuery) use ($search) {
                    $guruQuery->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhereHas('permohonanKonseling', function($permohonanQuery) use ($search) {
                    $permohonanQuery->where('topik_konseling', 'like', "%{$search}%");
                })->orWhere('tempat', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan guru BK
        if ($request->filled('guru_bk_id')) {
            $query->where('guru_bk_id', $request->guru_bk_id);
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function($siswaQuery) use ($request) {
                $siswaQuery->where('kelas', $request->kelas);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_konseling', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_konseling', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan hari ini
        if ($request->filled('hari_ini')) {
            $query->whereDate('tanggal_konseling', today());
        }

        $jadwalKonseling = $query->orderBy('tanggal_konseling', 'desc')
                                ->orderBy('jam_mulai', 'desc')
                                ->paginate(15);

        // Data untuk filter dropdown
        $guruBKList = GuruBK::with('user')->where('is_active', true)->orderBy('nama_lengkap')->get();
        $kelasList = Siswa::distinct()->orderBy('kelas')->pluck('kelas');

        // Statistik untuk dashboard
        $statistik = [
            'total' => JadwalKonseling::count(),
            'dijadwalkan' => JadwalKonseling::where('status', 'dijadwalkan')->count(),
            'berlangsung' => JadwalKonseling::where('status', 'berlangsung')->count(),
            'selesai' => JadwalKonseling::where('status', 'selesai')->count(),
            'dibatalkan' => JadwalKonseling::where('status', 'dibatalkan')->count(),
            'hari_ini' => JadwalKonseling::whereDate('tanggal_konseling', today())->count(),
        ];

        return view('administrator.konseling.jadwal', compact(
            'jadwalKonseling', 
            'guruBKList', 
            'kelasList', 
            'statistik'
        ));
    }

    public function riwayat(Request $request)
    {
        // Query builder untuk riwayat (semua jadwal)
        $query = JadwalKonseling::with([
            'permohonanKonseling.siswa.user',
            'siswa.user',
            'guruBK.user',
            'laporanBimbingan'
        ]);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($siswaQuery) use ($search) {
                    $siswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                              ->orWhere('nisn', 'like', "%{$search}%")
                              ->orWhere('kelas', 'like', "%{$search}%");
                })->orWhereHas('guruBK', function($guruQuery) use ($search) {
                    $guruQuery->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhereHas('permohonanKonseling', function($permohonanQuery) use ($search) {
                    $permohonanQuery->where('topik_konseling', 'like', "%{$search}%");
                });
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan guru BK
        if ($request->filled('guru_bk_id')) {
            $query->where('guru_bk_id', $request->guru_bk_id);
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function($siswaQuery) use ($request) {
                $siswaQuery->where('kelas', $request->kelas);
            });
        }

        // Filter berdasarkan periode
        if ($request->filled('periode')) {
            $periode = $request->periode;
            switch ($periode) {
                case 'minggu_ini':
                    $query->whereBetween('tanggal_konseling', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal_konseling', now()->month)
                          ->whereYear('tanggal_konseling', now()->year);
                    break;
                case 'semester_ini':
                    // Asumsi semester ganjil (Juli-Desember) dan genap (Januari-Juni)
                    $currentMonth = now()->month;
                    if ($currentMonth >= 7) {
                        // Semester ganjil
                        $query->whereMonth('tanggal_konseling', '>=', 7)
                              ->whereYear('tanggal_konseling', now()->year);
                    } else {
                        // Semester genap
                        $query->whereMonth('tanggal_konseling', '<=', 6)
                              ->whereYear('tanggal_konseling', now()->year);
                    }
                    break;
                case 'tahun_ini':
                    $query->whereYear('tanggal_konseling', now()->year);
                    break;
            }
        }

        // Filter berdasarkan tanggal custom
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_konseling', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_konseling', '<=', $request->tanggal_selesai);
        }

        $riwayatKonseling = $query->orderBy('tanggal_konseling', 'desc')
                                 ->orderBy('jam_mulai', 'desc')
                                 ->paginate(15);

        // Data untuk filter dropdown
        $guruBKList = GuruBK::with('user')->where('is_active', true)->orderBy('nama_lengkap')->get();
        $kelasList = Siswa::distinct()->orderBy('kelas')->pluck('kelas');

        // Statistik riwayat
        $statistikRiwayat = [
            'total_konseling' => JadwalKonseling::count(),
            'total_selesai' => JadwalKonseling::where('status', 'selesai')->count(),
            'total_dibatalkan' => JadwalKonseling::where('status', 'dibatalkan')->count(),
            'bulan_ini' => JadwalKonseling::whereMonth('tanggal_konseling', now()->month)
                                        ->whereYear('tanggal_konseling', now()->year)
                                        ->count(),
            'dengan_laporan' => JadwalKonseling::whereHas('laporanBimbingan')->count(),
        ];

        return view('administrator.konseling.riwayat', compact(
            'riwayatKonseling', 
            'guruBKList', 
            'kelasList', 
            'statistikRiwayat'
        ));
    }

    public function show(JadwalKonseling $jadwalKonseling)
    {
        $jadwalKonseling->load([
            'permohonanKonseling.siswa.user',
            'siswa.user',
            'guruBK.user',
            'laporanBimbingan'
        ]);

        // Format response agar konsisten dengan JavaScript
        $response = $jadwalKonseling->toArray();
        $response['guru_bk'] = $jadwalKonseling->guruBK;
        $response['permohonan_konseling'] = $jadwalKonseling->permohonanKonseling;
        $response['laporan_bimbingan'] = $jadwalKonseling->laporanBimbingan;

        return response()->json($response);
    }
}