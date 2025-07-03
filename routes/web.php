<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Administrator\DashboardController as AdminDashboardController;
use App\Http\Controllers\GuruBK\DashboardController as GuruBKDashboardController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $user = User::find(auth()->id());
        return redirect()->route($user->getDashboardRoute());
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware(['auth', 'role:administrator'])->prefix('administrator')->name('administrator.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/users', function() { return view('administrator.users.index'); })->name('users.index');
    Route::get('/users/create', function() { return view('administrator.users.create'); })->name('users.create');
    Route::get('/users/admin', function() { return view('administrator.users.index'); })->name('users.admin');
    Route::get('/users/guru-bk', function() { return view('administrator.users.index'); })->name('users.guru_bk');
    Route::get('/users/siswa', function() { return view('administrator.users.index'); })->name('users.siswa');
    
    Route::get('/konseling', function() { return view('administrator.konseling.index'); })->name('konseling.index');
    Route::get('/konseling/pending', function() { return view('administrator.konseling.requests'); })->name('konseling.pending');
    Route::get('/konseling/active', function() { return view('administrator.konseling.index'); })->name('konseling.active');
    
    Route::get('/laporan', function() { return view('administrator.laporan.index'); })->name('laporan.index');
    Route::get('/settings', function() { return view('administrator.settings.index'); })->name('settings.index');
});

Route::middleware(['auth', 'role:guru_bk'])->prefix('guru_bk')->name('guru_bk.')->group(function () {
    Route::get('/dashboard', [GuruBKDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/permohonan', function() { return 'Permohonan Masuk'; })->name('permohonan.index');
    Route::get('/permohonan/history', function() { return 'Riwayat Permohonan'; })->name('permohonan.history');
    
    Route::get('/jadwal/create', function() { return 'Buat Jadwal'; })->name('jadwal.create');
    Route::get('/jadwal', function() { return 'Jadwal Aktif'; })->name('jadwal.index');
    Route::get('/jadwal/history', function() { return 'Riwayat Jadwal'; })->name('jadwal.history');
    
    Route::get('/laporan/create', function() { return 'Buat Laporan'; })->name('laporan.create');
    Route::get('/laporan', function() { return 'Daftar Laporan'; })->name('laporan.index');
        
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/permohonan/create', function() { return 'Ajukan Permohonan'; })->name('permohonan.create');
    Route::get('/permohonan', function() { return 'Status Permohonan'; })->name('permohonan.index');
    
    Route::get('/jadwal', function() { return 'Jadwal Konseling'; })->name('jadwal.index');
    Route::get('/laporan', function() { return 'Laporan Bimbingan'; })->name('laporan.index');
    Route::get('/riwayat', function() { return 'Riwayat Konseling'; })->name('riwayat.index');
});