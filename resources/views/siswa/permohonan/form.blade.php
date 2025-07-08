@extends('layouts.base')

@section('page-title', isset($permohonanKonseling) ? 'Edit Permohonan Konseling' : 'Ajukan Permohonan Konseling')

@section('main-content')
<div>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class='bx {{ isset($permohonanKonseling) ? "bx-edit" : "bx-plus-circle" }} text-blue-600'></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">
                                {{ isset($permohonanKonseling) ? 'Edit Permohonan Konseling' : 'Ajukan Permohonan Konseling' }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ isset($permohonanKonseling) ? 'Perbarui informasi permohonan konseling Anda' : 'Ajukan permohonan konseling kepada guru BK' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('siswa.permohonan.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                        <i class='bx bx-arrow-back mr-2'></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
            <div class="p-6 bg-blue-50 rounded-t-lg border-b border-blue-100">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class='bx bx-info-circle text-blue-600'></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi Permohonan Konseling</h3>
                        <div class="text-sm text-blue-700 space-y-1">
                            <div class="flex items-start space-x-2">
                                <i class='bx bx-check text-blue-600 mt-0.5 flex-shrink-0'></i>
                                <span>Pilih guru BK yang Anda inginkan untuk menangani konseling</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class='bx bx-check text-blue-600 mt-0.5 flex-shrink-0'></i>
                                <span>Isi form dengan jujur dan detail agar dapat membantu Anda dengan tepat</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class='bx bx-check text-blue-600 mt-0.5 flex-shrink-0'></i>
                                <span>Permohonan akan diproses dalam 1-2 hari kerja</span>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class='bx bx-check text-blue-600 mt-0.5 flex-shrink-0'></i>
                                <span>Semua informasi akan dijaga kerahasiaannya</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ isset($permohonanKonseling) ? route('siswa.permohonan.update', $permohonanKonseling) : route('siswa.permohonan.store') }}" 
              method="POST">
            @csrf
            @if(isset($permohonanKonseling))
                @method('PUT')
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-user-voice text-green-600'></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Pilih Guru BK</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="guru_bk_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-user-check mr-1'></i>
                                Guru BK yang Diinginkan <span class="text-red-500">*</span>
                            </label>
                            <select name="guru_bk_id" 
                                    id="guru_bk_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('guru_bk_id') border-red-500 ring-2 ring-red-200 @enderror"
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
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class='bx bx-error-circle mr-1'></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div id="infoGuruBK" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2 mb-3">
                                <i class='bx bx-user-voice text-green-600'></i>
                                <h4 class="font-medium text-green-900">Informasi Guru BK</h4>
                            </div>
                            <div class="text-sm text-green-800 space-y-1">
                                <div class="flex items-center space-x-2">
                                    <i class='bx bx-user text-green-600'></i>
                                    <span><strong>Nama:</strong> <span id="namaGuruBK"></span></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class='bx bx-id-card text-green-600'></i>
                                    <span><strong>NIP:</strong> <span id="nipGuruBK"></span></span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class='bx bx-phone text-green-600'></i>
                                    <span><strong>No. Telp:</strong> <span id="telpGuruBK"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-clipboard text-purple-600'></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Detail Konseling</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="jenis_konseling" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-category mr-1'></i>
                                Jenis Konseling <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_konseling" 
                                    id="jenis_konseling"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('jenis_konseling') border-red-500 ring-2 ring-red-200 @enderror"
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
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class='bx bx-error-circle mr-1'></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="topik_konseling" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class='bx bx-message-detail mr-1'></i>
                                Topik Konseling <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="topik_konseling" 
                                   id="topik_konseling"
                                   value="{{ old('topik_konseling', isset($permohonanKonseling) ? $permohonanKonseling->topik_konseling : '') }}"
                                   placeholder="Contoh: Kesulitan bergaul dengan teman sekelas"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('topik_konseling') border-red-500 ring-2 ring-red-200 @enderror"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Jelaskan topik konseling dalam satu kalimat singkat</p>
                            @error('topik_konseling')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class='bx bx-error-circle mr-1'></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-orange-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-edit text-orange-600'></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Ringkasan Masalah</h3>
                    </div>

                    <div>
                        <label for="ringkasan_masalah" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-file-blank mr-1'></i>
                            Ceritakan Masalah Anda <span class="text-red-500">*</span>
                        </label>
                        <textarea name="ringkasan_masalah" 
                                  id="ringkasan_masalah"
                                  rows="6"
                                  minlength="50"
                                  maxlength="1000"
                                  placeholder="Ceritakan masalah yang Anda hadapi secara detail. Jelaskan:&#10;- Apa yang terjadi?&#10;- Kapan masalah ini dimulai?&#10;- Bagaimana dampaknya terhadap Anda?&#10;- Apa yang sudah Anda coba lakukan?&#10;- Bantuan seperti apa yang Anda harapkan?"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('ringkasan_masalah') border-red-500 ring-2 ring-red-200 @enderror"
                                  required>{{ old('ringkasan_masalah', isset($permohonanKonseling) ? $permohonanKonseling->ringkasan_masalah : '') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Minimal 50 karakter, maksimal 1000 karakter</p>
                        @error('ringkasan_masalah')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class='bx bx-error-circle mr-1'></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
                <div class="p-6 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class='bx bx-shield-alt text-green-600'></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-green-800 mb-2">Kerahasiaan Terjamin</h3>
                            <p class="text-sm text-green-700">
                                Semua informasi yang Anda berikan akan dijaga kerahasiaannya dan hanya akan diketahui oleh guru BK yang Anda pilih.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('siswa.permohonan.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                            {{ isset($permohonanKonseling) ? 'Perbarui' : 'Ajukan' }} Permohonan
                        </button>
                    </div>
                </div>
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
            counter.innerHTML = `<i class='bx bx-info-circle mr-1'></i>${charCount}/${minChars} karakter minimum`;
            counter.className = 'mt-1 text-sm text-red-600 flex items-center';
        } else if (charCount > maxChars) {
            counter.innerHTML = `<i class='bx bx-error-circle mr-1'></i>${charCount}/${maxChars} karakter melebihi batas`;
            counter.className = 'mt-1 text-sm text-red-600 flex items-center';
        } else {
            counter.innerHTML = `<i class='bx bx-check-circle mr-1'></i>${charCount} karakter`;
            counter.className = 'mt-1 text-sm text-green-600 flex items-center';
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