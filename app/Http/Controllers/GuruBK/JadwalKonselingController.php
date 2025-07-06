<?php

namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use App\Models\JadwalKonseling;
use App\Models\PermohonanKonseling;
use Illuminate\Http\Request;

class JadwalKonselingController extends Controller
{
    public function index(Request $request)
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi. Silakan hubungi administrator.');
        }

        // Query builder dengan filter
        $query = JadwalKonseling::with([
                'permohonanKonseling.siswa.user',
                'siswa.user',
                'guruBK.user'
            ])
            ->where('guru_bk_id', $guruBK->id);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            })->orWhereHas('permohonanKonseling', function($q) use ($search) {
                $q->where('topik_konseling', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_konseling', $request->tanggal);
        }

        $jadwalKonseling = $query->orderBy('tanggal_konseling', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->paginate(15);

        // Cek apakah ada jadwal yang sedang berlangsung
        $hasActiveBimbingan = JadwalKonseling::where('guru_bk_id', $guruBK->id)
            ->where('status', 'berlangsung')
            ->exists();
        
        return view('guru_bk.jadwal.index', compact('jadwalKonseling', 'hasActiveBimbingan'));
    }

    public function create(Request $request)
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi.');
        }

        // Jika ada parameter permohonan dari URL
        $selectedPermohonanId = $request->get('permohonan');

        // Ambil permohonan yang sudah disetujui tapi belum dijadwalkan
        $permohonanDisetujui = PermohonanKonseling::with(['siswa.user'])
            ->where('status', 'disetujui')
            ->whereDoesntHave('jadwalKonseling') // Belum ada jadwal
            ->orderBy('created_at', 'asc')
            ->get();

        if ($permohonanDisetujui->isEmpty()) {
            return redirect()->route('guru_bk.jadwal.index')
                ->with('warning', 'Tidak ada permohonan konseling yang perlu dijadwalkan.');
        }

        // Jika ada parameter permohonan, pastikan valid
        if ($selectedPermohonanId) {
            $selectedPermohonan = $permohonanDisetujui->find($selectedPermohonanId);
            if (!$selectedPermohonan) {
                return redirect()->route('guru_bk.jadwal.create')
                    ->with('error', 'Permohonan tidak valid atau sudah dijadwalkan.');
            }
        }

        return view('guru_bk.jadwal.form', compact('permohonanDisetujui', 'selectedPermohonanId'));
    }

    public function store(Request $request)
    {
        $guruBK = auth()->user()->guruBK;
        
        if (!$guruBK) {
            return redirect()->route('guru_bk.dashboard')
                ->with('error', 'Profil Guru BK belum dilengkapi.');
        }

        $request->validate([
            'permohonan_konseling_id' => 'required|exists:permohonan_konseling,id',
            'tanggal_konseling' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tempat' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        // Cek apakah permohonan masih disetujui dan belum dijadwalkan
        $permohonan = PermohonanKonseling::find($request->permohonan_konseling_id);
        
        if ($permohonan->status !== 'disetujui') {
            return back()->with('error', 'Permohonan konseling tidak dalam status disetujui.');
        }

        if ($permohonan->jadwalKonseling()->exists()) {
            return back()->with('error', 'Permohonan konseling sudah dijadwalkan.');
        }

        // Cek bentrok jadwal guru BK
        $bentrokJadwal = JadwalKonseling::where('guru_bk_id', $guruBK->id)
            ->where('tanggal_konseling', $request->tanggal_konseling)
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })->exists();

        if ($bentrokJadwal) {
            return back()->with('error', 'Jadwal bentrok dengan jadwal konseling lain pada tanggal dan jam yang sama.');
        }

        JadwalKonseling::create([
            'permohonan_konseling_id' => $request->permohonan_konseling_id,
            'guru_bk_id' => $guruBK->id,
            'siswa_id' => $permohonan->siswa_id,
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tempat' => $request->tempat,
            'status' => 'dijadwalkan',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('guru_bk.jadwal.index')
            ->with('success', 'Jadwal konseling berhasil dibuat. Siswa akan mendapat notifikasi.');
    }

    public function show(JadwalKonseling $jadwalKonseling)
    {
        // Pastikan guru BK hanya bisa melihat jadwal yang ditanganinya
        if ($jadwalKonseling->guru_bk_id !== auth()->user()->guruBK->id) {
            abort(403);
        }

        $jadwalKonseling->load([
            'permohonanKonseling.siswa.user',
            'siswa.user',
            'guruBK.user'
        ]);

        return response()->json($jadwalKonseling);
    }

    public function edit(JadwalKonseling $jadwalKonseling)
    {
        // Pastikan guru BK hanya bisa edit jadwal yang ditanganinya dan belum selesai
        if ($jadwalKonseling->guru_bk_id !== auth()->user()->guruBK->id) {
            abort(403);
        }

        if (in_array($jadwalKonseling->status, ['selesai', 'dibatalkan'])) {
            return redirect()->route('guru_bk.jadwal.index')
                ->with('error', 'Jadwal konseling yang sudah selesai atau dibatalkan tidak dapat diubah.');
        }

        // Untuk edit, tidak perlu dropdown permohonan karena sudah terikat
        return view('guru_bk.jadwal.form', compact('jadwalKonseling'));
    }

    public function update(Request $request, JadwalKonseling $jadwalKonseling)
    {
        // Pastikan guru BK hanya bisa edit jadwal yang ditanganinya
        if ($jadwalKonseling->guru_bk_id !== auth()->user()->guruBK->id) {
            abort(403);
        }

        if (in_array($jadwalKonseling->status, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Jadwal konseling yang sudah selesai atau dibatalkan tidak dapat diubah.');
        }

        $request->validate([
            'tanggal_konseling' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tempat' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        // Cek bentrok jadwal (exclude jadwal yang sedang diedit)
        $bentrokJadwal = JadwalKonseling::where('guru_bk_id', auth()->user()->guruBK->id)
            ->where('id', '!=', $jadwalKonseling->id)
            ->where('tanggal_konseling', $request->tanggal_konseling)
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })->exists();

        if ($bentrokJadwal) {
            return back()->with('error', 'Jadwal bentrok dengan jadwal konseling lain pada tanggal dan jam yang sama.');
        }

        $jadwalKonseling->update($request->only([
            'tanggal_konseling', 'jam_mulai', 'jam_selesai', 'tempat', 'catatan'
        ]));

        return redirect()->route('guru_bk.jadwal.index')
            ->with('success', 'Jadwal konseling berhasil diperbarui.');
    }

    public function destroy(JadwalKonseling $jadwalKonseling)
    {
        // Pastikan guru BK hanya bisa hapus jadwal yang ditanganinya
        if ($jadwalKonseling->guru_bk_id !== auth()->user()->guruBK->id) {
            abort(403);
        }

        if ($jadwalKonseling->status === 'selesai') {
            return back()->with('error', 'Jadwal konseling yang sudah selesai tidak dapat dihapus.');
        }

        try {
            $jadwalKonseling->delete();
            
            return redirect()->route('guru_bk.jadwal.index')
                ->with('success', 'Jadwal konseling berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus jadwal.');
        }
    }

    public function updateStatus(Request $request, JadwalKonseling $jadwalKonseling)
    {
        // Pastikan guru BK hanya bisa update status jadwal yang ditanganinya
        if ($jadwalKonseling->guru_bk_id !== auth()->user()->guruBK->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:berlangsung,selesai,dibatalkan',
            'catatan' => 'nullable|string',
            'dokumentasi_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
        ]);

        // Logic status transition
        $currentStatus = $jadwalKonseling->status;
        $newStatus = $request->status;

        // Validasi transisi status yang diperbolehkan
        $allowedTransitions = [
            'dijadwalkan' => ['berlangsung', 'dibatalkan'],
            'berlangsung' => ['selesai', 'dibatalkan'],
            'selesai' => [], // Tidak bisa diubah lagi
            'dibatalkan' => [] // Tidak bisa diubah lagi
        ];

        if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
            return response()->json([
                'success' => false,
                'message' => 'Transisi status tidak diperbolehkan.'
            ], 422);
        }

        $updateData = ['status' => $newStatus];
        
        // Jika status selesai, wajib upload foto dokumentasi
        if ($newStatus === 'selesai') {
            if (!$request->hasFile('dokumentasi_foto')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto dokumentasi wajib diupload untuk menyelesaikan bimbingan.'
                ], 422);
            }
            
            // Upload foto dokumentasi
            $file = $request->file('dokumentasi_foto');
            $fileName = 'dokumentasi_' . $jadwalKonseling->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke storage/app/public/dokumentasi
            $filePath = $file->storeAs('dokumentasi', $fileName, 'public');
            $updateData['dokumentasi'] = '/storage/' . $filePath;
        }
        
        // Update catatan jika ada
        if ($request->filled('catatan')) {
            $updateData['catatan'] = $request->catatan;
        }

        $jadwalKonseling->update($updateData);

        // Jika selesai, update status permohonan menjadi selesai
        if ($newStatus === 'selesai') {
            $jadwalKonseling->permohonanKonseling->update(['status' => 'selesai']);
        }

        $statusText = match($newStatus) {
            'berlangsung' => 'dimulai',
            'selesai' => 'diselesaikan',
            'dibatalkan' => 'dibatalkan',
            default => 'diperbarui'
        };

        return response()->json([
            'success' => true,
            'message' => "Bimbingan berhasil {$statusText}."
        ]);
    }
}