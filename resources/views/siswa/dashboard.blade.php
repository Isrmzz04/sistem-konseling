@extends('layouts.base')

@section('page-title', 'Dashboard Siswa')

@section('main-content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Halo, {{ auth()->user()->siswa->nama_lengkap ?? auth()->user()->username }}!</h1>
                <p class="text-gray-600 mt-1">{{ auth()->user()->siswa->kelas ?? '' }} {{ auth()->user()->siswa->jurusan ?? '' }}</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-900">{{ now()->format('H:i') }}</div>
                <div class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Current Status - Big Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class='bx bx-user-check text-blue-500 mr-2'></i>
                Status Konseling Saya
            </h3>
        </div>
        <div class="p-6">
            @if($currentStatus['can_create_new'])
                <!-- Bisa Ajukan Permohonan Baru -->
                <div class="text-center py-8">
                    <i class='bx bx-plus-circle text-6xl text-green-500 mb-4'></i>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Siap untuk Konseling?</h4>
                    <p class="text-gray-600 mb-6">Anda dapat mengajukan permohonan konseling baru</p>
                    <a href="{{ route('siswa.permohonan.create') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                        <i class='bx bx-plus mr-2'></i>
                        Ajukan Permohonan Konseling
                    </a>
                </div>
            @elseif($currentStatus['permohonan_aktif'])
                <!-- Ada Permohonan Aktif -->
                @php $permohonan = $currentStatus['permohonan_aktif']; @endphp
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <i class='bx bx-time-five text-2xl text-yellow-600 mr-3'></i>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Permohonan Sedang Diproses</h4>
                            <p class="text-sm text-gray-600">{{ $permohonan->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Jenis Konseling</p>
                            <p class="text-gray-900">{{ ucfirst($permohonan->jenis_konseling) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Guru BK</p>
                            <p class="text-gray-900">{{ $permohonan->guruBK->nama_lengkap }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-700">Topik</p>
                            <p class="text-gray-900">{{ $permohonan->topik_konseling }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class='bx bx-loader-alt mr-1'></i>
                            {{ ucfirst($permohonan->status) }}
                        </span>
                        <a href="{{ route('siswa.permohonan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            @elseif($currentStatus['jadwal_mendatang'])
                <!-- Ada Jadwal Mendatang -->
                @php $jadwal = $currentStatus['jadwal_mendatang']; @endphp
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <i class='bx bx-calendar-check text-2xl text-blue-600 mr-3'></i>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">Jadwal Konseling</h4>
                            <p class="text-sm text-gray-600">{{ $jadwal->tanggal_konseling->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Tanggal & Waktu</p>
                            <p class="text-gray-900">{{ $jadwal->tanggal_konseling->format('d/m/Y') }}</p>
                            <p class="text-gray-900">{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Tempat</p>
                            <p class="text-gray-900">{{ $jadwal->tempat }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Guru BK</p>
                            <p class="text-gray-900">{{ $jadwal->guruBK->nama_lengkap }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        @php
                            $statusColors = [
                                'dijadwalkan' => 'bg-blue-100 text-blue-800',
                                'berlangsung' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $statusIcons = [
                                'dijadwalkan' => 'bx-calendar',
                                'berlangsung' => 'bx-loader-alt',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$jadwal->status] ?? 'bg-gray-100 text-gray-800' }}">
                            <i class='bx {{ $statusIcons[$jadwal->status] ?? "bx-info-circle" }} mr-1'></i>
                            {{ ucfirst($jadwal->status) }}
                        </span>
                        <a href="{{ route('siswa.jadwal.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Riwayat Konseling -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class='bx bx-history text-purple-500 mr-2'></i>
                        Riwayat Konseling
                    </h3>
                    <a href="{{ route('siswa.riwayat.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                @forelse($riwayatKonseling as $riwayat)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $riwayat->permohonanKonseling->topik_konseling }}</p>
                        <p class="text-xs text-gray-500">{{ $riwayat->guruBK->nama_lengkap }}</p>
                        <p class="text-xs text-gray-400">{{ $riwayat->tanggal_konseling->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class='bx bx-check mr-1'></i>
                            Selesai
                        </span>
                        @if($riwayat->laporanBimbingan)
                        <i class='bx bx-file text-blue-500' title="Ada Laporan"></i>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <i class='bx bx-history text-3xl text-gray-300 mb-2'></i>
                    <p class="text-sm text-gray-500">Belum ada riwayat konseling</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Laporan Tersedia -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class='bx bx-file-blank text-orange-500 mr-2'></i>
                        Laporan Bimbingan
                    </h3>
                    <a href="{{ route('siswa.laporan.index') }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                @forelse($laporanTersedia as $laporan)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $laporan->jadwalKonseling->permohonanKonseling->topik_konseling }}</p>
                        <p class="text-xs text-gray-500">{{ $laporan->jadwalKonseling->guruBK->nama_lengkap }}</p>
                        <p class="text-xs text-gray-400">{{ $laporan->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class='bx bx-check-circle mr-1'></i>
                            Tersedia
                        </span>
                        <a href="{{ route('siswa.laporan.download', $laporan) }}" 
                           class="text-blue-600 hover:text-blue-800 p-1 rounded"
                           title="Download Laporan">
                            <i class='bx bx-download text-sm'></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <i class='bx bx-file-blank text-3xl text-gray-300 mb-2'></i>
                    <p class="text-sm text-gray-500">Belum ada laporan tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions (jika bisa buat permohonan baru) -->
    @if($currentStatus['can_create_new'])
    <div class="bg-gradient-to-r from-green-500 to-blue-500 rounded-lg shadow-sm text-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium">Butuh Bantuan Konseling?</h3>
                <p class="text-green-100 mt-1">Jangan ragu untuk mengajukan permohonan konseling kapan saja</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('siswa.permohonan.create') }}" 
                   class="bg-white text-green-600 hover:bg-gray-100 px-4 py-2 rounded-md font-medium transition-colors">
                    <i class='bx bx-plus mr-1'></i>
                    Ajukan Permohonan
                </a>
                <a href="{{ route('siswa.riwayat.index') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition-colors border border-green-400">
                    <i class='bx bx-history mr-1'></i>
                    Lihat Riwayat
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Box Icons CDN -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection