@extends('layouts.base')

@section('page-title', 'Dashboard')

@section('main-content')
<div>
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class='bx bx-user text-white text-xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">
                            Halo, {{ auth()->user()->siswa->nama_lengkap ?? auth()->user()->username }}!
                        </h1>
                        <p class="text-gray-600 mt-1 flex items-center">
                            <i class='bx bx-book mr-2 text-blue-500'></i>
                            {{ auth()->user()->siswa->kelas ?? '' }} {{ auth()->user()->siswa->jurusan ?? '' }}
                        </p>
                    </div>
                </div>
                <div class="text-right bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-semibold text-gray-800">{{ now()->format('H:i') }}</div>
                    <div class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-6">
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                    <i class='bx bx-user-check text-blue-600'></i>
                </div>
                <h3 class="font-medium text-gray-800">Status Konseling Saya</h3>
            </div>
        </div>
        <div class="p-6">
            @if($currentStatus['can_create_new'])
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-plus-circle text-3xl text-green-600'></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Siap untuk Konseling?</h4>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">Anda dapat mengajukan permohonan konseling baru untuk mendapatkan bimbingan dari Guru BK</p>
                    <a href="{{ route('siswa.permohonan.create') }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center transition-colors">
                        <i class='bx bx-plus mr-2'></i>
                        Ajukan Permohonan Konseling
                    </a>
                </div>
            @elseif($currentStatus['permohonan_aktif'])
                @php $permohonan = $currentStatus['permohonan_aktif']; @endphp
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class='bx bx-time-five text-xl text-yellow-600'></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Permohonan Sedang Diproses</h4>
                            <p class="text-sm text-gray-600 flex items-center">
                                <i class='bx bx-calendar mr-1'></i>
                                {{ $permohonan->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-category mr-1'></i>
                                Jenis Konseling
                            </p>
                            <p class="text-gray-900 mt-1">{{ ucfirst($permohonan->jenis_konseling) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-user-voice mr-1'></i>
                                Guru BK
                            </p>
                            <p class="text-gray-900 mt-1">{{ $permohonan->guruBK->nama_lengkap }}</p>
                        </div>
                        <div class="md:col-span-2 bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-message-detail mr-1'></i>
                                Topik
                            </p>
                            <p class="text-gray-900 mt-1">{{ $permohonan->topik_konseling }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class='bx bx-loader-alt mr-1'></i>
                            {{ ucfirst($permohonan->status) }}
                        </span>
                        <a href="{{ route('siswa.permohonan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center group">
                            Lihat Detail
                            <i class='bx bx-right-arrow-alt ml-1 group-hover:translate-x-1 transition-transform'></i>
                        </a>
                    </div>
                </div>
            @elseif($currentStatus['permohonan_disetujui'])
                @php $permohonan = $currentStatus['permohonan_disetujui']; @endphp
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class='bx bx-check-circle text-xl text-green-600'></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Permohonan Disetujui</h4>
                            <p class="text-sm text-gray-600 flex items-center">
                                <i class='bx bx-calendar mr-1'></i>
                                Menunggu penjadwalan dari Guru BK
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-category mr-1'></i>
                                Jenis Konseling
                            </p>
                            <p class="text-gray-900 mt-1">{{ ucfirst($permohonan->jenis_konseling) }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-user-voice mr-1'></i>
                                Guru BK
                            </p>
                            <p class="text-gray-900 mt-1">{{ $permohonan->guruBK->nama_lengkap }}</p>
                        </div>
                        <div class="md:col-span-2 bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-message-detail mr-1'></i>
                                Topik
                            </p>
                            <p class="text-gray-900 mt-1">{{ $permohonan->topik_konseling }}</p>
                        </div>
                    </div>

                    @if($permohonan->catatan_guru_bk)
                    <div class="bg-white p-3 rounded-lg mb-4">
                        <p class="text-sm font-medium text-gray-700 flex items-center">
                            <i class='bx bx-note mr-1'></i>
                            Catatan dari Guru BK
                        </p>
                        <p class="text-gray-900 mt-1">{{ $permohonan->catatan_guru_bk }}</p>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class='bx bx-check-circle mr-1'></i>
                            {{ ucfirst($permohonan->status) }}
                        </span>
                        <a href="{{ route('siswa.permohonan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center group">
                            Lihat Detail
                            <i class='bx bx-right-arrow-alt ml-1 group-hover:translate-x-1 transition-transform'></i>
                        </a>
                    </div>
                </div>
            @elseif($currentStatus['jadwal_mendatang'])
                @php $jadwal = $currentStatus['jadwal_mendatang']; @endphp
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class='bx bx-calendar-check text-xl text-blue-600'></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Jadwal Konseling</h4>
                            <p class="text-sm text-gray-600 flex items-center">
                                <i class='bx bx-time mr-1'></i>
                                {{ $jadwal->tanggal_konseling->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-calendar mr-1'></i>
                                Tanggal & Waktu
                            </p>
                            <p class="text-gray-900 mt-1">{{ $jadwal->tanggal_konseling->format('d/m/Y') }}</p>
                            <p class="text-gray-900">{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-map-pin mr-1'></i>
                                Tempat
                            </p>
                            <p class="text-gray-900 mt-1">{{ $jadwal->tempat }}</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg">
                            <p class="text-sm font-medium text-gray-700 flex items-center">
                                <i class='bx bx-user-voice mr-1'></i>
                                Guru BK
                            </p>
                            <p class="text-gray-900 mt-1">{{ $jadwal->guruBK->nama_lengkap }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-lg mb-4">
                        <p class="text-sm font-medium text-gray-700 flex items-center">
                            <i class='bx bx-message-detail mr-1'></i>
                            Topik Konseling
                        </p>
                        <p class="text-gray-900 mt-1">{{ $jadwal->permohonanKonseling->topik_konseling }}</p>
                    </div>

                    @if($jadwal->catatan)
                    <div class="bg-white p-3 rounded-lg mb-4">
                        <p class="text-sm font-medium text-gray-700 flex items-center">
                            <i class='bx bx-note mr-1'></i>
                            Catatan Jadwal
                        </p>
                        <p class="text-gray-900 mt-1">{{ $jadwal->catatan }}</p>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between">
                        @php
                            $statusConfig = [
                                'dijadwalkan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'bx-calendar'],
                                'berlangsung' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'bx-loader-alt'],
                            ];
                            $config = $statusConfig[$jadwal->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'bx-info-circle'];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class='bx {{ $config['icon'] }} mr-1'></i>
                            {{ ucfirst($jadwal->status) }}
                        </span>
                        <a href="{{ route('siswa.jadwal.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center group">
                            Lihat Detail
                            <i class='bx bx-right-arrow-alt ml-1 group-hover:translate-x-1 transition-transform'></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-history text-purple-600'></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Riwayat Konseling</h3>
                            <p class="text-sm text-gray-500">Konseling yang telah selesai</p>
                        </div>
                    </div>
                    <a href="{{ route('siswa.riwayat.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium flex items-center group">
                        Lihat Semua
                        <i class='bx bx-right-arrow-alt ml-1 group-hover:translate-x-1 transition-transform'></i>
                    </a>
                </div>
            </div>
            <div class="p-5">
                @forelse($riwayatKonseling as $riwayat)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50 rounded-lg px-2 -mx-2 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-purple-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-check text-purple-600 text-xs'></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $riwayat->permohonanKonseling->topik_konseling }}</p>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class='bx bx-user-voice mr-1'></i>
                                {{ $riwayat->guruBK->nama_lengkap }}
                            </p>
                            <p class="text-xs text-gray-400 flex items-center">
                                <i class='bx bx-calendar mr-1'></i>
                                {{ $riwayat->tanggal_konseling->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class='bx bx-check mr-1'></i>
                            Selesai
                        </span>
                        @if($riwayat->laporanBimbingan)
                        <div class="w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center" title="Ada Laporan">
                            <i class='bx bx-file text-blue-600 text-xs'></i>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class='bx bx-history text-2xl text-gray-400'></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Belum ada riwayat konseling</p>
                    <p class="text-xs text-gray-400 mt-1">Riwayat akan muncul setelah konseling selesai</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-file-blank text-orange-600'></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Laporan Bimbingan</h3>
                            <p class="text-sm text-gray-500">Laporan yang dapat diunduh</p>
                        </div>
                    </div>
                    <a href="{{ route('siswa.laporan.index') }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium flex items-center group">
                        Lihat Semua
                        <i class='bx bx-right-arrow-alt ml-1 group-hover:translate-x-1 transition-transform'></i>
                    </a>
                </div>
            </div>
            <div class="p-5">
                @forelse($laporanTersedia as $laporan)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50 rounded-lg px-2 -mx-2 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-orange-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-file text-orange-600 text-xs'></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $laporan->jadwalKonseling->permohonanKonseling->topik_konseling }}</p>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class='bx bx-user-voice mr-1'></i>
                                {{ $laporan->jadwalKonseling->guruBK->nama_lengkap }}
                            </p>
                            <p class="text-xs text-gray-400 flex items-center">
                                <i class='bx bx-calendar mr-1'></i>
                                {{ $laporan->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class='bx bx-check-circle mr-1'></i>
                            Tersedia
                        </span>
                        <a href="{{ route('siswa.laporan.download', $laporan) }}" 
                           class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-md flex items-center justify-center transition-colors"
                           title="Download Laporan">
                            <i class='bx bx-download text-sm'></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class='bx bx-file-blank text-2xl text-gray-400'></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Belum ada laporan tersedia</p>
                    <p class="text-xs text-gray-400 mt-1">Laporan akan tersedia setelah konseling selesai</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @if($currentStatus['can_create_new'])
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-blue-500 p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class='bx bx-help-circle text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium">Butuh Bantuan Konseling?</h3>
                        <p class="text-white/80 mt-1">Jangan ragu untuk mengajukan permohonan konseling kapan saja</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('siswa.permohonan.create') }}" 
                       class="bg-white text-green-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                        <i class='bx bx-plus mr-2'></i>
                        Ajukan Permohonan
                    </a>
                    <a href="{{ route('siswa.riwayat.index') }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition-colors border border-white/30 flex items-center">
                        <i class='bx bx-history mr-2'></i>
                        Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection