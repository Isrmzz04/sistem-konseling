<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermohonanKonseling;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PermohonanKonselingController extends Controller
{
    public function index()
    {
        $permohonanKonseling = PermohonanKonseling::with(['siswa.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('administrator.konseling.permohonan.index', compact('permohonanKonseling'));
    }

    public function create()
    {
        $siswas = Siswa::with('user')->get();
        return view('administrator.konseling.permohonan.form', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_konseling' => 'required|in:pribadi,sosial,akademik,karir,lainnya',
            'topik_konseling' => 'required|string|max:255',
            'ringkasan_masalah' => 'required|string',
        ]);

        PermohonanKonseling::create($request->all());

        return redirect()->route('administrator.konseling.permohonan.index')
            ->with('success', 'Permohonan konseling berhasil ditambahkan.');
    }

    public function show(PermohonanKonseling $permohonanKonseling)
    {
        $permohonanKonseling->load(['siswa.user']);
        return response()->json($permohonanKonseling);
    }

    public function edit(PermohonanKonseling $permohonanKonseling)
    {
        $permohonanKonseling->load(['siswa.user']);
        $siswas = Siswa::with('user')->get();
        return view('administrator.konseling.permohonan.form', compact('permohonanKonseling', 'siswas'));
    }

    public function update(Request $request, PermohonanKonseling $permohonanKonseling)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_konseling' => 'required|in:pribadi,sosial,akademik,karir,lainnya',
            'topik_konseling' => 'required|string|max:255',
            'ringkasan_masalah' => 'required|string',
            'status' => 'required|in:menunggu,disetujui,ditolak,selesai',
        ]);

        $permohonanKonseling->update($request->all());

        return redirect()->route('administrator.konseling.permohonan.index')
            ->with('success', 'Permohonan konseling berhasil diperbarui.');
    }

    public function destroy(PermohonanKonseling $permohonanKonseling)
    {
        try {
            $permohonanKonseling->delete();
            
            return redirect()->route('administrator.konseling.permohonan.index')
                ->with('success', 'Permohonan konseling berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    public function updateStatus(Request $request, PermohonanKonseling $permohonanKonseling)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string'
        ]);

        $permohonanKonseling->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status permohonan berhasil diperbarui.'
        ]);
    }
}