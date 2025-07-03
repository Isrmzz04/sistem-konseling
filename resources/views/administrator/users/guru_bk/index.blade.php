@extends('layouts.base')

@section('title', 'Kelola Guru BK')
@section('page-title', 'Kelola Guru BK')

@section('main-content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Data Guru BK</h3>
            <a href="{{ route('administrator.users.guru_bk.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Tambah Guru BK
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                    @forelse($guruBKs as $guruBK)
                        <tr>
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
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="showDetail({{ $guruBK->id }})" 
                                            class="text-blue-600 hover:text-blue-900">Lihat</button>
                                    <a href="{{ route('administrator.users.guru_bk.edit', $guruBK) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form method="POST" action="{{ route('administrator.users.guru_bk.destroy', $guruBK) }}" 
                                          class="inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data Guru BK
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $guruBKs->links() }}
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black/60 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Guru BK</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="modalContent" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Content akan diisi via JavaScript -->
            </div>
            
            <div class="flex justify-end mt-6">
                <button onclick="closeModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Tutup
                </button>
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
            <div class="space-y-3">
                <h4 class="font-medium text-gray-900 border-b pb-2">Data Login</h4>
                <div>
                    <span class="text-sm font-medium text-gray-700">Username:</span>
                    <p class="text-sm text-gray-900">${guruBK.user.username}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <p class="text-sm">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${guruBK.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${guruBK.is_active ? 'Aktif' : 'Tidak Aktif'}
                        </span>
                    </p>
                </div>
            </div>
            <div class="space-y-3">
                <h4 class="font-medium text-gray-900 border-b pb-2">Data Pribadi</h4>
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
                    <p class="text-sm text-gray-900">${guruBK.tanggal_lahir}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">No. Telepon:</span>
                    <p class="text-sm text-gray-900">${guruBK.no_telp}</p>
                </div>
                <div class="col-span-2">
                    <span class="text-sm font-medium text-gray-700">Alamat:</span>
                    <p class="text-sm text-gray-900">${guruBK.alamat}</p>
                </div>
            </div>
        `;
        
        document.getElementById('detailModal').classList.remove('hidden');
    }
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}
</script>
@endsection