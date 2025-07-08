<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nisn', 'LIKE', "%{$search}%")
                    ->orWhere('nama_lengkap', 'LIKE', "%{$search}%")
                    ->orWhere('kelas', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        if ($request->filled('kelas_filter')) {
            $query->where('kelas', 'LIKE', "%{$request->kelas_filter}%");
        }

        $siswas = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $jurusanList = Siswa::distinct()->pluck('jurusan')->filter()->sort();

        return view('administrator.users.siswa.index', compact('siswas', 'jurusanList'));
    }

    public function create()
    {
        return view('administrator.users.siswa.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nisn' => 'required|string|unique:siswas',
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ]);

            DB::commit();

            return redirect()->route('administrator.users.siswa')
                ->with('success', 'Data Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show(Siswa $siswa)
    {
        $siswa->load('user');

        if (request()->ajax()) {
            return response()->json($siswa);
        }

        return redirect()->route('administrator.users.siswa');
    }

    public function edit(Siswa $siswa)
    {
        $siswa->load('user');
        return view('administrator.users.siswa.form', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $siswa->user_id,
            'nisn' => 'required|string|unique:siswas,nisn,' . $siswa->id,
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|string|max:20',
            'jurusan' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6|confirmed',
            ]);
        }

        DB::beginTransaction();

        try {
            $userData = ['username' => $request->username];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $siswa->user->update($userData);

            $siswa->update([
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ]);

            DB::commit();

            return redirect()->route('administrator.users.siswa')
                ->with('success', 'Data Siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy(Siswa $siswa)
    {
        try {
            $siswa->user->delete();

            return redirect()->route('administrator.users.siswa')
                ->with('success', 'Data Siswa berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
