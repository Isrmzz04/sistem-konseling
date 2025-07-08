@extends('layouts.base')

@section('title', isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa')
@section('page-title', isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa')

@section('main-content')
<div>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class='bx {{ isset($siswa) ? "bx-edit" : "bx-user-plus" }} text-blue-600'></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ isset($siswa) ? 'Edit Data Siswa' : 'Tambah Data Siswa' }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ isset($siswa) ? 'Perbarui informasi siswa' : 'Lengkapi form untuk menambahkan siswa baru' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('administrator.users.siswa') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                        <i class='bx bx-arrow-back mr-2'></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                <i class='bx bx-error-circle mr-2 text-red-600'></i>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ isset($siswa) ? route('administrator.users.siswa.update', $siswa) : route('administrator.users.siswa.store') }}">
            @csrf
            @if(isset($siswa))
                @method('PUT')
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-8 h-8 bg-indigo-100 rounded-md flex items-center justify-center">
                                <i class='bx bx-user text-indigo-600'></i>
                            </div>
                            <h4 class="text-md font-medium text-gray-900">Data Login</h4>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class='bx bx-user mr-1'></i>
                                    Username
                                </label>
                                <input type="text" name="username" id="username" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('username') border-red-500 ring-2 ring-red-200 @enderror"
                                       value="{{ old('username', isset($siswa) ? $siswa->user->username : '') }}" 
                                       placeholder="Masukkan username"
                                       required>
                                @error('username')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class='bx bx-lock mr-1'></i>
                                    Password
                                    @if(isset($siswa))
                                        <span class="text-xs text-gray-500 font-normal">(kosongkan jika tidak ingin mengubah)</span>
                                    @endif
                                </label>
                                <input type="password" name="password" id="password" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 ring-2 ring-red-200 @enderror"
                                       placeholder="Masukkan password"
                                       {{ !isset($siswa) ? 'required' : '' }}>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class='bx bx-lock-alt mr-1'></i>
                                    Konfirmasi Password
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Konfirmasi password">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                <i class='bx bx-id-card text-green-600'></i>
                            </div>
                            <h4 class="text-md font-medium text-gray-900">Data Siswa</h4>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class='bx bx-badge mr-1'></i>
                                    NISN
                                </label>
                                <input type="text" name="nisn" id="nisn" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nisn') border-red-500 ring-2 ring-red-200 @enderror"
                                       value="{{ old('nisn', isset($siswa) ? $siswa->nisn : '') }}" 
                                       placeholder="Masukkan NISN"
                                       required>
                                @error('nisn')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class='bx bx-user-circle mr-1'></i>
                                    Nama Lengkap
                                </label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nama_lengkap') border-red-500 ring-2 ring-red-200 @enderror"
                                       value="{{ old('nama_lengkap', isset($siswa) ? $siswa->nama_lengkap : '') }}" 
                                       placeholder="Masukkan nama lengkap"
                                       required>
                                @error('nama_lengkap')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class='bx bx-book mr-1'></i>
                                        Kelas
                                    </label>
                                    <input type="text" name="kelas" id="kelas" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('kelas') border-red-500 ring-2 ring-red-200 @enderror"
                                           value="{{ old('kelas', isset($siswa) ? $siswa->kelas : '') }}" 
                                           placeholder="XII IPA 1" 
                                           required>
                                    @error('kelas')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class='bx bx-bookmark mr-1'></i>
                                        Jurusan
                                    </label>
                                    <select name="jurusan" id="jurusan" 
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('jurusan') border-red-500 ring-2 ring-red-200 @enderror" required>
                                        <option value="">Pilih Jurusan</option>
                                        <option value="IPA" {{ old('jurusan', isset($siswa) ? $siswa->jurusan : '') == 'IPA' ? 'selected' : '' }}>IPA</option>
                                        <option value="IPS" {{ old('jurusan', isset($siswa) ? $siswa->jurusan : '') == 'IPS' ? 'selected' : '' }}>IPS</option>
                                        <option value="Bahasa" {{ old('jurusan', isset($siswa) ? $siswa->jurusan : '') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                                    </select>
                                    @error('jurusan')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class='bx bx-error-circle mr-1'></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class='bx bx-male-female mr-1'></i>
                                    Jenis Kelamin
                                </label>
                                <select name="jenis_kelamin" id="jenis_kelamin" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('jenis_kelamin') border-red-500 ring-2 ring-red-200 @enderror" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', isset($siswa) ? $siswa->jenis_kelamin : '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', isset($siswa) ? $siswa->jenis_kelamin : '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class='bx bx-phone mr-1'></i>
                                    No. Telepon
                                </label>
                                <input type="text" name="no_telp" id="no_telp" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('no_telp') border-red-500 ring-2 ring-red-200 @enderror"
                                       value="{{ old('no_telp', isset($siswa) ? $siswa->no_telp : '') }}" 
                                       placeholder="Masukkan nomor telepon"
                                       required>
                                @error('no_telp')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-orange-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-info-circle text-orange-600'></i>
                        </div>
                        <h4 class="text-md font-medium text-gray-900">Informasi Tambahan</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-map-pin mr-1'></i>
                                Tempat Lahir
                            </label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tempat_lahir') border-red-500 ring-2 ring-red-200 @enderror"
                                   value="{{ old('tempat_lahir', isset($siswa) ? $siswa->tempat_lahir : '') }}" 
                                   placeholder="Masukkan tempat lahir"
                                   required>
                            @error('tempat_lahir')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class='bx bx-error-circle mr-1'></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-calendar mr-1'></i>
                                Tanggal Lahir
                            </label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tanggal_lahir') border-red-500 ring-2 ring-red-200 @enderror"
                                   value="{{ old('tanggal_lahir', isset($siswa) && $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}" required>
                            @error('tanggal_lahir')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class='bx bx-error-circle mr-1'></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-home mr-1'></i>
                            Alamat
                        </label>
                        <textarea name="alamat" id="alamat" rows="4" 
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('alamat') border-red-500 ring-2 ring-red-200 @enderror"
                                  placeholder="Masukkan alamat lengkap"
                                  required>{{ old('alamat', isset($siswa) ? $siswa->alamat : '') }}</textarea>
                        @error('alamat')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('administrator.users.siswa') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                            {{ isset($siswa) ? 'Update Data' : 'Simpan Data' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection