@extends('layouts.base')

@section('page-title', 'Monitoring Jadwal Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Monitoring Jadwal Konseling</h2>
                <p class="mt-1 text-sm text-gray-600">Monitoring semua jadwal konseling yang sedang berjalan</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">
                    Total: {{ $jadwalKonseling->total() }} jadwal
                </span>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-gray-900">{{ $statistik['total'] }}</div>
                <div class="text-sm text-gray-600">Total Jadwal</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-blue-600">{{ $statistik['dijadwalkan'] }}</div>
                <div class="text-sm text-gray-600">Dijadwalkan</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-yellow-600">{{ $statistik['berlangsung'] }}</div>
                <div class="text-sm text-gray-600">Berlangsung</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-green-600">{{ $statistik['selesai'] }}</div>
                <div class="text-sm text-gray-600">Selesai</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-red-600">{{ $statistik['dibatalkan'] }}</div>
                <div class="text-sm text-gray-600">Dibatalkan</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-purple-600">{{ $statistik['hari_ini'] }}</div>
                <div class="text-sm text-gray-600">Hari Ini</div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama siswa, guru BK, tempat..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" 
                       name="tanggal_mulai" 
                       value="{{ request('tanggal_mulai') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm flex-1">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('administrator.konseling.jadwal') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                    Reset
                </a>
                <button type="button" onclick="filterHariIni()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm">
                    Hari Ini
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Jadwal -->
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
                        Jadwal & Tempat
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Topik Konseling
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jadwalKonseling as $jadwal)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $jadwal->siswa->nama_lengkap }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $jadwal->siswa->kelas }} {{ $jadwal->siswa->jurusan }}
                            </div>
                            <div class="text-xs text-gray-400">
                                NISN: {{ $jadwal->siswa->nisn }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $jadwal->guruBK->nama_lengkap }}
                        </div>
                        <div class="text-xs text-gray-500">
                            NIP: {{ $jadwal->guruBK->nip }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $jadwal->tanggal_konseling->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                📍 {{ $jadwal->tempat }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-blue-600">
                            {{ $jadwal->permohonanKonseling->topik_konseling }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ ucfirst($jadwal->permohonanKonseling->jenis_konseling) }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'dijadwalkan' => 'bg-blue-100 text-blue-800',
                                'berlangsung' => 'bg-yellow-100 text-yellow-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$jadwal->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($jadwal->status) }}
                        </span>
                        @if($jadwal->laporanBimbingan)
                            <div class="text-xs text-green-600 mt-1">
                                <i class="fas fa-file-alt mr-1"></i>Ada Laporan
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="showDetail({{ $jadwal->id }})" 
                                    class="text-blue-600 hover:text-blue-900 p-2 rounded border border-blue-200 hover:bg-blue-50"
                                    title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            @if($jadwal->laporanBimbingan)
                                <a href="{{ route('administrator.laporan.download', $jadwal->laporanBimbingan) }}" 
                                   class="text-green-600 hover:text-green-900 p-2 rounded border border-green-200 hover:bg-green-50"
                                   title="Download Laporan">
                                    <i class="fas fa-download text-sm"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-calendar-alt text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium">Tidak ada jadwal konseling</div>
                        <div class="mt-2 text-sm">Belum ada jadwal yang sesuai dengan filter</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($jadwalKonseling->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $jadwalKonseling->links() }}
    </div>
    @endif
</div>

<!-- Modal Detail Jadwal -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full" style="height: 60vh; max-height: 60vh;">
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Jadwal Konseling</h3>
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
function showDetail(jadwalId) {
    fetch(`/administrator/konseling/jadwal/${jadwalId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-6">
                    <!-- Informasi Siswa -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Informasi Siswa</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <p class="text-sm text-gray-900">${data.siswa.nama_lengkap}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NISN</label>
                                <p class="text-sm text-gray-900">${data.siswa.nisn}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                                <p class="text-sm text-gray-900">${data.siswa.kelas} ${data.siswa.jurusan}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telp</label>
                                <p class="text-sm text-gray-900">${data.siswa.no_telp || 'Tidak ada'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Guru BK -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Guru BK</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Guru BK</label>
                                <p class="text-sm text-gray-900">${data.guru_bk.nama_lengkap}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIP</label>
                                <p class="text-sm text-gray-900">${data.guru_bk.nip}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Jadwal -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Detail Jadwal</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <p class="text-sm text-gray-900">${new Date(data.tanggal_konseling).toLocaleDateString('id-ID', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric'
                                })}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Waktu</label>
                                <p class="text-sm text-gray-900">${data.jam_mulai} - ${data.jam_selesai}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat</label>
                                <p class="text-sm text-gray-900">${data.tempat}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                    data.status === 'dijadwalkan' ? 'bg-blue-100 text-blue-800' :
                                    data.status === 'berlangsung' ? 'bg-yellow-100 text-yellow-800' :
                                    data.status === 'selesai' ? 'bg-green-100 text-green-800' :
                                    data.status === 'dibatalkan' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'
                                }">
                                    ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Permohonan -->
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Detail Permohonan</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Konseling</label>
                                <p class="text-sm text-gray-900">${data.permohonan_konseling.jenis_konseling.charAt(0).toUpperCase() + data.permohonan_konseling.jenis_konseling.slice(1)}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Ajuan</label>
                                <p class="text-sm text-gray-900">${new Date(data.permohonan_konseling.created_at).toLocaleDateString('id-ID')}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Topik Konseling</label>
                            <p class="text-sm text-gray-900">${data.permohonan_konseling.topik_konseling}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ringkasan Masalah</label>
                            <p class="text-sm text-gray-900 whitespace-pre-line">${data.permohonan_konseling.ringkasan_masalah}</p>
                        </div>
                    </div>

                    <!-- Catatan & Dokumentasi -->
                    ${data.catatan || data.dokumentasi ? `
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Catatan & Dokumentasi</h4>
                        ${data.catatan ? `
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <p class="text-sm text-gray-900 whitespace-pre-line">${data.catatan}</p>
                        </div>
                        ` : ''}
                        ${data.dokumentasi ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dokumentasi Foto</label>
                            <img src="${data.dokumentasi}" alt="Dokumentasi" class="mt-2 max-w-full h-auto rounded-lg border">
                        </div>
                        ` : ''}
                    </div>
                    ` : ''}

                    <!-- Info Laporan -->
                    ${data.laporan_bimbingan ? `
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Laporan Bimbingan</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-900">Laporan telah dibuat</p>
                                <p class="text-xs text-gray-500">Dibuat: ${new Date(data.laporan_bimbingan.created_at).toLocaleDateString('id-ID')}</p>
                            </div>
                            <a href="/administrator/laporan/${data.laporan_bimbingan.id}/download" 
                               class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>
                        </div>
                    </div>
                    ` : data.status === 'selesai' ? `
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Laporan Bimbingan</h4>
                        <p class="text-sm text-orange-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Konseling telah selesai tetapi laporan belum dibuat oleh Guru BK
                        </p>
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail jadwal');
        });
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function filterHariIni() {
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="tanggal_mulai"]').value = today;
    document.querySelector('form').submit();
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection