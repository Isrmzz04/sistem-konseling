{{-- resources/views/admin/users/siswa/index.blade.php --}}
@extends('layouts.base')

@section('title', 'Kelola Siswa')
@section('page-title', 'Kelola Siswa')

@section('main-content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Data Siswa</h3>
            <a href="{{ route('administrator.users.siswa.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Tambah Siswa
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
                            NISN
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Lengkap
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jurusan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Username
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Telp
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswas as $siswa)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $siswa->nisn }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $siswa->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $siswa->kelas }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $siswa->jurusan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $siswa->user->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $siswa->no_telp }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="showDetail({{ $siswa->id }})" 
                                            class="text-blue-600 hover:text-blue-900">Lihat</button>
                                    <a href="{{ route('administrator.users.siswa.edit', $siswa) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form method="POST" action="{{ route('administrator.users.siswa.destroy', $siswa) }}" 
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
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data Siswa
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $siswas->links() }}
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Siswa</h3>
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
    // Data Siswa (dalam implementasi nyata, ambil via AJAX)
    const siswas = @json($siswas->items());
    const siswa = siswas.find(s => s.id === id);
    
    if (siswa) {
        const modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `
            <div class="space-y-3">
                <h4 class="font-medium text-gray-900 border-b pb-2">Data Login</h4>
                <div>
                    <span class="text-sm font-medium text-gray-700">Username:</span>
                    <p class="text-sm text-gray-900">${siswa.user.username}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Role:</span>
                    <p class="text-sm text-gray-900">Siswa</p>
                </div>
            </div>
            <div class="space-y-3">
                <h4 class="font-medium text-gray-900 border-b pb-2">Data Siswa</h4>
                <div>
                    <span class="text-sm font-medium text-gray-700">NISN:</span>
                    <p class="text-sm text-gray-900">${siswa.nisn}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Nama Lengkap:</span>
                    <p class="text-sm text-gray-900">${siswa.nama_lengkap}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Kelas:</span>
                    <p class="text-sm text-gray-900">${siswa.kelas}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Jurusan:</span>
                    <p class="text-sm text-gray-900">${siswa.jurusan}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Jenis Kelamin:</span>
                    <p class="text-sm text-gray-900">${siswa.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Tempat Lahir:</span>
                    <p class="text-sm text-gray-900">${siswa.tempat_lahir}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Tanggal Lahir:</span>
                    <p class="text-sm text-gray-900">${siswa.tanggal_lahir}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">No. Telepon:</span>
                    <p class="text-sm text-gray-900">${siswa.no_telp}</p>
                </div>
                <div class="col-span-2">
                    <span class="text-sm font-medium text-gray-700">Alamat:</span>
                    <p class="text-sm text-gray-900">${siswa.alamat}</p>
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