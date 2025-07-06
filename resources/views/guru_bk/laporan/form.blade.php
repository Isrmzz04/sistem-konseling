@extends('layouts.base')

@section('page-title', 'Upload Laporan Bimbingan')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Upload Laporan Bimbingan</h2>
                <p class="mt-1 text-sm text-gray-600">Upload file laporan hasil bimbingan konseling</p>
            </div>
            <a href="{{ route('guru_bk.laporan.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Info Konseling -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <h3 class="text-sm font-medium text-gray-900 mb-3">Detail Konseling</h3>
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
                <span class="font-medium text-gray-700">Tanggal:</span>
                <span class="text-gray-900">{{ $jadwalKonseling->tanggal_konseling->format('d F Y') }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Waktu:</span>
                <span class="text-gray-900">{{ $jadwalKonseling->jam_mulai->format('H:i') }} - {{ $jadwalKonseling->jam_selesai->format('H:i') }} WIB</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Tempat:</span>
                <span class="text-gray-900">{{ $jadwalKonseling->tempat }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Jenis:</span>
                <span class="text-gray-900 capitalize">{{ $jadwalKonseling->permohonanKonseling->jenis_konseling }}</span>
            </div>
        </div>
        <div class="mt-3">
            <span class="font-medium text-gray-700">Topik:</span>
            <span class="text-gray-900">{{ $jadwalKonseling->permohonanKonseling->topik_konseling }}</span>
        </div>
    </div>

    <!-- Download Template Section -->
    <div class="p-6 border-b border-gray-200 bg-blue-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-medium text-blue-800">Template Laporan</h3>
                <p class="text-sm text-blue-700 mt-1">Download template laporan, isi sesuai hasil bimbingan, lalu upload kembali</p>
            </div>
            <a href="{{ route('guru_bk.laporan.download-template') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-download mr-2"></i>Download Template
            </a>
        </div>
    </div>

    <div class="p-6">
        <form action="{{ route('guru_bk.laporan.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            
            <input type="hidden" name="jadwal_konseling_id" value="{{ $jadwalKonseling->id }}">

            <!-- Upload File -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Upload File Laporan <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="dokumen_laporan" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload file laporan</span>
                                <input id="dokumen_laporan" name="dokumen_laporan" type="file" class="sr-only" accept=".doc,.docx,.pdf" required>
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">Word (.doc, .docx) atau PDF sampai 10MB</p>
                    </div>
                </div>
                
                <div id="preview-container" class="hidden mt-4 p-4 bg-gray-50 rounded-md">
                    <div class="flex items-center">
                        <i id="file-icon" class="fas fa-file-word text-blue-600 text-2xl mr-3"></i>
                        <div class="flex-1">
                            <p id="file-name" class="text-sm font-medium text-gray-900"></p>
                            <p id="file-size" class="text-xs text-gray-500"></p>
                        </div>
                        <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-800 ml-3">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                @error('dokumen_laporan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Penting!</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan laporan sudah diisi dengan lengkap dan benar</li>
                                <li>File yang diupload akan dapat diakses oleh siswa</li>
                                <li>Laporan yang sudah diupload tidak dapat diubah</li>
                                <li>Format file yang diterima: Word (.doc, .docx) atau PDF</li>
                                <li>Ukuran file maksimal 10MB</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('guru_bk.laporan.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md text-sm font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-upload mr-2"></i>Upload Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('dokumen_laporan');
    const previewContainer = document.getElementById('preview-container');
    const dropZone = document.querySelector('.border-dashed');

    // Handle file input change
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            showFilePreview(file);
        }
    });

    // Handle drag and drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            
            // Validate file type
            const allowedTypes = ['.doc', '.docx', '.pdf'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!allowedTypes.includes(fileExtension)) {
                alert('File harus berformat Word (.doc, .docx) atau PDF');
                return;
            }
            
            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 10MB.');
                return;
            }
            
            fileInput.files = files;
            showFilePreview(file);
        }
    });

    function showFilePreview(file) {
        // Validate file type
        const allowedTypes = ['.doc', '.docx', '.pdf'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            alert('File harus berformat Word (.doc, .docx) atau PDF');
            fileInput.value = '';
            return;
        }
        
        // Validate file size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB.');
            fileInput.value = '';
            return;
        }
        
        // Show preview
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const fileIcon = document.getElementById('file-icon');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        // Set icon based on file type
        if (fileExtension === '.pdf') {
            fileIcon.className = 'fas fa-file-pdf text-red-600 text-2xl mr-3';
        } else {
            fileIcon.className = 'fas fa-file-word text-blue-600 text-2xl mr-3';
        }
        
        previewContainer.classList.remove('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    window.removeFile = function() {
        fileInput.value = '';
        previewContainer.classList.add('hidden');
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Silakan pilih file laporan yang akan diupload');
            return;
        }
        
        if (!confirm('Apakah Anda yakin ingin mengupload laporan ini? Laporan yang sudah diupload tidak dapat diubah.')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection