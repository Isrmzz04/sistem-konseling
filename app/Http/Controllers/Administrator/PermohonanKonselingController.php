<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\PermohonanKonseling;
use App\Models\GuruBK;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PermohonanKonselingController extends Controller
{
    public function index(Request $request)
    {
        $query = PermohonanKonseling::with(['siswa.user', 'guruBK.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($siswaQuery) use ($search) {
                    $siswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                              ->orWhere('nisn', 'like', "%{$search}%")
                              ->orWhere('kelas', 'like', "%{$search}%")
                              ->orWhere('jurusan', 'like', "%{$search}%");
                })->orWhere('topik_konseling', 'like', "%{$search}%")
                  ->orWhere('ringkasan_masalah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_konseling')) {
            $query->where('jenis_konseling', $request->jenis_konseling);
        }

        if ($request->filled('guru_bk_id')) {
            $query->where('guru_bk_id', $request->guru_bk_id);
        }

        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function($siswaQuery) use ($request) {
                $siswaQuery->where('kelas', $request->kelas);
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        $permohonanKonseling = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $guruBKList = GuruBK::with('user')->where('is_active', true)->orderBy('nama_lengkap')->get();
        $kelasList = Siswa::distinct()->orderBy('kelas')->pluck('kelas');

        $statistik = [
            'total' => PermohonanKonseling::count(),
            'menunggu' => PermohonanKonseling::where('status', 'menunggu')->count(),
            'disetujui' => PermohonanKonseling::where('status', 'disetujui')->count(),
            'ditolak' => PermohonanKonseling::where('status', 'ditolak')->count(),
            'selesai' => PermohonanKonseling::where('status', 'selesai')->count(),
        ];

        return view('administrator.konseling.permohonan', compact(
            'permohonanKonseling', 
            'guruBKList', 
            'kelasList', 
            'statistik'
        ));
    }

    public function show(PermohonanKonseling $permohonanKonseling)
    {
        $permohonanKonseling->load([
            'siswa.user', 
            'guruBK.user', 
            'diprosesoleh.user',
            'jadwalKonseling.laporanBimbingan'
        ]);

        $response = $permohonanKonseling->toArray();
        $response['guru_bk'] = $permohonanKonseling->guruBK;
        $response['jadwal_konseling'] = $permohonanKonseling->jadwalKonseling;

        return response()->json($response);
    }
}