<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Administrator\DashboardController as AdminDashboardController;
use App\Http\Controllers\Administrator\GuruBKController;
use App\Http\Controllers\Administrator\SiswaController;

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

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/guru-bk', [GuruBKController::class, 'index'])->name('guru_bk');
        Route::get('/guru-bk/create', [GuruBKController::class, 'create'])->name('guru_bk.create');
        Route::post('/guru-bk', [GuruBKController::class, 'store'])->name('guru_bk.store');
        Route::get('/guru-bk/{guruBK}', [GuruBKController::class, 'show'])->name('guru_bk.show');
        Route::get('/guru-bk/{guruBK}/edit', [GuruBKController::class, 'edit'])->name('guru_bk.edit');
        Route::put('/guru-bk/{guruBK}', [GuruBKController::class, 'update'])->name('guru_bk.update');
        Route::delete('/guru-bk/{guruBK}', [GuruBKController::class, 'destroy'])->name('guru_bk.destroy');

        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    });

    Route::prefix('konseling')->name('konseling.')->group(function () {
        Route::get('/permohonan', [App\Http\Controllers\Administrator\PermohonanKonselingController::class, 'index'])->name('permohonan');
        Route::get('/permohonan/{permohonanKonseling}', [App\Http\Controllers\Administrator\PermohonanKonselingController::class, 'show'])->name('permohonan.show');

        Route::get('/jadwal', [App\Http\Controllers\Administrator\JadwalKonselingController::class, 'index'])->name('jadwal');
        Route::get('/jadwal/{jadwalKonseling}', [App\Http\Controllers\Administrator\JadwalKonselingController::class, 'show'])->name('jadwal.show');

        Route::get('/riwayat', [App\Http\Controllers\Administrator\JadwalKonselingController::class, 'riwayat'])->name('riwayat');
    });

    // Update: Laporan routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Administrator\LaporanBimbinganController::class, 'index'])->name('index');
        Route::get('/{laporanBimbingan}', [App\Http\Controllers\Administrator\LaporanBimbinganController::class, 'show'])->name('show');
        Route::get('/{laporanBimbingan}/download', [App\Http\Controllers\Administrator\LaporanBimbinganController::class, 'download'])->name('download');
    });
});

Route::middleware(['auth', 'role:guru_bk'])->prefix('guru_bk')->name('guru_bk.')->group(function () {
    Route::get('/dashboard', [GuruBKDashboardController::class, 'index'])->name('dashboard');

    // Permohonan routes
    Route::prefix('permohonan')->name('permohonan.')->group(function () {
        Route::get('/', [App\Http\Controllers\GuruBK\PermohonanKonselingController::class, 'index'])->name('index');
        Route::get('/history', [App\Http\Controllers\GuruBK\PermohonanKonselingController::class, 'history'])->name('history');
        Route::patch('/{permohonanKonseling}/status', [App\Http\Controllers\GuruBK\PermohonanKonselingController::class, 'updateStatus'])->name('update-status');
    });

    // Jadwal routes - Update ini (menu Jadwal adalah single, bukan dropdown)
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [App\Http\Controllers\GuruBK\JadwalKonselingController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\GuruBK\JadwalKonselingController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\GuruBK\JadwalKonselingController::class, 'store'])->name('store');
        Route::get('/{jadwalKonseling}', [App\Http\Controllers\GuruBK\JadwalKonselingController::class, 'show'])->name('show');
        Route::get('/{jadwalKonseling}/edit', [App\Http\Controllers\GuruBK\JadwalKonselingController::class, 'edit'])->name('edit');
        Route::put('/{jadwalKonseling}', [App\Http\Controllers\GuruBK\JadwalKonselingController::class, 'update'])->name('update');
        Route::delete('/{jadwalKonseling}', [App\Http\Controllers\GuruBK\JadwalKonselingController::class, 'destroy'])->name('destroy');
        Route::patch('/{jadwalKonseling}/status', [App\Http\Controllers\GuruBK\JadwalKonselingController::class, 'updateStatus'])->name('update-status');
    });

    // Laporan routes (belum diubah, masih single route)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [App\Http\Controllers\GuruBK\LaporanBimbinganController::class, 'index'])->name('index');
        Route::get('/template', [App\Http\Controllers\GuruBK\LaporanBimbinganController::class, 'downloadTemplate'])->name('download-template');
        Route::get('/create/{jadwalKonseling}', [App\Http\Controllers\GuruBK\LaporanBimbinganController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\GuruBK\LaporanBimbinganController::class, 'store'])->name('store');
        Route::get('/{laporanBimbingan}', [App\Http\Controllers\GuruBK\LaporanBimbinganController::class, 'show'])->name('show');
        Route::get('/{laporanBimbingan}/download', [App\Http\Controllers\GuruBK\LaporanBimbinganController::class, 'download'])->name('download');
        Route::delete('/{laporanBimbingan}', [App\Http\Controllers\GuruBK\LaporanBimbinganController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('permohonan')->name('permohonan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Siswa\PermohonanKonselingController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Siswa\PermohonanKonselingController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Siswa\PermohonanKonselingController::class, 'store'])->name('store');
        Route::get('/{permohonanKonseling}', [App\Http\Controllers\Siswa\PermohonanKonselingController::class, 'show'])->name('show');
        Route::get('/{permohonanKonseling}/edit', [App\Http\Controllers\Siswa\PermohonanKonselingController::class, 'edit'])->name('edit');
        Route::put('/{permohonanKonseling}', [App\Http\Controllers\Siswa\PermohonanKonselingController::class, 'update'])->name('update');
        Route::delete('/{permohonanKonseling}', [App\Http\Controllers\Siswa\PermohonanKonselingController::class, 'destroy'])->name('destroy');
    });

    Route::get('/jadwal', [App\Http\Controllers\Siswa\JadwalKonselingController::class, 'index'])->name('jadwal.index');

    Route::get('/riwayat', [App\Http\Controllers\Siswa\JadwalKonselingController::class, 'riwayat'])->name('riwayat.index');

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Siswa\LaporanBimbinganController::class, 'index'])->name('index');
        Route::get('/{laporanBimbingan}/download', [App\Http\Controllers\Siswa\LaporanBimbinganController::class, 'download'])->name('download');
    });
});
