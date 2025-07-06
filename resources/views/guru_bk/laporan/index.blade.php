@extends('layouts.base')

@section('page-title', 'Laporan Bimbingan')

@section('main-content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Laporan Bimbingan</h2>
                    <p class="mt-1 text-sm text-gray-600">Kelola laporan hasil bimbingan konseling</p>
                </div>
                <a href="{{ route('guru_bk.laporan.download-template') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-download mr-2"></i>Download Template
                </a>
            </div>
        </div>

        <!-- Info Template -->
        <div class="p-6 bg-blue-50 border-b border-gray-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Cara Membuat Laporan</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Download template laporan dengan klik tombol "Download Template"</li>
                            <li>Isi template sesuai dengan hasil bimbingan</li>
                            <li>Simpan file dalam format Word (.doc/.docx) atau PDF</li>
                            <li>Upload laporan yang sudah diisi melalui tombol "Buat Laporan"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Perlu Laporan -->
    @if($jadwalPerluLaporan->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Perlu Dibuat Laporan</h3>
            <p class="mt-1 text-sm text-gray-600">Jadwal konseling yang sudah selesai dan perlu dibuatkan laporan</p>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($jadwalPerluLaporan as $jadwal)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ $jadwal->permohonanKonseling->topik_konseling }}
                            </h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                Perlu Laporan
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                <span>{{ $jadwal->siswa->nama_lengkap }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                                <span>{{ $jadwal->siswa->kelas }} {{ $jadwal->siswa->jurusan }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-purple-500"></i>
                                <span>{{ $jadwal->tanggal_konseling->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-2 text-orange-500"></i>
                                <span class="capitalize">{{ $jadwal->permohonanKonseling->jenis_konseling }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ml-4">
                        <a href="{{ route('guru_bk.laporan.create', $jadwal) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>Buat Laporan
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Daftar Laporan -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Laporan yang Sudah Dibuat</h3>
            <p class="mt-1 text-sm text-gray-600">Daftar semua laporan bimbingan yang sudah dibuat</p>
        </div>

        @if($laporanBimbingan->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Siswa & Topik
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Konseling
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Upload
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            File Laporan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($laporanBimbingan as $laporan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $laporan->jadwalKonseling->siswa->nama_lengkap }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $laporan->jadwalKonseling->siswa->kelas }} {{ $laporan->jadwalKonseling->siswa->jurusan }}
                                </div>
                                <div class="text-sm text-blue-600 mt-1">
                                    {{ $laporan->jadwalKonseling->permohonanKonseling->topik_konseling }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ ucfirst($laporan->jadwalKonseling->permohonanKonseling->jenis_konseling) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $laporan->jadwalKonseling->tanggal_konseling->format('d/m/Y') }}
                            <div class="text-xs text-gray-500">
                                {{ $laporan->jadwalKonseling->jam_mulai->format('H:i') }} - {{ $laporan->jadwalKonseling->jam_selesai->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $laporan->created_at->format('d/m/Y H:i') }}
                            <div class="text-xs text-gray-500">
                                {{ $laporan->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @php
                                    $extension = pathinfo($laporan->dokumen_laporan, PATHINFO_EXTENSION);
                                    $iconClass = match($extension) {
                                        'pdf' => 'fas fa-file-pdf text-red-600',
                                        'doc', 'docx' => 'fas fa-file-word text-blue-600',
                                        default => 'fas fa-file text-gray-600'
                                    };
                                @endphp
                                <i class="{{ $iconClass }} mr-2"></i>
                                <div>
                                    <span class="text-sm text-gray-900 block">{{ $laporan->download_file_name }}</span>
                                    <span class="text-xs text-gray-500">{{ strtoupper($extension) }} • {{ number_format(file_exists($laporan->file_path) ? filesize($laporan->file_path) / 1024 : 0, 0) }} KB</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="showDetail({{ $laporan->id }})" 
                                        class="text-blue-600 hover:text-blue-900 p-1 rounded"
                                        title="Lihat Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                                
                                <a href="{{ route('guru_bk.laporan.download', $laporan) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-medium"
                                   title="Download Laporan">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                                
                                <form action="{{ route('guru_bk.laporan.destroy', $laporan) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-medium"
                                            title="Hapus Laporan">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($laporanBimbingan->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $laporanBimbingan->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-file-alt text-6xl mb-4 text-gray-300"></i>
            <div class="text-xl font-medium mb-2">Belum ada laporan dibuat</div>
            <div class="text-sm">Laporan akan muncul setelah Anda membuat laporan bimbingan</div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Detail Laporan -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Laporan Bimbingan</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="detailContent" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(laporanId) {
    fetch(`/guru_bk/laporan/${laporanId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Siswa</label>
                            <p class="text-sm text-gray-900">${data.jadwal_konseling.siswa.nama_lengkap}</p>
                            <p class="text-xs text-gray-500">${data.jadwal_konseling.siswa.kelas} ${data.jadwal_konseling.siswa.jurusan}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Konseling</label>
                            <p class="text-sm text-gray-900">${new Date(data.jadwal_konseling.tanggal_konseling).toLocaleDateString('id-ID')}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Topik Konseling</label>
                        <p class="text-sm text-gray-900">${data.jadwal_konseling.permohonan_konseling.topik_konseling}</p>
                        <p class="text-xs text-gray-500">${data.jadwal_konseling.permohonan_konseling.jenis_konseling.charAt(0).toUpperCase() + data.jadwal_konseling.permohonan_konseling.jenis_konseling.slice(1)}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu Konseling</label>
                            <p class="text-sm text-gray-900">${data.jadwal_konseling.jam_mulai} - ${data.jadwal_konseling.jam_selesai}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tempat</label>
                            <p class="text-sm text-gray-900">${data.jadwal_konseling.tempat}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">File Laporan</label>
                        <div class="flex items-center mt-2">
                            <i class="fas fa-file-word text-blue-600 mr-2"></i>
                            <div>
                                <span class="text-sm text-gray-900 block">${data.download_file_name}</span>
                                <span class="text-xs text-gray-500">File asli: ${data.file_name}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Upload</label>
                            <p class="text-sm text-gray-900">${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu Upload</label>
                            <p class="text-sm text-gray-900">${new Date(data.created_at).toLocaleTimeString('id-ID')}</p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail laporan');
        });
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection