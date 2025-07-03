<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GuruBK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruBKController extends Controller
{
    public function index()
    {
        $guruBKs = GuruBK::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('administrator.users.guru_bk.index', compact('guruBKs'));
    }

    public function create()
    {
        return view('administrator.users.guru_bk.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nip' => 'required|string|unique:guru_bks',
            'nama_lengkap' => 'required|string|max:255',
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
                'role' => 'guru_bk',
            ]);

            GuruBK::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ]);

            DB::commit();
            
            return redirect()->route('administrator.users.guru_bk')
                ->with('success', 'Data Guru BK berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show(GuruBK $guruBK)
    {
        $guruBK->load('user');
        
        if (request()->ajax()) {
            return response()->json($guruBK);
        }
        
        return redirect()->route('administrator.users.guru_bk');
    }

    public function edit(GuruBK $guruBK)
    {
        $guruBK->load('user');
        return view('administrator.users.guru_bk.form', compact('guruBK'));
    }

    public function update(Request $request, GuruBK $guruBK)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $guruBK->user_id,
            'nip' => 'required|string|unique:guru_bks,nip,' . $guruBK->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'is_active' => 'required|boolean',
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
            $guruBK->user->update($userData);

            $guruBK->update([
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'is_active' => $request->is_active,
            ]);

            DB::commit();
            
            return redirect()->route('administrator.users.guru_bk')
                ->with('success', 'Data Guru BK berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy(GuruBK $guruBK)
    {
        try {
            $guruBK->user->delete();
            
            return redirect()->route('administrator.users.guru_bk')
                ->with('success', 'Data Guru BK berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}