@extends('layouts.base')

@section('page-title', isset($jadwalKonseling) ? 'Edit Jadwal Konseling' : 'Buat Jadwal Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ isset($jadwalKonseling) ? 'Edit Jadwal Konseling' : 'Buat Jadwal Konseling' }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ isset($jadwalKonseling) ? 'Perbarui informasi jadwal konseling' : 'Buat jadwal konseling dari permohonan yang disetujui' }}
                </p>
            </div>
            <a href="{{ route('guru_bk.jadwal.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="p-6">
        <form action="{{ isset($jadwalKonseling) ? route('guru_bk.jadwal.update', $jadwalKonseling) : route('guru_bk.jadwal.store') }}" 
              method="POST" 
              class="space-y-6">
            @csrf
            @if(isset($jadwalKonseling))
                @method('PUT')
            @endif

            @if(!isset($jadwalKonseling))
            <!-- Pilih Permohonan (Hanya untuk Create) -->
            <div>
                <label for="permohonan_konseling_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Permohonan Konseling <span class="text-red-500">*</span>
                </label>
                <select name="permohonan_konseling_id" 
                        id="permohonan_konseling_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('permohonan_konseling_id') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Permohonan --</option>
                    @foreach($permohonanDisetujui as $permohonan)
                        <option value="{{ $permohonan->id }}" 
                                data-siswa="{{ $permohonan->siswa->nama_lengkap }}"
                                data-kelas="{{ $permohonan->siswa->kelas }} {{ $permohonan->siswa->jurusan }}"
                                data-topik="{{ $permohonan->topik_konseling }}"
                                data-jenis="{{ ucfirst($permohonan->jenis_konseling) }}"
                                data-ringkasan="{{ $permohonan->ringkasan_masalah }}"
                                {{ (old('permohonan_konseling_id', $selectedPermohonanId ?? null) == $permohonan->id) ? 'selected' : '' }}>
                            {{ $permohonan->siswa->nama_lengkap }} - {{ $permohonan->topik_konseling }}
                        </option>
                    @endforeach
                </select>
                @error('permohonan_konseling_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Detail Permohonan (Tampil saat pilih permohonan) -->
            <div id="detailPermohonan" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-3">Detail Permohonan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-blue-700">Siswa:</span>
                        <span id="detailSiswa" class="text-blue-900"></span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-700">Kelas:</span>
                        <span id="detailKelas" class="text-blue-900"></span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-700">Jenis:</span>
                        <span id="detailJenis" class="text-blue-900"></span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-700">Topik:</span>
                        <span id="detailTopik" class="text-blue-900"></span>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="font-medium text-blue-700">Ringkasan Masalah:</span>
                    <p id="detailRingkasan" class="text-blue-900 mt-1"></p>
                </div>
            </div>
            @else
            <!-- Info Permohonan (Untuk Edit) -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">Detail Permohonan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Siswa:</span>
                        <span class="text-gray-900">{{ $jadwalKonseling->siswa->nama_lengkap }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Kelas:</span>
                        <span class="text-gray-900">{{ $jadwalKonseling->siswa->kelas }} {{ $jadwalKonseling->siswa->jurusan }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Jenis:</span>
                        <span class="text-gray-900">{{ ucfirst($jadwalKonseling->permohonanKonseling->jenis_konseling) }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Topik:</span>
                        <span class="text-gray-900">{{ $jadwalKonseling->permohonanKonseling->topik_konseling }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Jadwal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal Konseling -->
                <div>
                    <label for="tanggal_konseling" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Konseling <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="tanggal_konseling" 
                           id="tanggal_konseling"
                           value="{{ old('tanggal_konseling', isset($jadwalKonseling) ? $jadwalKonseling->tanggal_konseling->format('Y-m-d') : '') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_konseling') border-red-500 @enderror"
                           required>
                    @error('tanggal_konseling')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat -->
                <div>
                    <label for="tempat" class="block text-sm font-medium text-gray-700 mb-2">
                        Tempat Konseling <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="tempat" 
                           id="tempat"
                           value="{{ old('tempat', isset($jadwalKonseling) ? $jadwalKonseling->tempat : '') }}"
                           placeholder="Ruang BK, Ruang Konseling, dll"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tempat') border-red-500 @enderror"
                           required>
                    @error('tempat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="jam_mulai" 
                           id="jam_mulai"
                           value="{{ old('jam_mulai', isset($jadwalKonseling) ? $jadwalKonseling->jam_mulai->format('H:i') : '') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_mulai') border-red-500 @enderror"
                           required>
                    @error('jam_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           name="jam_selesai" 
                           id="jam_selesai"
                           value="{{ old('jam_selesai', isset($jadwalKonseling) ? $jadwalKonseling->jam_selesai->format('H:i') : '') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_selesai') border-red-500 @enderror"
                           required>
                    @error('jam_selesai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Tambahan
                </label>
                <textarea name="catatan" 
                          id="catatan"
                          rows="4"
                          placeholder="Catatan atau persiapan khusus untuk konseling..."
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('catatan') border-red-500 @enderror">{{ old('catatan', isset($jadwalKonseling) ? $jadwalKonseling->catatan : '') }}</textarea>
                @error('catatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('guru_bk.jadwal.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-save mr-2"></i>{{ isset($jadwalKonseling) ? 'Perbarui' : 'Simpan' }} Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(!isset($jadwalKonseling))
    // Show detail permohonan when selected
    const permohonanSelect = document.getElementById('permohonan_konseling_id');
    const detailDiv = document.getElementById('detailPermohonan');
    
    permohonanSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            document.getElementById('detailSiswa').textContent = selectedOption.dataset.siswa;
            document.getElementById('detailKelas').textContent = selectedOption.dataset.kelas;
            document.getElementById('detailJenis').textContent = selectedOption.dataset.jenis;
            document.getElementById('detailTopik').textContent = selectedOption.dataset.topik;
            document.getElementById('detailRingkasan').textContent = selectedOption.dataset.ringkasan;
            
            detailDiv.classList.remove('hidden');
        } else {
            detailDiv.classList.add('hidden');
        }
    });

    // Trigger change event if there's an old value
    if (permohonanSelect.value) {
        permohonanSelect.dispatchEvent(new Event('change'));
    }
    @endif

    // Validate jam selesai > jam mulai
    const jamMulai = document.getElementById('jam_mulai');
    const jamSelesai = document.getElementById('jam_selesai');
    
    function validateTime() {
        if (jamMulai.value && jamSelesai.value) {
            if (jamSelesai.value <= jamMulai.value) {
                jamSelesai.setCustomValidity('Jam selesai harus lebih besar dari jam mulai');
            } else {
                jamSelesai.setCustomValidity('');
            }
        }
    }
    
    jamMulai.addEventListener('change', validateTime);
    jamSelesai.addEventListener('change', validateTime);
    
    // Auto-set jam selesai (1 jam setelah jam mulai)
    jamMulai.addEventListener('change', function() {
        if (this.value && !jamSelesai.value) {
            const [hours, minutes] = this.value.split(':');
            const newHours = parseInt(hours) + 1;
            if (newHours < 24) {
                jamSelesai.value = String(newHours).padStart(2, '0') + ':' + minutes;
            }
        }
    });
});
</script>
@endsection