<?php

namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use App\Models\PermohonanKonseling;
use Illuminate\Http\Request;

class PermohonanKonselingController extends Controller
{
    public function index()
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi. Silakan hubungi administrator.');
        }

        // Ambil permohonan yang ditujukan kepada guru BK yang login dan perlu ditangani
        $permohonanKonseling = PermohonanKonseling::with(['siswa.user'])
            ->where('guru_bk_id', $guruBK->id) // Filter berdasarkan guru BK yang login
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui')")
            ->orderBy('created_at', 'asc')
            ->paginate(15);
        
        return view('guru_bk.permohonan.index', compact('permohonanKonseling'));
    }

    public function history()
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi.');
        }

        // Ambil riwayat permohonan yang ditujukan kepada guru BK yang login
        $permohonanKonseling = PermohonanKonseling::with(['siswa.user', 'jadwalKonseling'])
            ->where('guru_bk_id', $guruBK->id) // Filter berdasarkan guru BK yang login
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
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

        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string'
        ]);

        // Hanya bisa mengubah status jika masih menunggu
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