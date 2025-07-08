<?php

namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use App\Models\LaporanBimbingan;
use App\Models\JadwalKonseling;
use App\Traits\SendsWhatsAppNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanBimbinganController extends Controller
{
    use SendsWhatsAppNotifications;
    public function index()
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi. Silakan hubungi administrator.');
        }

        $jadwalPerluLaporan = JadwalKonseling::with(['permohonanKonseling.siswa.user', 'siswa.user'])
            ->where('guru_bk_id', $guruBK->id)
            ->where('status', 'selesai')
            ->whereDoesntHave('laporanBimbingan')
            ->orderBy('tanggal_konseling', 'desc')
            ->get();

        $laporanBimbingan = LaporanBimbingan::with(['jadwalKonseling.permohonanKonseling.siswa.user', 'jadwalKonseling.siswa.user'])
            ->whereHas('jadwalKonseling', function($query) use ($guruBK) {
                $query->where('guru_bk_id', $guruBK->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('guru_bk.laporan.index', compact('jadwalPerluLaporan', 'laporanBimbingan'));
    }

    public function downloadTemplate()
    {
        $templatePath = resource_path('templates/template_laporan_bimbingan.docx');
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template laporan tidak ditemukan.');
        }

        return response()->download($templatePath, 'Template_Laporan_Bimbingan.docx');
    }

    public function create(JadwalKonseling $jadwalKonseling)
    {
        $guruBK = auth()->user()->guruBK;
        
        if ($jadwalKonseling->guru_bk_id !== $guruBK->id) {
            abort(403);
        }

        if ($jadwalKonseling->status !== 'selesai') {
            return redirect()->route('guru_bk.laporan.index')
                ->with('error', 'Laporan hanya dapat dibuat untuk jadwal konseling yang sudah selesai.');
        }

        if ($jadwalKonseling->laporanBimbingan) {
            return redirect()->route('guru_bk.laporan.index')
                ->with('error', 'Laporan untuk jadwal konseling ini sudah dibuat.');
        }

        $jadwalKonseling->load(['permohonanKonseling.siswa.user', 'siswa.user']);
        
        return view('guru_bk.laporan.form', compact('jadwalKonseling'));
    }

    public function store(Request $request)
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi.');
        }

        $request->validate([
            'jadwal_konseling_id' => 'required|exists:jadwal_konseling,id',
            'dokumen_laporan' => 'required|file|mimes:doc,docx,pdf|max:10240',
        ]);

        $jadwalKonseling = JadwalKonseling::find($request->jadwal_konseling_id);
        
        if ($jadwalKonseling->guru_bk_id !== $guruBK->id) {
            abort(403);
        }

        if ($jadwalKonseling->status !== 'selesai') {
            return back()->with('error', 'Laporan hanya dapat dibuat untuk jadwal konseling yang sudah selesai.');
        }

        if ($jadwalKonseling->laporanBimbingan) {
            return back()->with('error', 'Laporan untuk jadwal konseling ini sudah dibuat.');
        }

        $file = $request->file('dokumen_laporan');
        
        $siswa = $jadwalKonseling->siswa;
        $tanggalKonseling = $jadwalKonseling->tanggal_konseling->format('Y-m-d');
        
        $namaLengkap = str_replace([' ', '.', ',', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $siswa->nama_lengkap);
        $kelas = str_replace([' ', '.', ',', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $siswa->kelas);
        $jurusan = str_replace([' ', '.', ',', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $siswa->jurusan);
        
        $fileName = $namaLengkap . '_' . $kelas . '_' . $jurusan . '_' . $tanggalKonseling . '.' . $file->getClientOriginalExtension();
        
        $filePath = $file->storeAs('laporan', $fileName);

        $laporanBimbingan = LaporanBimbingan::create([
            'jadwal_konseling_id' => $request->jadwal_konseling_id,
            'dokumen_laporan' => $filePath,
        ]);

        $this->sendWhatsAppNotification($laporanBimbingan, 'laporan_ready');

        return redirect()->route('guru_bk.laporan.index')
            ->with('success', 'Laporan bimbingan berhasil diupload dan dapat diakses oleh siswa.');
    }

    public function show(LaporanBimbingan $laporanBimbingan)
    {
        $guruBK = auth()->user()->guruBK;
        
        if ($laporanBimbingan->jadwalKonseling->guru_bk_id !== $guruBK->id) {
            abort(403);
        }

        $laporanBimbingan->load(['jadwalKonseling.permohonanKonseling.siswa.user', 'jadwalKonseling.siswa.user']);

        return response()->json($laporanBimbingan);
    }

    public function download(LaporanBimbingan $laporanBimbingan)
    {
        $guruBK = auth()->user()->guruBK;
        
        if ($laporanBimbingan->jadwalKonseling->guru_bk_id !== $guruBK->id) {
            abort(403);
        }

        if (!$laporanBimbingan->fileExists()) {
            return back()->with('error', 'File laporan tidak ditemukan.');
        }

        return response()->download($laporanBimbingan->file_path, $laporanBimbingan->download_file_name);
    }

    public function destroy(LaporanBimbingan $laporanBimbingan)
    {
        $guruBK = auth()->user()->guruBK;
        
        if ($laporanBimbingan->jadwalKonseling->guru_bk_id !== $guruBK->id) {
            abort(403);
        }

        try {
            if (Storage::exists($laporanBimbingan->dokumen_laporan)) {
                Storage::delete($laporanBimbingan->dokumen_laporan);
            }

            $laporanBimbingan->delete();
            
            return redirect()->route('guru_bk.laporan.index')
                ->with('success', 'Laporan bimbingan berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus laporan.');
        }
    }
}