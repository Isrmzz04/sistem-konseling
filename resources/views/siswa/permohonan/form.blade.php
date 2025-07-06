@extends('layouts.base')

@section('page-title', isset($permohonanKonseling) ? 'Edit Permohonan Konseling' : 'Ajukan Permohonan Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ isset($permohonanKonseling) ? 'Edit Permohonan Konseling' : 'Ajukan Permohonan Konseling' }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ isset($permohonanKonseling) ? 'Perbarui informasi permohonan konseling Anda' : 'Ajukan permohonan konseling kepada guru BK' }}
                </p>
            </div>
            <a href="{{ route('siswa.permohonan.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="p-6 border-b border-gray-200 bg-blue-50">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi Permohonan Konseling</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Pilih guru BK yang Anda inginkan untuk menangani konseling</li>
                        <li>Isi form dengan jujur dan detail agar dapat membantu Anda dengan tepat</li>
                        <li>Permohonan akan diproses dalam 1-2 hari kerja</li>
                        <li>Semua informasi akan dijaga kerahasiaannya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        <form action="{{ isset($permohonanKonseling) ? route('siswa.permohonan.update', $permohonanKonseling) : route('siswa.permohonan.store') }}" 
              method="POST" 
              class="space-y-6">
            @csrf
            @if(isset($permohonanKonseling))
                @method('PUT')
            @endif

            <!-- Pilih Guru BK -->
            <div>
                <label for="guru_bk_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Guru BK <span class="text-red-500">*</span>
                </label>
                <select name="guru_bk_id" 
                        id="guru_bk_id"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('guru_bk_id') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Guru BK --</option>
                    @foreach($guruBKList as $guruBK)
                        <option value="{{ $guruBK->id }}" 
                                data-nip="{{ $guruBK->nip }}"
                                data-telp="{{ $guruBK->no_telp }}"
                                {{ old('guru_bk_id', isset($permohonanKonseling) ? $permohonanKonseling->guru_bk_id : '') == $guruBK->id ? 'selected' : '' }}>
                            {{ $guruBK->nama_lengkap }}
                            @if($guruBK->nip) - NIP: {{ $guruBK->nip }} @endif
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Pilih guru BK yang Anda inginkan untuk menangani konseling Anda</p>
                @error('guru_bk_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Guru BK yang dipilih -->
            <div id="infoGuruBK" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-medium text-green-900 mb-2">Informasi Guru BK</h4>
                <div class="text-sm text-green-800">
                    <div><strong>Nama:</strong> <span id="namaGuruBK"></span></div>
                    <div><strong>NIP:</strong> <span id="nipGuruBK"></span></div>
                    <div><strong>No. Telp:</strong> <span id="telpGuruBK"></span></div>
                </div>
            </div>

            <!-- Jenis Konseling -->
            <div>
                <label for="jenis_konseling" class="block text-sm font-medium text-gray-700 mb-2">
                    Jenis Konseling <span class="text-red-500">*</span>
                </label>
                <select name="jenis_konseling" 
                        id="jenis_konseling"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_konseling') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Jenis Konseling --</option>
                    <option value="pribadi" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'pribadi' ? 'selected' : '' }}>
                        Pribadi - Masalah personal, emosi, atau kepribadian
                    </option>
                    <option value="sosial" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'sosial' ? 'selected' : '' }}>
                        Sosial - Hubungan dengan teman, keluarga, atau lingkungan
                    </option>
                    <option value="akademik" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'akademik' ? 'selected' : '' }}>
                        Akademik - Masalah belajar, prestasi, atau sekolah
                    </option>
                    <option value="karir" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'karir' ? 'selected' : '' }}>
                        Karir - Pilihan jurusan, cita-cita, atau masa depan
                    </option>
                    <option value="lainnya" {{ old('jenis_konseling', isset($permohonanKonseling) ? $permohonanKonseling->jenis_konseling : '') == 'lainnya' ? 'selected' : '' }}>
                        Lainnya - Masalah lain yang memerlukan konseling
                    </option>
                </select>
                @error('jenis_konseling')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Topik Konseling -->
            <div>
                <label for="topik_konseling" class="block text-sm font-medium text-gray-700 mb-2">
                    Topik Konseling <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="topik_konseling" 
                       id="topik_konseling"
                       value="{{ old('topik_konseling', isset($permohonanKonseling) ? $permohonanKonseling->topik_konseling : '') }}"
                       placeholder="Contoh: Kesulitan bergaul dengan teman sekelas"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('topik_konseling') border-red-500 @enderror"
                       required>
                <p class="mt-1 text-sm text-gray-500">Jelaskan topik konseling dalam satu kalimat singkat</p>
                @error('topik_konseling')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ringkasan Masalah -->
            <div>
                <label for="ringkasan_masalah" class="block text-sm font-medium text-gray-700 mb-2">
                    Ringkasan Masalah <span class="text-red-500">*</span>
                </label>
                <textarea name="ringkasan_masalah" 
                          id="ringkasan_masalah"
                          rows="6"
                          minlength="50"
                          maxlength="1000"
                          placeholder="Ceritakan masalah yang Anda hadapi secara detail. Jelaskan:&#10;- Apa yang terjadi?&#10;- Kapan masalah ini dimulai?&#10;- Bagaimana dampaknya terhadap Anda?&#10;- Apa yang sudah Anda coba lakukan?&#10;- Bantuan seperti apa yang Anda harapkan?"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ringkasan_masalah') border-red-500 @enderror"
                          required>{{ old('ringkasan_masalah', isset($permohonanKonseling) ? $permohonanKonseling->ringkasan_masalah : '') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Minimal 50 karakter, maksimal 1000 karakter.</p>
                @error('ringkasan_masalah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Privacy Notice -->
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-shield-alt text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Kerahasiaan Terjamin</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>Semua informasi yang Anda berikan akan dijaga kerahasiaannya dan hanya akan diketahui oleh guru BK yang Anda pilih.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('siswa.permohonan.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>{{ isset($permohonanKonseling) ? 'Perbarui' : 'Ajukan' }} Permohonan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const guruBKSelect = document.getElementById('guru_bk_id');
    const infoDiv = document.getElementById('infoGuruBK');
    
    guruBKSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('namaGuruBK').textContent = selectedOption.textContent.split(' - ')[0];
            document.getElementById('nipGuruBK').textContent = selectedOption.dataset.nip || '-';
            document.getElementById('telpGuruBK').textContent = selectedOption.dataset.telp || '-';
            infoDiv.classList.remove('hidden');
        } else {
            infoDiv.classList.add('hidden');
        }
    });

    if (guruBKSelect.value) {
        guruBKSelect.dispatchEvent(new Event('change'));
    }

    const textarea = document.getElementById('ringkasan_masalah');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';

        const charCount = this.value.length;
        const minChars = 50;
        const maxChars = 1000;

        let counter = document.getElementById('char-counter');
        if (!counter) {
            counter = document.createElement('p');
            counter.id = 'char-counter';
            counter.className = 'mt-1 text-sm';
            this.parentNode.appendChild(counter);
        }

        if (charCount < minChars) {
            counter.textContent = `${charCount}/${minChars} karakter minimum`;
            counter.className = 'mt-1 text-sm text-red-600';
        } else if (charCount > maxChars) {
            counter.textContent = `${charCount}/${maxChars} karakter melebihi batas`;
            counter.className = 'mt-1 text-sm text-red-600';
        } else {
            counter.textContent = `${charCount} karakter`;
            counter.className = 'mt-1 text-sm text-green-600';
        }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        const value = textarea.value.trim();
        if (value.length < 50) {
            e.preventDefault();
            alert('Mohon jelaskan masalah Anda lebih detail (minimal 50 karakter)');
            textarea.focus();
            return;
        }
        if (value.length > 1000) {
            e.preventDefault();
            alert('Ringkasan masalah terlalu panjang (maksimal 1000 karakter)');
            textarea.focus();
            return;
        }
        if (!confirm('Apakah Anda yakin ingin {{ isset($permohonanKonseling) ? "memperbarui" : "mengajukan" }} permohonan konseling ini?')) {
            e.preventDefault();
        }
    });

    textarea.dispatchEvent(new Event('input'));
});
</script>
@endsection
