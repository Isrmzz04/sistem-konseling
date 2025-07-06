<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LaporanBimbingan;

class LaporanBimbinganController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa belum dilengkapi. Silakan hubungi administrator.');
        }

        // Ambil laporan bimbingan untuk siswa yang login
        $laporanBimbingan = LaporanBimbingan::with([
                'jadwalKonseling.permohonanKonseling', 
                'jadwalKonseling.guruBK'
            ])
            ->whereHas('jadwalKonseling', function($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('siswa.laporan.index', compact('laporanBimbingan'));
    }

    public function download(LaporanBimbingan $laporanBimbingan)
    {
        $siswa = auth()->user()->siswa;
        
        // Pastikan siswa hanya bisa download laporan miliknya
        if ($laporanBimbingan->jadwalKonseling->siswa_id !== $siswa->id) {
            abort(403);
        }

        if (!$laporanBimbingan->fileExists()) {
            return back()->with('error', 'File laporan tidak ditemukan.');
        }

        return response()->download($laporanBimbingan->file_path, $laporanBimbingan->download_file_name);
    }
}