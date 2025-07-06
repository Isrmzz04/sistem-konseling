@extends('layouts.base')

@section('page-title', 'Permohonan Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Permohonan Konseling</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola permohonan konseling Anda</p>
            </div>
            @if($hasActivePermohonan)
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-orange-600 bg-orange-50 px-3 py-1 rounded-full">
                        <i class="fas fa-hourglass-half mr-1"></i>Ada permohonan aktif
                    </span>
                    <button disabled 
                           class="bg-gray-300 text-gray-500 px-4 py-2 rounded-md text-sm font-medium cursor-not-allowed"
                           title="Anda sudah memiliki permohonan yang sedang diproses">
                        <i class="fas fa-plus mr-2"></i>Ajukan Permohonan
                    </button>
                </div>
            @else
                <a href="{{ route('siswa.permohonan.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Ajukan Permohonan
                </a>
            @endif
        </div>
    </div>

    <!-- Info Section untuk Permohonan Aktif -->
    @if($hasActivePermohonan)
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-orange-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-orange-800">Permohonan Sedang Diproses</h3>
                <div class="mt-2 text-sm text-orange-700">
                    <p>Anda sudah memiliki permohonan konseling yang sedang diproses. Silakan tunggu hingga permohonan selesai sebelum mengajukan permohonan baru.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistik Ringkas -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-xl font-bold text-yellow-600">{{ $permohonanKonseling->where('status', 'menunggu')->count() }}</div>
                <div class="text-xs text-gray-600">Menunggu</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-green-600">{{ $permohonanKonseling->where('status', 'disetujui')->count() }}</div>
                <div class="text-xs text-gray-600">Disetujui</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-red-600">{{ $permohonanKonseling->where('status', 'ditolak')->count() }}</div>
                <div class="text-xs text-gray-600">Ditolak</div>
            </div>
            <div class="text-center">
                <div class="text-xl font-bold text-blue-600">{{ $permohonanKonseling->where('status', 'selesai')->count() }}</div>
                <div class="text-xs text-gray-600">Selesai</div>
            </div>
        </div>
    </div>

    <!-- List Permohonan -->
    <div class="divide-y divide-gray-200">
        @forelse($permohonanKonseling as $permohonan)
        <div class="p-6 hover:bg-gray-50">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $permohonan->topik_konseling }}
                        </h3>
                        @php
                            $statusColors = [
                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                'disetujui' => 'bg-green-100 text-green-800',
                                'ditolak' => 'bg-red-100 text-red-800',
                                'selesai' => 'bg-blue-100 text-blue-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$permohonan->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($permohonan->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-3">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            <span>{{ $permohonan->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-tag mr-2 text-green-500"></i>
                            <span class="capitalize">{{ $permohonan->jenis_konseling }}</span>
                        </div>
                        @if($permohonan->guruBK)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-user-tie mr-2 text-purple-500"></i>
                            <span>{{ $permohonan->guruBK->nama_lengkap }}</span>
                        </div>
                        @endif
                    </div>
                    
                    @if($permohonan->diproses_at)
                    <div class="text-xs text-gray-500 mb-3">
                        <i class="fas fa-clock mr-1"></i>
                        Diproses {{ $permohonan->diproses_at->diffForHumans() }}
                    </div>
                    @endif
                    
                    <div class="text-sm text-gray-700 mb-4">
                        {{ Str::limit($permohonan->ringkasan_masalah, 150) }}
                    </div>
                    
                    @if($permohonan->catatan_guru_bk)
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                        <div class="text-xs text-blue-600 font-medium mb-1">Catatan dari Guru BK:</div>
                        <div class="text-sm text-blue-800">{{ $permohonan->catatan_guru_bk }}</div>
                    </div>
                    @endif
                    
                    <!-- Status specific messages -->
                    @if($permohonan->status === 'disetujui')
                    <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-sm text-green-700 font-medium">Permohonan disetujui! Silakan tunggu informasi jadwal konseling.</span>
                        </div>
                    </div>
                    @elseif($permohonan->status === 'ditolak')
                    <div class="bg-red-50 border border-red-200 rounded-md p-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                            <span class="text-sm text-red-700 font-medium">Permohonan ditolak. Periksa catatan atau ajukan permohonan baru.</span>
                        </div>
                    </div>
                    @elseif($permohonan->status === 'menunggu')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-hourglass-half text-yellow-500 mr-2"></i>
                            <span class="text-sm text-yellow-700">Permohonan sedang dalam proses review.</span>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center space-x-2 ml-4">
                    <button onclick="showDetail({{ $permohonan->id }})" 
                            class="text-blue-600 hover:text-blue-900 p-2 rounded-md hover:bg-blue-50"
                            title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    
                    @if($permohonan->status === 'menunggu')
                        <a href="{{ route('siswa.permohonan.edit', $permohonan) }}"
                           class="text-indigo-600 hover:text-indigo-900 p-2 rounded-md hover:bg-indigo-50"
                           title="Edit Permohonan">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <form action="{{ route('siswa.permohonan.destroy', $permohonan) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Yakin ingin membatalkan permohonan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50"
                                    title="Batalkan Permohonan">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
            <div class="text-xl font-medium mb-2">Belum ada permohonan konseling</div>
            <div class="text-sm mb-6">Mulai dengan mengajukan permohonan konseling pertama Anda</div>
            @if($hasActivePermohonan)
                <button disabled 
                       class="bg-gray-300 text-gray-500 px-4 py-2 rounded-md text-sm cursor-not-allowed"
                       title="Anda sudah memiliki permohonan yang sedang diproses">
                    <i class="fas fa-plus mr-2"></i>Ajukan Permohonan
                </button>
            @else
                <a href="{{ route('siswa.permohonan.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                    <i class="fas fa-plus mr-2"></i>Ajukan Permohonan
                </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($permohonanKonseling->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $permohonanKonseling->links() }}
    </div>
    @endif
</div>

<!-- Modal Detail Permohonan -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
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
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(permohonanId) {
    fetch(`/siswa/permohonan/${permohonanId}`)
        .then(response => response.json())
        .then(data => {
            const statusColors = {
                'menunggu': 'bg-yellow-100 text-yellow-800',
                'disetujui': 'bg-green-100 text-green-800',
                'ditolak': 'bg-red-100 text-red-800',
                'selesai': 'bg-blue-100 text-blue-800'
            };
            
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="text-center border-b border-gray-200 pb-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">${data.topik_konseling}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColors[data.status]}">
                            ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Ajuan</label>
                            <p class="text-sm text-gray-900">${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Konseling</label>
                            <p class="text-sm text-gray-900 capitalize">${data.jenis_konseling}</p>
                        </div>
                    </div>
                    
                    ${data.guru_bk ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Guru BK yang Dipilih</label>
                            <p class="text-sm text-gray-900">${data.guru_bk.nama_lengkap}</p>
                        </div>
                    ` : ''}
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ringkasan Masalah</label>
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">${data.ringkasan_masalah}</p>
                    </div>
                    
                    ${data.catatan_guru_bk ? `
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                            <label class="block text-sm font-medium text-blue-800 mb-1">Catatan dari Guru BK</label>
                            <p class="text-sm text-blue-900">${data.catatan_guru_bk}</p>
                        </div>
                    ` : ''}
                    
                    ${data.diproses_at ? `
                        <div class="border-t border-gray-200 pt-4">
                            <div class="text-xs text-gray-500">
                                Diproses: ${new Date(data.diproses_at).toLocaleDateString('id-ID')} ${new Date(data.diproses_at).toLocaleTimeString('id-ID')}
                            </div>
                        </div>
                    ` : ''}
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

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection