@extends('layouts.base')

@section('page-title', 'Monitoring Permohonan Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Monitoring Permohonan Konseling</h2>
                <p class="mt-1 text-sm text-gray-600">Monitoring semua permohonan konseling dari seluruh siswa</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-500">
                    Total: {{ $permohonanKonseling->total() }} permohonan
                </span>
            </div>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-gray-900">{{ $statistik['total'] }}</div>
                <div class="text-sm text-gray-600">Total Permohonan</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-yellow-600">{{ $statistik['menunggu'] }}</div>
                <div class="text-sm text-gray-600">Menunggu</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-green-600">{{ $statistik['disetujui'] }}</div>
                <div class="text-sm text-gray-600">Disetujui</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-red-600">{{ $statistik['ditolak'] }}</div>
                <div class="text-sm text-gray-600">Ditolak</div>
            </div>
            <div class="bg-white p-4 rounded-lg border">
                <div class="text-2xl font-bold text-blue-600">{{ $statistik['selesai'] }}</div>
                <div class="text-sm text-gray-600">Selesai</div>
            </div>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama siswa, NISN, topik..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Konseling</label>
                <select name="jenis_konseling" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="pribadi" {{ request('jenis_konseling') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                    <option value="sosial" {{ request('jenis_konseling') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                    <option value="akademik" {{ request('jenis_konseling') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="karir" {{ request('jenis_konseling') == 'karir' ? 'selected' : '' }}>Karir</option>
                    <option value="lainnya" {{ request('jenis_konseling') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm flex-1">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('administrator.konseling.permohonan') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if(request('search') || request('status') || request('jenis_konseling') || request('guru_bk_id') || request('kelas'))
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
                        @if(request('jenis_konseling'))
                            jenis "<strong>{{ ucfirst(request('jenis_konseling')) }}</strong>"
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
                        Konseling
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tanggal Ajuan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
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
                        <div class="text-sm text-gray-900">
                            {{ $permohonan->guruBK->nama_lengkap }}
                        </div>
                        <div class="text-xs text-gray-500">
                            NIP: {{ $permohonan->guruBK->nip }}
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
                                {{ Str::limit($permohonan->ringkasan_masalah, 100) }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $permohonan->created_at->format('d/m/Y H:i') }}
                        <div class="text-xs text-gray-500">
                            {{ $permohonan->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
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
                        @if($permohonan->diproses_at)
                            <div class="text-xs text-gray-500 mt-1">
                                Diproses: {{ $permohonan->diproses_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="showDetail({{ $permohonan->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-md text-xs font-medium transition-colors duration-200"
                                    title="Lihat Detail">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium">Tidak ada permohonan konseling</div>
                        <div class="mt-2 text-sm">
                            @if(request('search') || request('status') || request('jenis_konseling') || request('guru_bk_id') || request('kelas'))
                                Tidak ada permohonan yang sesuai dengan filter yang dipilih
                            @else
                                Belum ada permohonan konseling yang masuk
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
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full" style="height: 60vh; max-height: 60vh;">
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Permohonan Konseling</h3>
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
function showDetail(permohonanId) {
    fetch(`/administrator/konseling/permohonan/${permohonanId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data)
            
            if (!data.siswa || !data.guru_bk) {
                throw new Error('Data tidak lengkap');
            }

            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-6">
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
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <p class="text-sm text-gray-900">${data.siswa.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Guru BK -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Guru BK yang Dipilih</h4>
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
                    
                    <!-- Detail Permohonan -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Detail Permohonan</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Konseling</label>
                                <p class="text-sm text-gray-900">${data.jenis_konseling.charAt(0).toUpperCase() + data.jenis_konseling.slice(1)}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Ajuan</label>
                                <p class="text-sm text-gray-900">${new Date(data.created_at).toLocaleDateString('id-ID', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Topik Konseling</label>
                            <p class="text-sm text-gray-900">${data.topik_konseling}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ringkasan Masalah</label>
                            <p class="text-sm text-gray-900 whitespace-pre-line">${data.ringkasan_masalah}</p>
                        </div>
                    </div>

                    <!-- Status & Proses -->
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Status & Pemrosesan</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                    data.status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' :
                                    data.status === 'disetujui' ? 'bg-green-100 text-green-800' :
                                    data.status === 'ditolak' ? 'bg-red-100 text-red-800' :
                                    data.status === 'selesai' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'
                                }">
                                    ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                                </span>
                            </div>
                            ${data.diproses_at ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Diproses</label>
                                <p class="text-sm text-gray-900">${new Date(data.diproses_at).toLocaleDateString('id-ID', { 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</p>
                            </div>
                            ` : ''}
                        </div>
                        ${data.catatan_guru_bk ? `
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Catatan Guru BK</label>
                            <p class="text-sm text-gray-900 whitespace-pre-line">${data.catatan_guru_bk}</p>
                        </div>
                        ` : ''}
                    </div>

                    <!-- Info Jadwal jika ada -->
                    ${data.jadwal_konseling && data.jadwal_konseling.length > 0 ? `
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-3">Jadwal Konseling</h4>
                        ${data.jadwal_konseling.map(jadwal => `
                            <div class="border border-purple-200 rounded p-3 mb-2 bg-white">
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <span class="font-medium">Tanggal:</span> ${new Date(jadwal.tanggal_konseling).toLocaleDateString('id-ID')}
                                    </div>
                                    <div>
                                        <span class="font-medium">Waktu:</span> ${jadwal.jam_mulai} - ${jadwal.jam_selesai}
                                    </div>
                                    <div>
                                        <span class="font-medium">Tempat:</span> ${jadwal.tempat}
                                    </div>
                                    <div>
                                        <span class="font-medium">Status:</span> 
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${
                                            jadwal.status === 'dijadwalkan' ? 'bg-blue-100 text-blue-800' :
                                            jadwal.status === 'berlangsung' ? 'bg-yellow-100 text-yellow-800' :
                                            jadwal.status === 'selesai' ? 'bg-green-100 text-green-800' :
                                            jadwal.status === 'dibatalkan' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'
                                        }">
                                            ${jadwal.status.charAt(0).toUpperCase() + jadwal.status.slice(1)}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail permohonan: ' + error.message);
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