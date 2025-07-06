@extends('layouts.base')

@section('page-title', 'Dashboard Administrator')

@section('main-content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-sm text-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->username }}!</h1>
                <p class="text-blue-100 mt-1">Monitoring sistem konseling siswa - {{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ now()->format('H:i') }}</div>
                <div class="text-sm text-blue-100">{{ now()->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Permohonan Hari Ini -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-file-plus text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Permohonan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['permohonan_hari_ini'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">Minggu ini: {{ $statistics['permohonan_minggu_ini'] }}</span>
            </div>
        </div>

        <!-- Konseling Berlangsung -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-comments text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Konseling Berlangsung</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['konseling_berlangsung'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">Jadwal hari ini: {{ $statistics['jadwal_hari_ini'] }}</span>
            </div>
        </div>

        <!-- Permohonan Pending -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Permohonan Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['permohonan_pending'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">Rata-rata: {{ $statistics['rata_response'] }} hari</span>
            </div>
        </div>

        <!-- Tingkat Penyelesaian -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tingkat Penyelesaian</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['tingkat_penyelesaian'] }}%</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">Bulan ini</span>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Guru BK Paling Aktif -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Guru BK Paling Aktif</h3>
                <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-user-tie text-sm"></i>
                </div>
            </div>
            <div class="text-center">
                <p class="text-xl font-bold text-gray-900">{{ $statistics['guru_bk_aktif']['nama'] }}</p>
                <p class="text-sm text-gray-600">{{ $statistics['guru_bk_aktif']['jumlah'] }} konseling bulan ini</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                <div class="p-2 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-bolt text-sm"></i>
                </div>
            </div>
            <div class="space-y-2">
                <a href="{{ route('administrator.konseling.permohonan') }}" class="flex items-center p-2 rounded-md hover:bg-gray-50 transition-colors">
                    <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                    <span class="text-sm text-gray-700">Lihat Semua Permohonan</span>
                </a>
                <a href="{{ route('administrator.konseling.jadwal') }}" class="flex items-center p-2 rounded-md hover:bg-gray-50 transition-colors">
                    <i class="fas fa-calendar text-green-500 mr-3"></i>
                    <span class="text-sm text-gray-700">Monitoring Jadwal</span>
                </a>
                <a href="{{ route('administrator.laporan.index') }}" class="flex items-center p-2 rounded-md hover:bg-gray-50 transition-colors">
                    <i class="fas fa-chart-bar text-purple-500 mr-3"></i>
                    <span class="text-sm text-gray-700">Lihat Laporan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Quick Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Aktivitas Terkini</h3>
                    <div class="p-2 rounded-full bg-orange-100 text-orange-600">
                        <i class="fas fa-history text-sm"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-1">3 hari terakhir</p>
            </div>
            <div class="p-6" style="max-height: 400px; overflow-y: auto;">
                @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 mb-4 last:mb-0">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-600 flex items-center justify-center">
                            <i class="{{ $activity['icon'] }} text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                        @if($activity['meta'])
                        <p class="text-xs text-gray-500">{{ $activity['meta'] }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada aktivitas terkini</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Permohonan Pending & Jadwal Hari Ini -->
        <div class="space-y-6">
            <!-- Permohonan Pending -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Permohonan Pending</h3>
                        <a href="{{ route('administrator.konseling.permohonan', ['status' => 'menunggu']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($pendingPermohonan as $permohonan)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $permohonan->siswa->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $permohonan->siswa->kelas }} - {{ ucfirst($permohonan->jenis_konseling) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">{{ $permohonan->created_at->diffForHumans() }}</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Menunggu
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-2xl text-green-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Tidak ada permohonan pending</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Jadwal Hari Ini -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Jadwal Hari Ini</h3>
                        <a href="{{ route('administrator.konseling.jadwal', ['tanggal_mulai' => now()->format('Y-m-d')]) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($jadwalHariIni as $jadwal)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $jadwal->siswa->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $jadwal->guruBK->nama_lengkap }} - {{ $jadwal->tempat }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</p>
                            @php
                                $statusColors = [
                                    'dijadwalkan' => 'bg-blue-100 text-blue-800',
                                    'berlangsung' => 'bg-yellow-100 text-yellow-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'dibatalkan' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$jadwal->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($jadwal->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-calendar text-2xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Tidak ada jadwal hari ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection