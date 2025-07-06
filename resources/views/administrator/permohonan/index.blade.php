@extends('layouts.base')

@section('title', 'Kelola Permohonan Konseling')
@section('page-title', 'Kelola Permohonan Konseling')

@section('main-content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Data Permohonan Konseling</h3>
            <a href="{{ route('administrator.konseling.permohonan.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Tambah Permohonan
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

        <!-- Filter Status -->
        <div class="mb-4 flex space-x-2">
            <select id="statusFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="menunggu">Menunggu</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                <option value="selesai">Selesai</option>
            </select>
            <select id="jenisFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Jenis</option>
                <option value="pribadi">Pribadi</option>
                <option value="sosial">Sosial</option>
                <option value="akademik">Akademik</option>
                <option value="karir">Karir</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Konseling
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Topik
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
                    @forelse($permohonanKonseling as $permohonan)
                        <tr data-status="{{ $permohonan->status }}" data-jenis="{{ $permohonan->jenis_konseling }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $permohonan->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ $permohonan->siswa->nama_lengkap }}</div>
                                    <div class="text-gray-500">{{ $permohonan->siswa->kelas }} - {{ $permohonan->siswa->jurusan }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $permohonan->jenis_konseling === 'pribadi' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $permohonan->jenis_konseling === 'sosial' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $permohonan->jenis_konseling === 'akademik' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $permohonan->jenis_konseling === 'karir' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $permohonan->jenis_konseling === 'lainnya' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($permohonan->jenis_konseling) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                {{ $permohonan->topik_konseling }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $permohonan->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $permohonan->status === 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $permohonan->status === 'ditolak' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $permohonan->status === 'selesai' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($permohonan->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="showDetail({{ $permohonan->id }})" 
                                            class="text-blue-600 hover:text-blue-900">Lihat</button>
                                    
                                    @if($permohonan->status === 'menunggu')
                                        <button onclick="updateStatus({{ $permohonan->id }}, 'disetujui')" 
                                                class="text-green-600 hover:text-green-900">Setujui</button>
                                        <button onclick="updateStatus({{ $permohonan->id }}, 'ditolak')" 
                                                class="text-red-600 hover:text-red-900">Tolak</button>
                                    @endif
                                    
                                    <a href="{{ route('administrator.konseling.permohonan.edit', $permohonan) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    
                                    <form method="POST" action="{{ route('administrator.konseling.permohonan.destroy', $permohonan) }}" 
                                          class="inline" onsubmit="return confirm('Yakin ingin menghapus permohonan ini?')">
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
                                Tidak ada permohonan konseling
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $permohonanKonseling->links() }}
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Permohonan Konseling</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="modalContent">
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

<!-- Modal Update Status -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status Permohonan</h3>
            
            <form id="statusForm">
                <input type="hidden" id="permohonanId">
                <input type="hidden" id="newStatus">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="catatan" rows="3" 
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Berikan catatan untuk siswa..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('jenisFilter').addEventListener('change', filterTable);

function filterTable() {
    const statusFilter = document.getElementById('statusFilter').value;
    const jenisFilter = document.getElementById('jenisFilter').value;
    const rows = document.querySelectorAll('tbody tr[data-status]');
    
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        const jenis = row.getAttribute('data-jenis');
        
        const statusMatch = !statusFilter || status === statusFilter;
        const jenisMatch = !jenisFilter || jenis === jenisFilter;
        
        if (statusMatch && jenisMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function showDetail(id) {
    fetch(`/administrator/konseling/permohonan/${id}`)
        .then(response => response.json())
        .then(data => {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Siswa:</span>
                            <p class="text-sm text-gray-900">${data.siswa.nama_lengkap}</p>
                            <p class="text-xs text-gray-500">${data.siswa.kelas} - ${data.siswa.jurusan}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Tanggal Pengajuan:</span>
                            <p class="text-sm text-gray-900">${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Jenis Konseling:</span>
                            <p class="text-sm text-gray-900">${data.jenis_konseling}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700">Status:</span>
                            <p class="text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(data.status)}">
                                    ${data.status}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-700">Topik Konseling:</span>
                        <p class="text-sm text-gray-900">${data.topik_konseling}</p>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-700">Ringkasan Masalah:</span>
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">${data.ringkasan_masalah}</p>
                    </div>
                    
                    ${data.catatan_admin ? `
                    <div>
                        <span class="text-sm font-medium text-gray-700">Catatan Admin:</span>
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">${data.catatan_admin}</p>
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('detailModal').classList.remove('hidden');
        });
}

function getStatusClass(status) {
    const classes = {
        'menunggu': 'bg-yellow-100 text-yellow-800',
        'disetujui': 'bg-green-100 text-green-800',
        'ditolak': 'bg-red-100 text-red-800',
        'selesai': 'bg-blue-100 text-blue-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function updateStatus(id, status) {
    document.getElementById('permohonanId').value = id;
    document.getElementById('newStatus').value = status;
    document.getElementById('statusModal').classList.remove('hidden');
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('permohonanId').value;
    const status = document.getElementById('newStatus').value;
    const catatan = document.getElementById('catatan').value;
    
    fetch(`/administrator/konseling/permohonan/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: status,
            catatan: catatan
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Terjadi kesalahan');
        }
    });
});

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('catatan').value = '';
}
</script>
@endsection