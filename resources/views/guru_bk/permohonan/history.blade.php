@extends('layouts.base')

@section('page-title', 'Riwayat Permohonan')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Riwayat Permohonan</h2>
                <p class="mt-1 text-sm text-gray-600">Lihat semua riwayat permohonan konseling</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">
                    Total: {{ $permohonanKonseling->total() }} permohonan
                </span>
            </div>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama siswa atau topik konseling..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                <a href="{{ route('guru_bk.permohonan.history') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if(request('search') || request('status') || request('bulan'))
        <div class="px-6 py-3 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>
                        Menampilkan {{ $permohonanKonseling->count() }} dari {{ $permohonanKonseling->total() }} hasil
                        @if(request('search'))
                            untuk pencarian "<strong>{{ request('search') }}</strong>"
                        @endif
                        @if(request('status'))
                            dengan status "<strong>{{ ucfirst(request('status')) }}</strong>"
                        @endif
                        @if(request('bulan'))
                            untuk periode "<strong>{{ \Carbon\Carbon::parse(request('bulan'))->format('F Y') }}</strong>"
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
                        Konseling
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jadwal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($permohonanKonseling as $index => $permohonan)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $permohonanKonseling->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $permohonan->siswa->nama_lengkap }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $permohonan->siswa->kelas }} {{ $permohonan->siswa->jurusan }}
                            </div>
                            <div class="text-xs text-gray-400">
                                NISN: {{ $permohonan->siswa->nisn }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-blue-600">
                                {{ $permohonan->topik_konseling }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ ucfirst($permohonan->jenis_konseling) }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1 line-clamp-2">
                                {{ Str::limit($permohonan->ringkasan_masalah, 80) }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $permohonan->created_at->format('d/m/Y') }}
                        <div class="text-xs text-gray-500">
                            {{ $permohonan->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'ditolak' => 'bg-red-100 text-red-800',
                                'selesai' => 'bg-blue-100 text-blue-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$permohonan->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($permohonan->status) }}
                        </span>
                        @if($permohonan->catatan_guru_bk)
                            <div class="text-xs text-gray-500 mt-1" title="{{ $permohonan->catatan_guru_bk }}">
                                <i class="fas fa-sticky-note mr-1"></i>Ada catatan
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($permohonan->status === 'selesai' && $permohonan->jadwalKonseling && $permohonan->jadwalKonseling->isNotEmpty())
                            @php $jadwal = $permohonan->jadwalKonseling->first(); @endphp
                            <div class="text-sm text-gray-900">
                                {{ $jadwal->tanggal_konseling->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}
                            </div>
                            @php
                                $jadwalStatusColors = [
                                    'dijadwalkan' => 'bg-yellow-100 text-yellow-800',
                                    'berlangsung' => 'bg-blue-100 text-blue-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'dibatalkan' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $jadwalStatusColors[$jadwal->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($jadwal->status) }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">
                                @if($permohonan->status === 'ditolak')
                                    Permohonan ditolak
                                @else
                                    -
                                @endif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="showDetail({{ $permohonan->id }})" 
                                    class="text-blue-600 hover:text-blue-900 p-1 rounded"
                                    title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            
                            @if($permohonan->status === 'selesai' && $permohonan->jadwalKonseling && $permohonan->jadwalKonseling->isNotEmpty())
                                <a href="{{ route('guru_bk.jadwal.show', $permohonan->jadwalKonseling->first()) }}" 
                                   class="text-green-600 hover:text-green-900 p-1 rounded"
                                   title="Lihat Jadwal">
                                    <i class="fas fa-calendar-alt text-sm"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-history text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium">
                            @if(request('search') || request('status') || request('bulan'))
                                Tidak ada riwayat yang sesuai dengan filter
                            @else
                                Belum ada riwayat permohonan
                            @endif
                        </div>
                        <div class="mt-2 text-sm">
                            @if(request('search') || request('status') || request('bulan'))
                                Coba ubah atau hapus filter untuk melihat lebih banyak data
                            @else
                                Riwayat akan muncul setelah ada permohonan yang diproses
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($permohonanKonseling->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan {{ $permohonanKonseling->firstItem() }} sampai {{ $permohonanKonseling->lastItem() }} dari {{ $permohonanKonseling->total() }} hasil
            </div>
            <div class="flex items-center space-x-2">
                <nav class="flex items-center space-x-1">
                    @if ($permohonanKonseling->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $permohonanKonseling->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    @foreach ($permohonanKonseling->getUrlRange(1, $permohonanKonseling->lastPage()) as $page => $url)
                        @if ($page == $permohonanKonseling->currentPage())
                            <span class="px-3 py-2 text-sm font-medium text-white bg-gray-600 rounded-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($permohonanKonseling->hasMorePages())
                        <a href="{{ $permohonanKonseling->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
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
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Permohonan</h3>
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
function showDetail(permohonanId) {
    fetch(`/siswa/permohonan/${permohonanId}`)
        .then(response => response.json())
        .then(data => {
            let statusBadge = '';
            const statusColors = {
                'ditolak': 'bg-red-100 text-red-800',
                'selesai': 'bg-blue-100 text-blue-800'
            };
            
            statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColors[data.status] || 'bg-gray-100 text-gray-800'}">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>`;
            
            let catatanSection = '';
            if (data.catatan_guru_bk) {
                catatanSection = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan Guru BK</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">${data.catatan_guru_bk}</p>
                    </div>
                `;
            }
            
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Siswa</label>
                            <p class="text-sm text-gray-900">${data.siswa.nama_lengkap}</p>
                            <p class="text-xs text-gray-500">${data.siswa.kelas} ${data.siswa.jurusan}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NISN</label>
                            <p class="text-sm text-gray-900">${data.siswa.nisn}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Konseling</label>
                            <p class="text-sm text-gray-900">${data.jenis_konseling.charAt(0).toUpperCase() + data.jenis_konseling.slice(1)}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div>${statusBadge}</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Ajuan</label>
                            <p class="text-sm text-gray-900">${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diproses Pada</label>
                            <p class="text-sm text-gray-900">${data.diproses_at ? new Date(data.diproses_at).toLocaleDateString('id-ID') : '-'}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Topik Konseling</label>
                        <p class="text-sm text-gray-900">${data.topik_konseling}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ringkasan Masalah</label>
                        <p class="text-sm text-gray-900">${data.ringkasan_masalah}</p>
                    </div>
                    
                    ${catatanSection}
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail permohonan');
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