@extends('layouts.base')

@section('page-title', 'Dashboard')

@section('main-content')
<div class="">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class='bx bxs-dashboard text-white text-xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">
                            Selamat Datang, {{ auth()->user()->username }}!
                        </h1>
                        <p class="text-gray-600 mt-1 flex items-center">
                            <i class='bx bx-calendar mr-2 text-blue-500'></i>
                            Monitoring sistem konseling siswa - {{ now()->format('l, d F Y') }}
                        </p>
                    </div>
                </div>
                <div class="text-right bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-semibold text-gray-800">{{ now()->format('H:i') }}</div>
                    <div class="text-sm text-gray-500 flex items-center justify-end">
                        <i class='bx bx-time mr-1'></i>
                        {{ now()->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:border-green-200 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-file-plus text-green-600'></i>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['permohonan_hari_ini'] }}</p>
                    <p class="text-sm text-green-600 font-medium">Permohonan Hari Ini</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-md">
                    Minggu ini: {{ $statistics['permohonan_minggu_ini'] }}
                </span>
                <i class='bx bx-trending-up text-green-500 text-sm'></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:border-orange-200 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-conversation text-orange-600'></i>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['konseling_berlangsung'] }}</p>
                    <p class="text-sm text-orange-600 font-medium">Konseling Berlangsung</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-md">
                    Jadwal hari ini: {{ $statistics['jadwal_hari_ini'] }}
                </span>
                <i class='bx bx-time-five text-orange-500 text-sm'></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:border-red-200 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-loader-circle text-red-600'></i>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['permohonan_pending'] }}</p>
                    <p class="text-sm text-red-600 font-medium">Permohonan Pending</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-md">
                    Rata-rata: {{ $statistics['rata_response'] }} hari
                </span>
                <i class='bx bx-error-circle text-red-500 text-sm'></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:border-purple-200 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-line-chart text-purple-600'></i>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['tingkat_penyelesaian'] }}%</p>
                    <p class="text-sm text-purple-600 font-medium">Tingkat Penyelesaian</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-md">
                    Bulan ini
                </span>
                <i class='bx bx-check-circle text-purple-500 text-sm'></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-user-voice text-blue-600'></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-800">Guru BK Paling Aktif</h3>
                    <p class="text-sm text-gray-500">Bulan ini</p>
                </div>
            </div>
            <div class="text-center bg-blue-50 rounded-lg p-4">
                <p class="text-lg font-semibold text-gray-800">{{ $statistics['guru_bk_aktif']['nama'] }}</p>
                <p class="text-sm text-gray-600 flex items-center justify-center mt-1">
                    <i class='bx bx-medal mr-2 text-blue-500'></i>
                    {{ $statistics['guru_bk_aktif']['jumlah'] }} konseling bulan ini
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-rocket text-green-600'></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-800">Quick Actions</h3>
                    <p class="text-sm text-gray-500">Akses cepat</p>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('administrator.konseling.permohonan') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-colors group">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class='bx bx-file-blank text-blue-600 text-sm'></i>
                    </div>
                    <span class="text-sm text-gray-700 ml-3">Lihat Semua Permohonan</span>
                    <i class='bx bx-right-arrow-alt ml-auto text-gray-400 group-hover:text-blue-500 transition-colors'></i>
                </a>
                <a href="{{ route('administrator.konseling.jadwal') }}" class="flex items-center p-3 rounded-lg hover:bg-green-50 transition-colors group">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <i class='bx bx-calendar-check text-green-600 text-sm'></i>
                    </div>
                    <span class="text-sm text-gray-700 ml-3">Monitoring Jadwal</span>
                    <i class='bx bx-right-arrow-alt ml-auto text-gray-400 group-hover:text-green-500 transition-colors'></i>
                </a>
                <a href="{{ route('administrator.laporan.index') }}" class="flex items-center p-3 rounded-lg hover:bg-purple-50 transition-colors group">
                    <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class='bx bx-bar-chart-alt-2 text-purple-600 text-sm'></i>
                    </div>
                    <span class="text-sm text-gray-700 ml-3">Lihat Laporan</span>
                    <i class='bx bx-right-arrow-alt ml-auto text-gray-400 group-hover:text-purple-500 transition-colors'></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-md flex items-center justify-center">
                        <i class='bx bx-history text-orange-600'></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800">Aktivitas Terkini</h3>
                        <p class="text-sm text-gray-500">3 hari terakhir</p>
                    </div>
                </div>
            </div>
            <div class="p-5" style="max-height: 400px; overflow-y: auto;">
                @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 mb-4 last:mb-0 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-600 flex items-center justify-center">
                            <i class="{{ $activity['icon'] }} text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                        @if($activity['meta'])
                        <p class="text-xs text-gray-500 mt-1 bg-gray-100 inline-block px-2 py-1 rounded-md">{{ $activity['meta'] }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-2 flex items-center">
                            <i class='bx bx-time-five mr-1'></i>
                            {{ $activity['time']->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class='bx bx-inbox text-2xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada aktivitas terkini</p>
                    <p class="text-gray-400 text-sm mt-1">Aktivitas akan muncul di sini</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-red-100 rounded-md flex items-center justify-center">
                                <i class='bx bx-time text-red-600'></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Permohonan Pending</h3>
                                <p class="text-sm text-gray-500">Memerlukan perhatian</p>
                            </div>
                        </div>
                        <a href="{{ route('administrator.konseling.permohonan', ['status' => 'menunggu']) }}" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                            Lihat Semua
                            <i class='bx bx-right-arrow-alt ml-1'></i>
                        </a>
                    </div>
                </div>
                <div class="p-5">
                    @forelse($pendingPermohonan as $permohonan)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-red-100 rounded-md flex items-center justify-center">
                                <i class='bx bx-user text-red-600 text-xs'></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $permohonan->siswa->nama_lengkap }}</p>
                                <p class="text-xs text-gray-500">{{ $permohonan->siswa->kelas }} • {{ ucfirst($permohonan->jenis_konseling) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-1">{{ $permohonan->created_at->diffForHumans() }}</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class='bx bx-loader-circle mr-1'></i>
                                Menunggu
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class='bx bx-check-circle text-xl text-green-600'></i>
                        </div>
                        <p class="text-sm text-gray-500">Tidak ada permohonan pending</p>
                        <p class="text-xs text-gray-400 mt-1">Semua permohonan telah diproses</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                                <i class='bx bx-calendar-event text-blue-600'></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-800">Jadwal Hari Ini</h3>
                                <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('administrator.konseling.jadwal', ['tanggal_mulai' => now()->format('Y-m-d')]) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                            Lihat Detail
                            <i class='bx bx-right-arrow-alt ml-1'></i>
                        </a>
                    </div>
                </div>
                <div class="p-5">
                    @forelse($jadwalHariIni as $jadwal)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex items-center space-x-3">
                            <div class="w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center">
                                <i class='bx bx-user-voice text-blue-600 text-xs'></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $jadwal->siswa->nama_lengkap }}</p>
                                <p class="text-xs text-gray-500 flex items-center">
                                    <i class='bx bx-user mr-1'></i>
                                    {{ $jadwal->guruBK->nama_lengkap }} • {{ $jadwal->tempat }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900 flex items-center justify-end">
                                <i class='bx bx-time mr-1 text-gray-500'></i>
                                {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}
                            </p>
                            @php
                                $statusConfig = [
                                    'dijadwalkan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'bx-calendar'],
                                    'berlangsung' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'bx-play-circle'],
                                    'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'bx-check-circle'],
                                    'dibatalkan' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'bx-x-circle']
                                ];
                                $config = $statusConfig[$jadwal->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'bx-info-circle'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} mt-1">
                                <i class='bx {{ $config['icon'] }} mr-1'></i>
                                {{ ucfirst($jadwal->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class='bx bx-calendar text-xl text-gray-400'></i>
                        </div>
                        <p class="text-sm text-gray-500">Tidak ada jadwal hari ini</p>
                        <p class="text-xs text-gray-400 mt-1">Jadwal kosong untuk hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection