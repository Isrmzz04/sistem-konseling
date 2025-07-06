@extends('layouts.base')

@section('title', isset($permohonanKonseling) ? 'Edit Permohonan Konseling' : 'Tambah Permohonan Konseling')
@section('page-title', isset($permohonanKonseling) ? 'Edit Permohonan Konseling' : 'Tambah Permohonan Konseling')

@section('main-content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ isset($permohonanKonseling) ? 'Edit Permohonan Konseling' : 'Tambah Permohonan Konseling' }}
            </h3>
            <a href="{{ route('administrator.konseling.permohonan.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ isset($permohonanKonseling) ? route('administrator.konseling.permohonan.update', $permohonanKonseling) : route('administrator.konseling.permohonan.store') }}">
            @csrf
            @if(isset($permohonanKonseling))
                @method('PUT')
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Siswa -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2">Data Siswa</h4>
                    
                    <div>
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                        <select name="siswa_id" id="siswa_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('siswa_id') border-red-500 @enderror" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}" 
                                        {{ old('siswa_id', isset($permohonanKonseling) ? $permohonanKonseling->siswa_id : '') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama_lengkap }} - {{ $siswa->kelas }} ({{ $siswa->nisn }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(isset($permohonanKonseling))
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror" required>
                            <option value="menunggu" {{ old('status', $permohonanKonseling->status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ old('status', $permohonanKonseling->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ old('status', $permohonanKonseling->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="selesai" {{ old('status', $permohonanKonseling->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif
                </div>

                <!-- Data Konseling -->
                <div class="space-y-4">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2">Data Konseling</h4>
                    
                    <div>
                        <label for="jenis_konseling" class="block text-sm font-medium text-gray-700">Jenis Konseling</label>
                        <select name="jenis_konseling" id="jenis_konseling" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('jenis_konseling') border-red-500 @enderror" required>
                            <option value="">Pilih Jenis Konseling</option>
                            <option value="pribadi" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                            <option value="sosial" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                            <option value="akademik" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                            <option value="karir" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'karir' ? 'selected' : '' }}>Karir</option>
                            <option value="lainnya" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_konseling')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="topik_konseling" class="block text-sm font-medium text-gray-700">Topik Konseling</label>
                        <input type="text" name="topik_konseling" id="topik_konseling" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('topik_konseling') border-red-500 @enderror"
                               value="{{ old('topik_konseling', isset($permohonanKonseling) ? $permohonanKonseling->topik_konseling : '') }}" 
                               placeholder="Contoh: Masalah dengan teman sekelas" required>
                        @error('topik_konseling')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label for="ringkasan_masalah" class="block text-sm font-medium text-gray-700">Ringkasan Masalah</label>
                <textarea name="ringkasan_masalah" id="ringkasan_masalah" rows="5" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('ringkasan_masalah') border-red-500 @enderror"
                          placeholder="Jelaskan secara detail masalah yang dihadapi siswa..." required>{{ old('ringkasan_masalah', isset($permohonanKonseling) ? $permohonanKonseling->ringkasan_masalah : '') }}</textarea>
                @error('ringkasan_masalah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('administrator.konseling.permohonan.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    {{ isset($permohonanKonseling) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection