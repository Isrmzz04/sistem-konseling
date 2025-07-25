<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PermohonanKonseling;
use App\Models\GuruBK;
use App\Traits\SendsWhatsAppNotifications;
use Illuminate\Http\Request;

class PermohonanKonselingController extends Controller
{
    use SendsWhatsAppNotifications;
    public function index()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa belum dilengkapi. Silakan hubungi administrator.');
        }

        $permohonanKonseling = PermohonanKonseling::with(['guruBK'])
            ->where('siswa_id', $siswa->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $hasActivePermohonan = PermohonanKonseling::where('siswa_id', $siswa->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();
        
        return view('siswa.permohonan.index', compact('permohonanKonseling', 'hasActivePermohonan'));
    }

    public function create()
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa belum dilengkapi. Silakan hubungi administrator.');
        }

        $hasActivePermohonan = PermohonanKonseling::where('siswa_id', $siswa->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($hasActivePermohonan) {
            return redirect()->route('siswa.permohonan.index')
                ->with('error', 'Anda sudah memiliki permohonan konseling yang sedang diproses. Tunggu hingga permohonan selesai sebelum mengajukan yang baru.');
        }

        $guruBKList = GuruBK::with('user')
            ->where('is_active', true)
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('siswa.permohonan.form', compact('guruBKList'));
    }

    public function store(Request $request)
    {
        $siswa = auth()->user()->siswa;
        
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Profil siswa belum dilengkapi.');
        }

        $hasActivePermohonan = PermohonanKonseling::where('siswa_id', $siswa->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($hasActivePermohonan) {
            return redirect()->route('siswa.permohonan.index')
                ->with('error', 'Anda sudah memiliki permohonan konseling yang sedang diproses.');
        }

        $request->validate([
            'guru_bk_id' => 'required|exists:guru_bks,id',
            'jenis_konseling' => 'required|in:pribadi,sosial,akademik,karir,lainnya',
            'topik_konseling' => 'required|string|max:255',
            'ringkasan_masalah' => 'required|string|min:50',
        ]);

        $permohonanKonseling = PermohonanKonseling::create([
            'siswa_id' => $siswa->id,
            'guru_bk_id' => $request->guru_bk_id,
            'jenis_konseling' => $request->jenis_konseling,
            'topik_konseling' => $request->topik_konseling,
            'ringkasan_masalah' => $request->ringkasan_masalah,
            'status' => 'menunggu'
        ]);

        $this->sendWhatsAppNotification($permohonanKonseling, 'permohonan_masuk');

        return redirect()->route('siswa.permohonan.index')
            ->with('success', 'Permohonan konseling berhasil diajukan. Silakan tunggu konfirmasi dari guru BK.');
    }

    public function show(PermohonanKonseling $permohonanKonseling)
    {
        if ($permohonanKonseling->siswa_id !== auth()->user()->siswa->id) {
            abort(403);
        }

        $permohonanKonseling->load(['guruBK']);
        return response()->json($permohonanKonseling);
    }

    public function edit(PermohonanKonseling $permohonanKonseling)
    {
        if ($permohonanKonseling->siswa_id !== auth()->user()->siswa->id || 
            $permohonanKonseling->status !== 'menunggu') {
            abort(403);
        }

        $guruBKList = GuruBK::with('user')
            ->where('is_active', true)
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('siswa.permohonan.form', compact('permohonanKonseling', 'guruBKList'));
    }

    public function update(Request $request, PermohonanKonseling $permohonanKonseling)
    {
        if ($permohonanKonseling->siswa_id !== auth()->user()->siswa->id || 
            $permohonanKonseling->status !== 'menunggu') {
            abort(403);
        }

        $request->validate([
            'guru_bk_id' => 'required|exists:guru_bks,id',
            'jenis_konseling' => 'required|in:pribadi,sosial,akademik,karir,lainnya',
            'topik_konseling' => 'required|string|max:255',
            'ringkasan_masalah' => 'required|string|min:50',
        ]);

        $permohonanKonseling->update($request->only([
            'guru_bk_id', 'jenis_konseling', 'topik_konseling', 'ringkasan_masalah'
        ]));

        return redirect()->route('siswa.permohonan.index')
            ->with('success', 'Permohonan konseling berhasil diperbarui.');
    }

    public function destroy(PermohonanKonseling $permohonanKonseling)
    {
        if ($permohonanKonseling->siswa_id !== auth()->user()->siswa->id || 
            $permohonanKonseling->status !== 'menunggu') {
            abort(403);
        }

        try {
            $permohonanKonseling->delete();
            
            return redirect()->route('siswa.permohonan.index')
                ->with('success', 'Permohonan konseling berhasil dibatalkan.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membatalkan permohonan.');
        }
    }
}