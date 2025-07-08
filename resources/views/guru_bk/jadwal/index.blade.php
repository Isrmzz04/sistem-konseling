@extends('layouts.base')

@section('page-title', 'Jadwal Konseling')

@section('main-content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Jadwal Konseling</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola jadwal konseling siswa</p>
            </div>
            <a href="{{ route('guru_bk.jadwal.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>Buat Jadwal
            </a>
        </div>
    </div>

    @if($hasActiveBimbingan)
    <div class="p-6 border-b border-gray-200 bg-orange-50">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-orange-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-orange-800">Bimbingan Sedang Berlangsung</h3>
                <div class="mt-2 text-sm text-orange-700">
                    <p>Anda memiliki sesi bimbingan yang sedang berlangsung. Selesaikan sesi tersebut sebelum memulai bimbingan baru.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                    <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <div class="min-w-40">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" 
                       name="tanggal" 
                       value="{{ request('tanggal') }}"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                    <i class="fas fa-search mr-1"></i>Filter
                </button>
                <a href="{{ route('guru_bk.jadwal.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Siswa & Topik
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Jadwal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tempat
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
                @forelse($jadwalKonseling as $jadwal)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $jadwal->siswa->nama_lengkap }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $jadwal->siswa->kelas }} {{ $jadwal->siswa->jurusan }}
                            </div>
                            <div class="text-sm text-blue-600 mt-1">
                                {{ $jadwal->permohonanKonseling->topik_konseling }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ ucfirst($jadwal->permohonanKonseling->jenis_konseling) }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $jadwal->tanggal_konseling->format('d/m/Y') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $jadwal->tempat }}
                    </td>
                    <td class="px-6 py-4">
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
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="showDetail({{ $jadwal->id }})" 
                                    class="text-blue-600 hover:text-blue-900 p-1 rounded"
                                    title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            
                            @if($jadwal->status === 'dijadwalkan')
                                <a href="{{ route('guru_bk.jadwal.edit', $jadwal) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 p-1 rounded"
                                   title="Edit Jadwal">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                
                                @if($hasActiveBimbingan)
                                    <button disabled 
                                            class="bg-gray-300 text-gray-500 px-2 py-1 rounded text-xs font-medium cursor-not-allowed"
                                            title="Tidak dapat memulai bimbingan karena ada bimbingan lain yang sedang berlangsung">
                                        <i class="fas fa-play mr-1"></i>Mulai
                                    </button>
                                @else
                                    <button onclick="startBimbingan({{ $jadwal->id }})" 
                                            class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-medium"
                                            title="Mulai Bimbingan">
                                        <i class="fas fa-play mr-1"></i>Mulai
                                    </button>
                                @endif
                                
                                <button onclick="cancelBimbingan({{ $jadwal->id }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-medium"
                                        title="Batalkan">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                            @endif
                            
                            @if($jadwal->status === 'berlangsung')
                                <button onclick="showFinishModal({{ $jadwal->id }})" 
                                        class="bg-purple-500 hover:bg-purple-600 text-white px-2 py-1 rounded text-xs font-medium"
                                        title="Selesaikan Bimbingan">
                                    <i class="fas fa-check mr-1"></i>Selesai
                                </button>
                                
                                <button onclick="cancelBimbingan({{ $jadwal->id }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-medium"
                                        title="Batalkan">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                            @endif
                            
                            @if(in_array($jadwal->status, ['selesai', 'dibatalkan']))
                                <span class="text-xs text-gray-500 italic">
                                    {{ $jadwal->status === 'selesai' ? 'Selesai' : 'Dibatalkan' }}
                                </span>
                            @endif
                            
                            @if($jadwal->status !== 'selesai')
                                <form action="{{ route('guru_bk.jadwal.destroy', $jadwal) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 p-1 rounded"
                                            title="Hapus Jadwal">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium">Belum ada jadwal konseling</div>
                        <div class="mt-2">
                            <a href="{{ route('guru_bk.jadwal.create') }}" class="text-blue-600 hover:text-blue-800">
                                Buat jadwal konseling pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($jadwalKonseling->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $jadwalKonseling->links() }}
    </div>
    @endif
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

<div id="finishModal" class="fixed inset-0 bg-black/50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Selesaikan Bimbingan</h3>
                <p class="text-sm text-gray-600 mt-1">Upload dokumentasi foto dan catatan hasil bimbingan</p>
            </div>
            <form id="finishForm" enctype="multipart/form-data">
                <div class="p-6 h-[60vh] max-h-[60vh] overflow-y-auto">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Foto Dokumentasi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="dokumentasi_foto" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload foto</span>
                                        <input id="dokumentasi_foto" name="dokumentasi_foto" type="file" class="sr-only" accept="image/*" required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF sampai 5MB</p>
                            </div>
                        </div>
                        <div id="preview-container" class="hidden mt-4">
                            <img id="image-preview" class="max-w-full h-48 object-cover rounded-md mx-auto" alt="Preview">
                            <p id="file-name" class="text-sm text-gray-600 text-center mt-2"></p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Hasil Bimbingan</label>
                        <textarea name="catatan" 
                                  rows="4" 
                                  placeholder="Tuliskan hasil dan kesimpulan dari sesi bimbingan..."
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Catatan ini akan dapat dilihat oleh siswa</p>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeFinishModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm">
                        Batal
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-check mr-2"></i>Selesaikan Bimbingan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentJadwalId = null;

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
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Siswa</label>
                            <p class="text-sm text-gray-900">${data.siswa.nama_lengkap}</p>
                            <p class="text-xs text-gray-500">${data.siswa.kelas} ${data.siswa.jurusan}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColors[data.status]}">
                                ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Topik Konseling</label>
                        <p class="text-sm text-gray-900">${data.permohonan_konseling.topik_konseling}</p>
                        <p class="text-xs text-gray-500">${data.permohonan_konseling.jenis_konseling.charAt(0).toUpperCase() + data.permohonan_konseling.jenis_konseling.slice(1)}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <p class="text-sm text-gray-900">${new Date(data.tanggal_konseling).toLocaleDateString('id-ID')}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu</label>
                            <p class="text-sm text-gray-900">${data.jam_mulai} - ${data.jam_selesai}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tempat</label>
                        <p class="text-sm text-gray-900">${data.tempat}</p>
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

function showFinishModal(jadwalId) {
    currentJadwalId = jadwalId;
    document.getElementById('finishModal').classList.remove('hidden');
}

function closeFinishModal() {
    document.getElementById('finishModal').classList.add('hidden');
    currentJadwalId = null;
    document.getElementById('finishForm').reset();
    
    const previewContainer = document.getElementById('preview-container');
    if (previewContainer) {
        previewContainer.classList.add('hidden');
    }
}

function startBimbingan(jadwalId) {
    @if($hasActiveBimbingan)
        alert('Tidak dapat memulai bimbingan baru karena ada bimbingan lain yang sedang berlangsung.');
        return;
    @endif
    
    if (!confirm('Apakah Anda yakin ingin memulai sesi bimbingan ini?')) {
        return;
    }
    
    updateStatus(jadwalId, 'berlangsung');
}

function cancelBimbingan(jadwalId) {
    if (!confirm('Apakah Anda yakin ingin membatalkan jadwal bimbingan ini?')) {
        return;
    }
    
    updateStatus(jadwalId, 'dibatalkan');
}

function updateStatus(jadwalId, status) {
    if (status === 'dibatalkan') {
        if (!confirm('Yakin ingin membatalkan jadwal konseling ini?')) {
            return;
        }
    }
    
    const formData = new FormData();
    formData.append('status', status);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PATCH');
    
    fetch(`/guru_bk/jadwal/${jadwalId}/status`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
    });
}

document.getElementById('finishForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('status', 'selesai');
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PATCH');
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    fetch(`/guru_bk/jadwal/${currentJadwalId}/status`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeFinishModal();
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyelesaikan bimbingan');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

document.getElementById('dokumentasi_foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            this.value = '';
            return;
        }
        
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar.');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('file-name').textContent = file.name;
            document.getElementById('preview-container').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});

const dropZone = document.querySelector('.border-dashed');
if (dropZone) {
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-gray-400', 'bg-gray-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-gray-400', 'bg-gray-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-gray-400', 'bg-gray-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('dokumentasi_foto').files = files;
            document.getElementById('dokumentasi_foto').dispatchEvent(new Event('change'));
        }
    });
}

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('finishModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFinishModal();
    }
});
</script>
@endsection