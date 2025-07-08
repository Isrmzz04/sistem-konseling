<?php

namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use App\Models\PermohonanKonseling;
use Illuminate\Http\Request;

class PermohonanKonselingController extends Controller
{
    public function index(Request $request)
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi. Silakan hubungi administrator.');
        }

        $query = PermohonanKonseling::with(['siswa.user', 'jadwalKonseling'])
            ->where('guru_bk_id', $guruBK->id)
            ->whereIn('status', ['menunggu', 'disetujui']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($siswaQuery) use ($search) {
                    $siswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                              ->orWhere('nisn', 'like', "%{$search}%");
                })
                ->orWhere('topik_konseling', 'like', "%{$search}%")
                ->orWhere('ringkasan_masalah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['menunggu', 'disetujui'])) {
            $query->where('status', $request->status);
        }

        $permohonanKonseling = $query
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui')")
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->appends($request->query());
        
        return view('guru_bk.permohonan.index', compact('permohonanKonseling'));
    }

    public function history(Request $request)
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi.');
        }

        $query = PermohonanKonseling::with(['siswa.user', 'jadwalKonseling'])
            ->where('guru_bk_id', $guruBK->id)
            ->whereIn('status', ['ditolak', 'selesai']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($siswaQuery) use ($search) {
                    $siswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                              ->orWhere('nisn', 'like', "%{$search}%");
                })
                ->orWhere('topik_konseling', 'like', "%{$search}%")
                ->orWhere('ringkasan_masalah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['ditolak', 'selesai'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bulan')) {
            $bulan = $request->bulan;
            $query->whereYear('created_at', substr($bulan, 0, 4))
                  ->whereMonth('created_at', substr($bulan, 5, 2));
        }

        $permohonanKonseling = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());
        
        return view('guru_bk.permohonan.history', compact('permohonanKonseling'));
    }

    public function updateStatus(Request $request, PermohonanKonseling $permohonanKonseling)
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return response()->json([
                'success' => false,
                'message' => 'Akses tidak diizinkan.'
            ], 403);
        }

        if ($permohonanKonseling->guru_bk_id !== $guruBK->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk memproses permohonan ini.'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string'
        ]);

        if ($permohonanKonseling->status !== 'menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Permohonan sudah diproses sebelumnya.'
            ], 422);
        }

        $permohonanKonseling->update([
            'status' => $request->status,
            'catatan_guru_bk' => $request->catatan,
            'diproses_oleh' => $guruBK->id,
            'diproses_at' => now()
        ]);

        $statusText = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';

        return response()->json([
            'success' => true,
            'message' => "Permohonan konseling berhasil {$statusText}."
        ]);
    }
}