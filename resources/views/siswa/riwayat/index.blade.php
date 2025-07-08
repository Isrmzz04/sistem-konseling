@extends('layouts.base')

@section('page-title', 'Riwayat Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Riwayat Konseling</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat semua riwayat konseling yang pernah dilakukan</p>
            </div>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-xl font-bold text-green-600">{{ $riwayatKonseling->where('status', 'selesai')->count() }}</div>
                <div class="text-xs text-gray-600">Selesai</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-blue-600">{{ $riwayatKonseling->where('status', 'dijadwalkan')->count() }}</div>
                <div class="text-xs text-gray-600">Dijadwalkan</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-red-600">{{ $riwayatKonseling->where('status', 'dibatalkan')->count() }}</div>
                <div class="text-xs text-gray-600">Dibatalkan</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-gray-600">{{ $riwayatKonseling->total() }}</div>
                <div class="text-xs text-gray-600">Total</div>
            </div>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <div class="min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <input type="month" 
                       name="bulan" 
                       value="{{ request('bulan') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('siswa.riwayat.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="p-6">
        @forelse($riwayatKonseling as $riwayat)
        <div class="relative pl-8 pb-8 @if(!$loop->last) border-l-2 border-gray-200 @endif">
            <div class="absolute left-0 -ml-2 mt-2">
                @php
                    $dotColors = [
                        'selesai' => 'bg-green-500',
                        'dijadwalkan' => 'bg-blue-500',
                        'berlangsung' => 'bg-yellow-500',
                        'dibatalkan' => 'bg-red-500'
                    ];
                @endphp
                <div class="w-4 h-4 {{ $dotColors[$riwayat->status] ?? 'bg-gray-500' }} rounded-full border-2 border-white shadow"></div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $riwayat->permohonanKonseling->topik_konseling ?? 'Topik tidak tersedia' }}
                        </h3>
                        <div class="flex items-center space-x-3 mt-1">
                            @php
                                $statusColors = [
                                    'dijadwalkan' => 'bg-blue-100 text-blue-800',
                                    'berlangsung' => 'bg-yellow-100 text-yellow-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'dibatalkan' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$riwayat->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($riwayat->status) }}
                            </span>
                            @if($riwayat->permohonanKonseling)
                            <span class="text-sm text-gray-500 capitalize">
                                {{ $riwayat->permohonanKonseling->jenis_konseling }}
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-right text-sm text-gray-500">
                        {{ $riwayat->tanggal_konseling->format('d M Y') }}
                        <div>{{ $riwayat->jam_mulai->format('H:i') }} - {{ $riwayat->jam_selesai->format('H:i') }}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-user-tie mr-2 text-purple-500"></i>
                        <span>{{ $riwayat->guruBK->nama_lengkap ?? 'Guru BK tidak tersedia' }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                        <span>{{ $riwayat->tempat }}</span>
                    </div>
                </div>
                
                @if($riwayat->permohonanKonseling)
                <div class="text-sm text-gray-700 mb-4">
                    {{ Str::limit($riwayat->permohonanKonseling->ringkasan_masalah, 150) }}
                </div>
                @endif
                
                @if($riwayat->catatan)
                <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                    <div class="text-xs text-blue-600 font-medium mb-1">Catatan Konseling:</div>
                    <div class="text-sm text-blue-800">{{ $riwayat->catatan }}</div>
                </div>
                @endif
                
                @if($riwayat->dokumentasi)
                <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-4">
                    <div class="text-xs text-green-600 font-medium mb-2">Dokumentasi Bimbingan:</div>
                    @if(Str::startsWith($riwayat->dokumentasi, ['/storage/', 'http']))
                        <div class="mb-2">
                            <img src="{{ $riwayat->dokumentasi }}" 
                                 alt="Dokumentasi Bimbingan" 
                                 class="max-w-full h-48 object-cover rounded-md cursor-pointer"
                                 onclick="showImageModal('{{ $riwayat->dokumentasi }}')">
                        </div>
                        <p class="text-xs text-green-700">Klik gambar untuk melihat lebih besar</p>
                    @else
                        <div class="text-sm text-green-800">{{ $riwayat->dokumentasi }}</div>
                    @endif
                </div>
                @endif
                
                @if($riwayat->laporanBimbingan)
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs text-yellow-600 font-medium">Laporan Bimbingan Tersedia</div>
                            <div class="text-sm text-yellow-800">Laporan hasil konseling sudah dibuat</div>
                        </div>
                        <a href="#" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                            <i class="fas fa-download mr-1"></i>Unduh
                        </a>
                    </div>
                </div>
                @endif
                
                <div class="flex justify-between items-center">
                    <div class="text-xs text-gray-500">
                        Dibuat {{ $riwayat->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="fas fa-history text-6xl text-gray-300 mb-4"></i>
            <div class="text-xl font-medium text-gray-600 mb-2">Belum ada riwayat konseling</div>
            <div class="text-sm text-gray-500 mb-6">Riwayat akan muncul setelah Anda mengikuti sesi konseling</div>
            <a href="{{ route('siswa.permohonan.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                <i class="fas fa-plus mr-2"></i>Ajukan Konseling Baru
            </a>
        </div>
        @endforelse
    </div>

    @if($riwayatKonseling->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $riwayatKonseling->links() }}
    </div>
    @endif
</div>

<div id="detailModal" class="fixed inset-0 bg-black/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Riwayat Konseling</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="detailContent" class="p-6">
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="fixed inset-0 bg-black/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" 
                    class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-screen object-contain rounded-lg">
        </div>
    </div>
</div>

<script>
function showDetail(jadwalId) {
    fetch(`/guru_bk/jadwal/${jadwalId}`)
        .then(response => response.json())
        .then(data => {
            const statusColors = {
                'dijadwalkan': 'bg-blue-100 text-blue-800',
                'berlangsung': 'bg-yellow-100 text-yellow-800',
                'selesai': 'bg-green-100 text-green-800',
                'dibatalkan': 'bg-red-100 text-red-800'
            };
            
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-6">
                    <!-- Header -->
                    <div class="text-center border-b border-gray-200 pb-4">
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">${data.permohonan_konseling ? data.permohonan_konseling.topik_konseling : 'Detail Riwayat Konseling'}</h4>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusColors[data.status]}">
                            ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                        </span>
                    </div>
                    
                    <!-- Informasi Jadwal -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h5 class="font-medium text-gray-900 mb-3">Informasi Jadwal</h5>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <label class="block text-gray-600 font-medium">Tanggal</label>
                                <p class="text-gray-900">${new Date(data.tanggal_konseling).toLocaleDateString('id-ID', { 
                                    weekday: 'long', 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric' 
                                })}</p>
                            </div>
                            <div>
                                <label class="block text-gray-600 font-medium">Waktu</label>
                                <p class="text-gray-900">${data.jam_mulai} - ${data.jam_selesai} WIB</p>
                            </div>
                            <div>
                                <label class="block text-gray-600 font-medium">Tempat</label>
                                <p class="text-gray-900">${data.tempat}</p>
                            </div>
                            <div>
                                <label class="block text-gray-600 font-medium">Guru BK</label>
                                <p class="text-gray-900">${data.guru_bk ? data.guru_bk.nama_lengkap : 'Tidak tersedia'}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Permohonan -->
                    ${data.permohonan_konseling ? `
                    <div>
                        <h5 class="font-medium text-gray-900 mb-3">Detail Permohonan</h5>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Konseling</label>
                                <p class="text-sm text-gray-900 capitalize">${data.permohonan_konseling.jenis_konseling}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ringkasan Masalah</label>
                                <p class="text-sm text-gray-900">${data.permohonan_konseling.ringkasan_masalah}</p>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Catatan & Dokumentasi -->
                    ${data.catatan || data.dokumentasi ? `
                        <div>
                            <h5 class="font-medium text-gray-900 mb-3">Hasil Konseling</h5>
                            <div class="space-y-3">
                                ${data.catatan ? `
                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                        <label class="block text-sm font-medium text-blue-800 mb-1">Catatan Konseling</label>
                                        <p class="text-sm text-blue-900">${data.catatan}</p>
                                    </div>
                                ` : ''}
                                ${data.dokumentasi ? `
                                    <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                        <label class="block text-sm font-medium text-green-800 mb-1">Dokumentasi</label>
                                        <p class="text-sm text-green-900">${data.dokumentasi}</p>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    ` : ''}
                    
                    <!-- Waktu -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Dibuat: ${new Date(data.created_at).toLocaleDateString('id-ID')}</span>
                            <span>Diperbarui: ${new Date(data.updated_at).toLocaleDateString('id-ID')}</span>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail riwayat');
        });
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endsection