@extends('layouts.base')

@section('page-title', 'Monitoring Laporan Bimbingan')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Monitoring Laporan Bimbingan</h2>
                <p class="mt-1 text-sm text-gray-600">Monitoring semua laporan bimbingan dari seluruh Guru BK</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">
                    Total: {{ $laporanBimbingan->total() }} laporan
                </span>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-gray-900">{{ $statistik['total_laporan'] }}</div>
                <div class="text-sm text-gray-600">Total Laporan</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-blue-600">{{ $statistik['minggu_ini'] }}</div>
                <div class="text-sm text-gray-600">Minggu Ini</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-green-600">{{ $statistik['bulan_ini'] }}</div>
                <div class="text-sm text-gray-600">Bulan Ini</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-purple-600">{{ $statistik['tahun_ini'] }}</div>
                <div class="text-sm text-gray-600">Tahun Ini</div>
            </div>
        </div>

        <!-- Statistik Detail -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Top 5 Jenis Konseling -->
            <div class="bg-white p-4 rounded-lg border">
                <h4 class="font-medium text-gray-900 mb-3">Top 5 Jenis Konseling</h4>
                @forelse($statistik['per_jenis'] as $jenis => $jumlah)
                <div class="flex justify-between items-center py-1">
                    <span class="text-sm text-gray-700">{{ ucfirst($jenis) }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ $jumlah }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500">Belum ada data</p>
                @endforelse
            </div>

            <!-- Top 5 Guru BK -->
            <div class="bg-white p-4 rounded-lg border">
                <h4 class="font-medium text-gray-900 mb-3">Top 5 Guru BK</h4>
                @forelse($statistik['per_guru_bk'] as $guru => $jumlah)
                <div class="flex justify-between items-center py-1">
                    <span class="text-sm text-gray-700">{{ $guru }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ $jumlah }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama siswa, guru BK, topik..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Konseling</label>
                <select name="jenis_konseling" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="pribadi" {{ request('jenis_konseling') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                    <option value="sosial" {{ request('jenis_konseling') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                    <option value="akademik" {{ request('jenis_konseling') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="karir" {{ request('jenis_konseling') == 'karir' ? 'selected' : '' }}>Karir</option>
                    <option value="lainnya" {{ request('jenis_konseling') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Guru BK</label>
                <select name="guru_bk_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Guru BK</option>
                    @foreach($guruBKList as $guruBK)
                    <option value="{{ $guruBK->id }}" {{ request('guru_bk_id') == $guruBK->id ? 'selected' : '' }}>
                        {{ $guruBK->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="kelas" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                        {{ $kelas }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select name="periode" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Periode</option>
                    <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="semester_ini" {{ request('periode') == 'semester_ini' ? 'selected' : '' }}>Semester Ini</option>
                    <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" 
                       name="tanggal_mulai" 
                       value="{{ request('tanggal_mulai') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm flex-1">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('administrator.laporan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Laporan -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Siswa
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Guru BK
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Konseling
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal Konseling
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal Laporan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($laporanBimbingan as $laporan)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $laporan->jadwalKonseling->siswa->nama_lengkap }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $laporan->jadwalKonseling->siswa->kelas }} {{ $laporan->jadwalKonseling->siswa->jurusan }}
                            </div>
                            <div class="text-xs text-gray-400">
                                NISN: {{ $laporan->jadwalKonseling->siswa->nisn }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $laporan->jadwalKonseling->guruBK->nama_lengkap }}
                        </div>
                        <div class="text-xs text-gray-500">
                            NIP: {{ $laporan->jadwalKonseling->guruBK->nip }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-blue-600">
                                {{ $laporan->jadwalKonseling->permohonanKonseling->topik_konseling }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ ucfirst($laporan->jadwalKonseling->permohonanKonseling->jenis_konseling) }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $laporan->jadwalKonseling->tempat }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $laporan->jadwalKonseling->tanggal_konseling->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $laporan->jadwalKonseling->jam_mulai->format('H:i') }} - {{ $laporan->jadwalKonseling->jam_selesai->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $laporan->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $laporan->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="showDetail({{ $laporan->id }})" 
                                    class="text-blue-600 hover:text-blue-900 p-2 rounded border border-blue-200 hover:bg-blue-50"
                                    title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <a href="{{ route('administrator.laporan.download', $laporan) }}" 
                               class="text-green-600 hover:text-green-900 p-2 rounded border border-green-200 hover:bg-green-50"
                               title="Download Laporan">
                                <i class="fas fa-download text-sm"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-file-alt text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium">Tidak ada laporan bimbingan</div>
                        <div class="mt-2 text-sm">Belum ada laporan yang sesuai dengan filter</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($laporanBimbingan->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $laporanBimbingan->links() }}
    </div>
    @endif
</div>

<!-- Modal Detail Laporan -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full" style="height: 60vh; max-height: 60vh;">
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Laporan Bimbingan</h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="detailContent" class="p-6 overflow-y-auto" style="height: calc(60vh - 80px);">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(laporanId) {
    fetch(`/administrator/laporan/${laporanId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data); // Debug log
            
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-6">
                    <!-- Info Laporan -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Informasi Laporan</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
                                <p class="text-sm text-gray-900">${new Date(data.created_at).toLocaleDateString('id-ID', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File Laporan</label>
                                <p class="text-sm text-gray-900">${data.dokumen_laporan.split('/').pop()}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Siswa -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Informasi Siswa</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.siswa.nama_lengkap}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NISN</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.siswa.nisn}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.siswa.kelas} ${data.jadwal_konseling.siswa.jurusan}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telp</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.siswa.no_telp || 'Tidak ada'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Guru BK -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Guru BK</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Guru BK</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.guru_bk.nama_lengkap}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIP</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.guru_bk.nip}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Konseling -->
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Detail Konseling</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Konseling</label>
                                <p class="text-sm text-gray-900">${new Date(data.jadwal_konseling.tanggal_konseling).toLocaleDateString('id-ID', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric'
                                })}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Waktu</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.jam_mulai} - ${data.jadwal_konseling.jam_selesai}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.tempat}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Konseling</label>
                                <p class="text-sm text-gray-900">${data.jadwal_konseling.permohonan_konseling.jenis_konseling.charAt(0).toUpperCase() + data.jadwal_konseling.permohonan_konseling.jenis_konseling.slice(1)}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Topik Konseling</label>
                            <p class="text-sm text-gray-900">${data.jadwal_konseling.permohonan_konseling.topik_konseling}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ringkasan Masalah</label>
                            <p class="text-sm text-gray-900 whitespace-pre-line">${data.jadwal_konseling.permohonan_konseling.ringkasan_masalah}</p>
                        </div>
                    </div>

                    <!-- Catatan & Dokumentasi -->
                    ${data.jadwal_konseling.catatan || data.jadwal_konseling.dokumentasi ? `
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Catatan & Dokumentasi</h4>
                        ${data.jadwal_konseling.catatan ? `
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Catatan Guru BK</label>
                            <p class="text-sm text-gray-900 whitespace-pre-line">${data.jadwal_konseling.catatan}</p>
                        </div>
                        ` : ''}
                        ${data.jadwal_konseling.dokumentasi ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dokumentasi Foto</label>
                            <img src="${data.jadwal_konseling.dokumentasi}" alt="Dokumentasi" class="mt-2 max-w-full h-auto rounded-lg border">
                        </div>
                        ` : ''}
                    </div>
                    ` : ''}

                    <!-- Download Section -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Download Laporan</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-900">File laporan bimbingan tersedia untuk didownload</p>
                                <p class="text-xs text-gray-500">Format: ${data.dokumen_laporan.split('.').pop().toUpperCase()}</p>
                            </div>
                            <a href="/administrator/laporan/${data.id}/download" 
                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium inline-flex items-center">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail laporan: ' + error.message);
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