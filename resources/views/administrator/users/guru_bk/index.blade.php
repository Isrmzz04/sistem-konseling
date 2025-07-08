@extends('layouts.base')

@section('title', 'Kelola Guru BK')
@section('page-title', 'Kelola Guru BK')

@section('main-content')
<div class="">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class='bx bx-user-voice text-blue-600'></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Data Guru BK</h3>
                        <p class="text-sm text-gray-500">Kelola data Guru Bimbingan Konseling</p>
                    </div>
                </div>
                <a href="{{ route('administrator.users.guru_bk.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                    <i class='bx bx-plus mr-2'></i>
                    Tambah Guru BK
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <form method="GET" action="{{ route('administrator.users.guru_bk') }}" class="md:col-span-2">
                    <div class="flex space-x-3">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-search text-gray-400'></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Cari berdasarkan NIP atau Nama Lengkap..." 
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            </div>
                        </div>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                            <i class='bx bx-search mr-2'></i>
                            Cari
                        </button>
                        @if(request('search') || request('status'))
                        <a href="{{ route('administrator.users.guru_bk') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                            <i class='bx bx-x mr-2'></i>
                            Reset
                        </a>
                        @endif
                    </div>
                </form>

                <form method="GET" action="{{ route('administrator.users.guru_bk') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="status" 
                            onchange="this.form.submit()"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ request('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </form>
            </div>
        </div>

        @if(request('search') || request('status'))
            <div class="px-6 py-3 bg-blue-50 border-b border-blue-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-blue-700">
                        <i class='bx bx-info-circle mr-2'></i>
                        <span>
                            Menampilkan {{ $guruBKs->count() }} dari {{ $guruBKs->total() }} hasil
                            @if(request('search'))
                                untuk pencarian "<strong>{{ request('search') }}</strong>"
                            @endif
                            @if(request('status'))
                                dengan status "<strong>{{ request('status') === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}</strong>"
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Lengkap
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Username
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Telp
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
                    @forelse($guruBKs as $index => $guruBK)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $guruBKs->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $guruBK->nip }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $guruBK->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $guruBK->user->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $guruBK->no_telp }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($guruBK->is_active)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                        <i class='bx bx-check mr-1'></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                        <i class='bx bx-x mr-1'></i>
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="showDetail({{ $guruBK->id }})" 
                                            class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-md flex items-center justify-center transition-colors"
                                            title="Lihat Detail">
                                        <i class='bx bx-show text-sm'></i>
                                    </button>
                                    <a href="{{ route('administrator.users.guru_bk.edit', $guruBK) }}" 
                                       class="w-8 h-8 bg-green-100 hover:bg-green-200 text-green-600 rounded-md flex items-center justify-center transition-colors"
                                       title="Edit">
                                        <i class='bx bx-edit text-sm'></i>
                                    </a>
                                    <form method="POST" action="{{ route('administrator.users.guru_bk.destroy', $guruBK) }}" 
                                          class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-md flex items-center justify-center transition-colors"
                                                title="Hapus">
                                            <i class='bx bx-trash text-sm'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class='bx bx-user-voice text-3xl text-gray-400'></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        @if(request('search') || request('status'))
                                            Tidak ada data yang ditemukan
                                        @else
                                            Belum ada data Guru BK
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-500 mb-4">
                                        @if(request('search') || request('status'))
                                            Coba ubah kriteria pencarian atau filter Anda
                                        @else
                                            Mulai dengan menambahkan data Guru BK pertama
                                        @endif
                                    </p>
                                    @if(!request('search') && !request('status'))
                                        <a href="{{ route('administrator.users.guru_bk.create') }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                                            <i class='bx bx-plus mr-2'></i>
                                            Tambah Guru BK
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guruBKs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan {{ $guruBKs->firstItem() }} sampai {{ $guruBKs->lastItem() }} dari {{ $guruBKs->total() }} hasil
                    </div>
                    <div class="flex items-center space-x-2">
                        <nav class="flex items-center space-x-1">
                            @if ($guruBKs->onFirstPage())
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                    <i class='bx bx-chevron-left'></i>
                                </span>
                            @else
                                <a href="{{ $guruBKs->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    <i class='bx bx-chevron-left'></i>
                                </a>
                            @endif

                            @foreach ($guruBKs->getUrlRange(1, $guruBKs->lastPage()) as $page => $url)
                                @if ($page == $guruBKs->currentPage())
                                    <span class="px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($guruBKs->hasMorePages())
                                <a href="{{ $guruBKs->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    <i class='bx bx-chevron-right'></i>
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                    <i class='bx bx-chevron-right'></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div id="detailModal" class="fixed inset-0 bg-black/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-user-voice text-blue-600'></i>
                        </div>
                        <h3 class="font-medium text-gray-800">Detail Guru BK</h3>
                    </div>
                    <button onclick="closeModal()" 
                            class="w-8 h-8 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md flex items-center justify-center transition-colors">
                        <i class='bx bx-x text-lg'></i>
                    </button>
                </div>
            </div>
            
            <div id="modalContent" class="p-6 overflow-y-auto max-h-96">
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                <div class="flex justify-end">
                    <button onclick="closeModal()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDetail(id) {
    const guruBKs = @json($guruBKs->items());
    const guruBK = guruBKs.find(g => g.id === id);
    
    if (guruBK) {
        const modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center space-x-2 mb-3">
                            <i class='bx bx-user text-blue-600'></i>
                            <h4 class="font-medium text-gray-900">Data Login</h4>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Username:</span>
                                <p class="text-sm text-gray-900">${guruBK.user.username}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Status:</span>
                                <p class="text-sm">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full ${guruBK.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        <i class='bx ${guruBK.is_active ? 'bx-check' : 'bx-x'} mr-1'></i>
                                        ${guruBK.is_active ? 'Aktif' : 'Tidak Aktif'}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center space-x-2 mb-3">
                            <i class='bx bx-id-card text-green-600'></i>
                            <h4 class="font-medium text-gray-900">Data Pribadi</h4>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm font-medium text-gray-700">NIP:</span>
                                <p class="text-sm text-gray-900">${guruBK.nip}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Nama Lengkap:</span>
                                <p class="text-sm text-gray-900">${guruBK.nama_lengkap}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Jenis Kelamin:</span>
                                <p class="text-sm text-gray-900">${guruBK.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Tempat Lahir:</span>
                                <p class="text-sm text-gray-900">${guruBK.tempat_lahir}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Tanggal Lahir:</span>
                                <p class="text-sm text-gray-900">${new Date(guruBK.tanggal_lahir).toLocaleDateString('id-ID')}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">No. Telepon:</span>
                                <p class="text-sm text-gray-900">${guruBK.no_telp}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:col-span-2">
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <div class="flex items-center space-x-2 mb-3">
                            <i class='bx bx-map text-orange-600'></i>
                            <h4 class="font-medium text-gray-900">Alamat</h4>
                        </div>
                        <p class="text-sm text-gray-900">${guruBK.alamat}</p>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('detailModal').classList.remove('hidden');
    }
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