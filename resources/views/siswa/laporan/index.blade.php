@extends('layouts.base')

@section('page-title', 'Laporan Bimbingan')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Laporan Bimbingan</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat dan download laporan hasil bimbingan konseling Anda</p>
            </div>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-blue-50">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tentang Laporan Bimbingan</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Laporan bimbingan berisi hasil dan rekomendasi dari sesi konseling yang telah Anda ikuti. 
                    Laporan ini dibuat oleh guru BK dan dapat Anda download untuk referensi pribadi.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $laporanBimbingan->count() }}</div>
                <div class="text-sm text-gray-600">Total Laporan</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ $laporanBimbingan->where('created_at', '>=', now()->startOfMonth())->count() }}
                </div>
                <div class="text-sm text-gray-600">Bulan Ini</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">
                    {{ $laporanBimbingan->where('created_at', '>=', now()->startOfYear())->count() }}
                </div>
                <div class="text-sm text-gray-600">Tahun Ini</div>
            </div>
        </div>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($laporanBimbingan as $laporan)
        <div class="p-6 hover:bg-gray-50">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $laporan->jadwalKonseling->permohonanKonseling->topik_konseling }}
                        </h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>Tersedia
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-3">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-user-tie mr-2 text-purple-500"></i>
                            <span>{{ $laporan->jadwalKonseling->guruBK->nama_lengkap }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            <span>{{ $laporan->jadwalKonseling->tanggal_konseling->format('d F Y') }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-tag mr-2 text-green-500"></i>
                            <span class="capitalize">{{ $laporan->jadwalKonseling->permohonanKonseling->jenis_konseling }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-600 mb-4">
                        <i class="fas fa-clock mr-2 text-orange-500"></i>
                        <span>{{ $laporan->jadwalKonseling->jam_mulai->format('H:i') }} - {{ $laporan->jadwalKonseling->jam_selesai->format('H:i') }} WIB</span>
                        <span class="mx-2">•</span>
                        <span>{{ $laporan->jadwalKonseling->tempat }}</span>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-4">
                        <div class="flex items-center">
                            @php
                                $extension = pathinfo($laporan->dokumen_laporan, PATHINFO_EXTENSION);
                                $iconClass = match($extension) {
                                    'pdf' => 'fas fa-file-pdf text-red-600',
                                    'doc', 'docx' => 'fas fa-file-word text-blue-600',
                                    default => 'fas fa-file text-gray-600'
                                };
                                $fileSize = file_exists($laporan->file_path) ? filesize($laporan->file_path) : 0;
                            @endphp
                            <i class="{{ $iconClass }} mr-2"></i>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-green-800">{{ $laporan->download_file_name }}</div>
                                <div class="text-xs text-green-600">
                                    Dibuat {{ $laporan->created_at->diffForHumans() }} • {{ strtoupper($extension) }} • {{ number_format($fileSize / 1024, 0) }} KB
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 ml-4">
                    <a href="{{ route('siswa.laporan.download', $laporan) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>Download
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-file-alt text-6xl mb-4 text-gray-300"></i>
            <div class="text-xl font-medium mb-2">Belum ada laporan bimbingan</div>
            <div class="text-sm mb-6">Laporan akan muncul setelah guru BK membuat laporan hasil konseling Anda</div>
            <a href="{{ route('siswa.permohonan.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                <i class="fas fa-plus mr-2"></i>Ajukan Konseling Baru
            </a>
        </div>
        @endforelse
    </div>

    @if($laporanBimbingan->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $laporanBimbingan->links() }}
    </div>
    @endif
</div>
@endsection