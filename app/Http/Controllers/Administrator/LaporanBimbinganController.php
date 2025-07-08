<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\LaporanBimbingan;
use App\Models\GuruBK;
use App\Models\Siswa;
use Illuminate\Http\Request;

class LaporanBimbinganController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanBimbingan::with([
            'jadwalKonseling.permohonanKonseling.siswa.user',
            'jadwalKonseling.siswa.user',
            'jadwalKonseling.guruBK.user'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('jadwalKonseling', function($jadwalQuery) use ($search) {
                $jadwalQuery->whereHas('siswa', function($siswaQuery) use ($search) {
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

        if ($request->filled('guru_bk_id')) {
            $query->whereHas('jadwalKonseling', function($jadwalQuery) use ($request) {
                $jadwalQuery->where('guru_bk_id', $request->guru_bk_id);
            });
        }

        if ($request->filled('kelas')) {
            $query->whereHas('jadwalKonseling.siswa', function($siswaQuery) use ($request) {
                $siswaQuery->where('kelas', $request->kelas);
            });
        }

        if ($request->filled('jenis_konseling')) {
            $query->whereHas('jadwalKonseling.permohonanKonseling', function($permohonanQuery) use ($request) {
                $permohonanQuery->where('jenis_konseling', $request->jenis_konseling);
            });
        }

        if ($request->filled('periode')) {
            $periode = $request->periode;
            switch ($periode) {
                case 'minggu_ini':
                    $query->whereHas('jadwalKonseling', function($jadwalQuery) {
                        $jadwalQuery->whereBetween('tanggal_konseling', [
                            now()->startOfWeek(),
                            now()->endOfWeek()
                        ]);
                    });
                    break;
                case 'bulan_ini':
                    $query->whereHas('jadwalKonseling', function($jadwalQuery) {
                        $jadwalQuery->whereMonth('tanggal_konseling', now()->month)
                                   ->whereYear('tanggal_konseling', now()->year);
                    });
                    break;
                case 'semester_ini':
                    $currentMonth = now()->month;
                    $query->whereHas('jadwalKonseling', function($jadwalQuery) use ($currentMonth) {
                        if ($currentMonth >= 7) {
                            $jadwalQuery->whereMonth('tanggal_konseling', '>=', 7)
                                       ->whereYear('tanggal_konseling', now()->year);
                        } else {
                            $jadwalQuery->whereMonth('tanggal_konseling', '<=', 6)
                                       ->whereYear('tanggal_konseling', now()->year);
                        }
                    });
                    break;
                case 'tahun_ini':
                    $query->whereHas('jadwalKonseling', function($jadwalQuery) {
                        $jadwalQuery->whereYear('tanggal_konseling', now()->year);
                    });
                    break;
            }
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereHas('jadwalKonseling', function($jadwalQuery) use ($request) {
                $jadwalQuery->whereDate('tanggal_konseling', '>=', $request->tanggal_mulai);
            });
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereHas('jadwalKonseling', function($jadwalQuery) use ($request) {
                $jadwalQuery->whereDate('tanggal_konseling', '<=', $request->tanggal_selesai);
            });
        }

        $laporanBimbingan = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $guruBKList = GuruBK::with('user')->where('is_active', true)->orderBy('nama_lengkap')->get();
        $kelasList = Siswa::distinct()->orderBy('kelas')->pluck('kelas');

        $statistik = [
            'total_laporan' => LaporanBimbingan::count(),
            'minggu_ini' => LaporanBimbingan::whereHas('jadwalKonseling', function($query) {
                $query->whereBetween('tanggal_konseling', [now()->startOfWeek(), now()->endOfWeek()]);
            })->count(),
            'bulan_ini' => LaporanBimbingan::whereHas('jadwalKonseling', function($query) {
                $query->whereMonth('tanggal_konseling', now()->month)
                      ->whereYear('tanggal_konseling', now()->year);
            })->count(),
            'tahun_ini' => LaporanBimbingan::whereHas('jadwalKonseling', function($query) {
                $query->whereYear('tanggal_konseling', now()->year);
            })->count(),
            'per_jenis' => $this->getStatistikPerJenis(),
            'per_guru_bk' => $this->getStatistikPerGuruBK(),
        ];

        return view('administrator.laporan.index', compact(
            'laporanBimbingan', 
            'guruBKList', 
            'kelasList', 
            'statistik'
        ));
    }

    private function getStatistikPerJenis()
    {
        return LaporanBimbingan::whereHas('jadwalKonseling.permohonanKonseling')
            ->with('jadwalKonseling.permohonanKonseling')
            ->get()
            ->groupBy('jadwalKonseling.permohonanKonseling.jenis_konseling')
            ->map(function ($items) {
                return $items->count();
            })
            ->sortDesc()
            ->take(5);
    }

    private function getStatistikPerGuruBK()
    {
        return LaporanBimbingan::whereHas('jadwalKonseling.guruBK')
            ->with('jadwalKonseling.guruBK')
            ->get()
            ->groupBy('jadwalKonseling.guruBK.nama_lengkap')
            ->map(function ($items) {
                return $items->count();
            })
            ->sortDesc()
            ->take(5);
    }

    public function show(LaporanBimbingan $laporanBimbingan)
    {
        $laporanBimbingan->load([
            'jadwalKonseling.permohonanKonseling.siswa.user',
            'jadwalKonseling.siswa.user',
            'jadwalKonseling.guruBK.user'
        ]);

        $response = $laporanBimbingan->toArray();
        $response['jadwal_konseling'] = $laporanBimbingan->jadwalKonseling;
        
        return response()->json($response);
    }

    public function download(LaporanBimbingan $laporanBimbingan)
    {
        if (!$laporanBimbingan->fileExists()) {
            return back()->with('error', 'File laporan tidak ditemukan.');
        }

        return response()->download($laporanBimbingan->file_path, $laporanBimbingan->download_file_name);
    }
}