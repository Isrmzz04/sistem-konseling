@extends('layouts.base')

@section('page-title', 'Riwayat Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Riwayat Konseling</h2>
                <p class="mt-1 text-sm text-gray-600">Riwayat lengkap semua konseling yang pernah dilakukan</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">
                    Total: {{ $riwayatKonseling->total() }} konseling
                </span>
            </div>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-gray-900">{{ $statistikRiwayat['total_konseling'] }}</div>
                <div class="text-sm text-gray-600">Total Konseling</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-green-600">{{ $statistikRiwayat['total_selesai'] }}</div>
                <div class="text-sm text-gray-600">Selesai</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-red-600">{{ $statistikRiwayat['total_dibatalkan'] }}</div>
                <div class="text-sm text-gray-600">Dibatalkan</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-blue-600">{{ $statistikRiwayat['bulan_ini'] }}</div>
                <div class="text-sm text-gray-600">Bulan Ini</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-purple-600">{{ $statistikRiwayat['dengan_laporan'] }}</div>
                <div class="text-sm text-gray-600">Dengan Laporan</div>
            </div>
        </div>
    </div>

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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" 
                       name="tanggal_selesai" 
                       value="{{ request('tanggal_selesai') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm flex-1">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('administrator.konseling.riwayat') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if(request('search') || request('status') || request('guru_bk_id') || request('kelas') || request('periode') || request('tanggal_mulai') || request('tanggal_selesai'))
        <div class="px-6 py-3 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>
                        Menampilkan {{ $riwayatKonseling->count() }} dari {{ $riwayatKonseling->total() }} hasil
                        @if(request('search'))
                            untuk pencarian "<strong>{{ request('search') }}</strong>"
                        @endif
                        @if(request('status'))
                            dengan status "<strong>{{ ucfirst(request('status')) }}</strong>"
                        @endif
                        @if(request('periode'))
                            periode "<strong>{{ ucfirst(str_replace('_', ' ', request('periode'))) }}</strong>"
                        @endif
                    </span>
                </div>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        No
                    </th>
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
                        Status & Laporan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($riwayatKonseling as $index => $riwayat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $riwayatKonseling->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $riwayat->siswa->nama_lengkap }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $riwayat->siswa->kelas }} {{ $riwayat->siswa->jurusan }}
                            </div>
                            <div class="text-xs text-gray-400">
                                NISN: {{ $riwayat->siswa->nisn }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $riwayat->guruBK->nama_lengkap }}
                        </div>
                        <div class="text-xs text-gray-500">
                            NIP: {{ $riwayat->guruBK->nip }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $riwayat->tanggal_konseling->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $riwayat->jam_mulai->format('H:i') }} - {{ $riwayat->jam_selesai->format('H:i') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                📍 {{ $riwayat->tempat }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-blue-600">
                            {{ $riwayat->permohonanKonseling->topik_konseling }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ ucfirst($riwayat->permohonanKonseling->jenis_konseling) }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            Ajuan: {{ $riwayat->permohonanKonseling->created_at->format('d/m/Y') }}
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
                        <div class="flex flex-col space-y-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$riwayat->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($riwayat->status) }}
                            </span>
                            @if($riwayat->laporanBimbingan)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-file-alt mr-1"></i>Ada Laporan
                                </span>
                            @elseif($riwayat->status === 'selesai')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Belum Ada Laporan
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="showDetail({{ $riwayat->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-md text-xs font-medium transition-colors duration-200"
                                    title="Lihat Detail">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </button>
                            @if($riwayat->laporanBimbingan)
                                <a href="{{ route('administrator.laporan.download', $riwayat->laporanBimbingan) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 rounded-md text-xs font-medium transition-colors duration-200"
                                   title="Download Laporan">
                                    <i class="fas fa-download mr-1"></i>
                                    Laporan
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-history text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium">Tidak ada riwayat konseling</div>
                        <div class="mt-2 text-sm">
                            @if(request('search') || request('status') || request('guru_bk_id') || request('kelas') || request('periode') || request('tanggal_mulai') || request('tanggal_selesai'))
                                Tidak ada riwayat yang sesuai dengan filter yang dipilih
                            @else
                                Belum ada riwayat konseling yang tercatat
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($riwayatKonseling->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan {{ $riwayatKonseling->firstItem() }} sampai {{ $riwayatKonseling->lastItem() }} dari {{ $riwayatKonseling->total() }} hasil
                </div>
                <div class="flex items-center space-x-2">
                    <nav class="flex items-center space-x-1">
                        @if ($riwayatKonseling->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $riwayatKonseling->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        @foreach ($riwayatKonseling->getUrlRange(1, $riwayatKonseling->lastPage()) as $page => $url)
                            @if ($page == $riwayatKonseling->currentPage())
                                <span class="px-3 py-2 text-sm font-medium text-white bg-gray-600 rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($riwayatKonseling->hasMorePages())
                            <a href="{{ $riwayatKonseling->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    @endif
</div>

<div id="detailModal" class="fixed inset-0 bg-black/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full" style="height: 60vh; max-height: 60vh;">
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Riwayat Konseling</h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300" title="Tutup">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="detailContent" class="p-6 overflow-y-auto" style="height: calc(60vh - 80px);">
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
                    <!-- Timeline Status -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Timeline Konseling</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <span class="font-medium">Permohonan Diajukan:</span> 
                                    ${new Date(data.permohonan_konseling.created_at).toLocaleDateString('id-ID')}
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <span class="font-medium">Jadwal Dibuat:</span> 
                                    ${new Date(data.created_at).toLocaleDateString('id-ID')}
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 ${data.status === 'selesai' ? 'bg-green-500' : data.status === 'dibatalkan' ? 'bg-red-500' : 'bg-yellow-500'} rounded-full mr-3"></div>
                                <div class="text-sm">
                                    <span class="font-medium">Status Terakhir:</span> 
                                    ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                                </div>
                            </div>
                        </div>
                    </div>

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
                                <label class="block text-sm font-medium text-gray-700">Durasi</label>
                                <p class="text-sm text-gray-900">${calculateDuration(data.jam_mulai, data.jam_selesai)}</p>
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
                            <p class="text-sm text-gray-900 break-words">${data.permohonan_konseling.ringkasan_masalah}</p>
                        </div>
                    </div>

                    <!-- Catatan & Dokumentasi -->
                    ${data.catatan || data.dokumentasi ? `
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Catatan & Dokumentasi</h4>
                        ${data.catatan ? `
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Catatan Guru BK</label>
                            <p class="text-sm text-gray-900 break-words">${data.catatan}</p>
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
                                <p class="text-sm text-gray-900 font-medium">✅ Laporan telah dibuat</p>
                                <p class="text-xs text-gray-500">Dibuat: ${new Date(data.laporan_bimbingan.created_at).toLocaleDateString('id-ID')} ${new Date(data.laporan_bimbingan.created_at).toLocaleTimeString('id-ID')}</p>
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
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                            <p class="text-sm text-orange-700">
                                Konseling telah selesai tetapi laporan belum dibuat oleh Guru BK
                            </p>
                        </div>
                    </div>
                    ` : data.status === 'dibatalkan' ? `
                    <div class="bg-red-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Status Konseling</h4>
                        <div class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <p class="text-sm text-red-700">
                                Konseling telah dibatalkan
                            </p>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail riwayat');
        });
}

function calculateDuration(startTime, endTime) {
    const start = new Date(`2000-01-01 ${startTime}`);
    const end = new Date(`2000-01-01 ${endTime}`);
    const diffMs = end - start;
    const diffMins = Math.round(diffMs / 60000);
    
    if (diffMins < 60) {
        return `${diffMins} menit`;
    } else {
        const hours = Math.floor(diffMins / 60);
        const mins = diffMins % 60;
        return mins > 0 ? `${hours} jam ${mins} menit` : `${hours} jam`;
    }
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection