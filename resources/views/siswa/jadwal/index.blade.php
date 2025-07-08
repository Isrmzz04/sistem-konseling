@extends('layouts.base')

@section('page-title', 'Jadwal Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Jadwal Konseling</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat jadwal konseling yang sudah ditetapkan</p>
            </div>
            <a href="{{ route('siswa.permohonan.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Ajukan Konseling
            </a>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-blue-50">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $jadwalKonseling->where('status', 'dijadwalkan')->count() }}</div>
                <div class="text-sm text-gray-600">Dijadwalkan</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $jadwalKonseling->where('status', 'berlangsung')->count() }}</div>
                <div class="text-sm text-gray-600">Berlangsung</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-600">{{ $jadwalKonseling->count() }}</div>
                <div class="text-sm text-gray-600">Total Jadwal</div>
            </div>
        </div>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($jadwalKonseling as $jadwal)
        <div class="p-6 hover:bg-gray-50">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $jadwal->permohonanKonseling->topik_konseling }}
                        </h3>
                        @php
                            $statusColors = [
                                'dijadwalkan' => 'bg-yellow-100 text-yellow-800',
                                'berlangsung' => 'bg-blue-100 text-blue-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$jadwal->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($jadwal->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            <span>{{ $jadwal->tanggal_konseling->format('l, d F Y') }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock mr-2 text-green-500"></i>
                            <span>{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }} WIB</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                            <span>{{ $jadwal->tempat }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex items-center text-sm text-gray-600">
                        <i class="fas fa-user-tie mr-2 text-purple-500"></i>
                        <span>{{ $jadwal->guruBK->nama_lengkap }}</span>
                        <span class="mx-2">•</span>
                        <span class="capitalize">{{ $jadwal->permohonanKonseling->jenis_konseling }}</span>
                    </div>
                    
                    @if($jadwal->catatan)
                    <div class="mt-3 p-3 bg-gray-50 rounded-md">
                        <div class="text-xs text-gray-500 mb-1">Catatan:</div>
                        <div class="text-sm text-gray-700">{{ $jadwal->catatan }}</div>
                    </div>
                    @endif

                    @if($jadwal->status === 'dijadwalkan' && $jadwal->tanggal_konseling->isToday())
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                            <span class="text-sm text-yellow-700 font-medium">Konseling hari ini! Pastikan Anda hadir tepat waktu.</span>
                        </div>
                    </div>
                    @elseif($jadwal->status === 'dijadwalkan' && $jadwal->tanggal_konseling->isTomorrow())
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-sm text-blue-700">Konseling besok. Siapkan diri Anda dengan baik.</span>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center space-x-2 ml-4">
                    <button onclick="showDetail({{ $jadwal->id }})" 
                            class="text-blue-600 hover:text-blue-900 p-2 rounded-md hover:bg-blue-50"
                            title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    
                    @if($jadwal->status === 'dijadwalkan' && $jadwal->tanggal_konseling->isPast())
                    <div class="text-orange-600 p-2" title="Jadwal terlewat">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-calendar-times text-6xl mb-4 text-gray-300"></i>
            <div class="text-xl font-medium mb-2">Belum ada jadwal konseling</div>
            <div class="text-sm mb-4">Jadwal akan muncul setelah permohonan konseling Anda disetujui</div>
            <a href="{{ route('siswa.permohonan.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                <i class="fas fa-plus mr-2"></i>Ajukan Konseling
            </a>
        </div>
        @endforelse
    </div>
</div>

<div id="detailModal" class="fixed inset-0 bg-black/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Jadwal Konseling</h3>
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

<script>
function showDetail(jadwalId) {
    fetch(`/guru_bk/jadwal/${jadwalId}`)
        .then(response => response.json())
        .then(data => {
            const statusColors = {
                'dijadwalkan': 'bg-yellow-100 text-yellow-800',
                'berlangsung': 'bg-blue-100 text-blue-800',
                'selesai': 'bg-green-100 text-green-800',
                'dibatalkan': 'bg-red-100 text-red-800'
            };
            
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="text-center border-b border-gray-200 pb-4">
                        <h4 class="text-lg font-medium text-gray-900">${data.permohonan_konseling.topik_konseling}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColors[data.status]} mt-2">
                            ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="text-sm text-gray-900">${new Date(data.tanggal_konseling).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu</label>
                            <p class="text-sm text-gray-900">${data.jam_mulai} - ${data.jam_selesai} WIB</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tempat</label>
                        <p class="text-sm text-gray-900">${data.tempat}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Guru BK</label>
                        <p class="text-sm text-gray-900">${data.guru_bk.nama_lengkap}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Konseling</label>
                        <p class="text-sm text-gray-900">${data.permohonan_konseling.jenis_konseling.charAt(0).toUpperCase() + data.permohonan_konseling.jenis_konseling.slice(1)}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ringkasan Masalah</label>
                        <p class="text-sm text-gray-900">${data.permohonan_konseling.ringkasan_masalah}</p>
                    </div>
                    
                    ${data.catatan ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <p class="text-sm text-gray-900">${data.catatan}</p>
                        </div>
                    ` : ''}
                    
                    ${data.dokumentasi ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dokumentasi</label>
                            <p class="text-sm text-gray-900">${data.dokumentasi}</p>
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

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection