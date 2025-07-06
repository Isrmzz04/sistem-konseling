@extends('layouts.base')

@section('page-title', 'Dashboard Guru BK')

@section('main-content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->guruBK->nama_lengkap ?? auth()->user()->username }}!</h1>
                <p class="text-gray-600 mt-1">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-900">{{ now()->format('H:i') }}</div>
                <div class="text-sm text-gray-500">WIB</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Permohonan Pending -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class='bx bx-time text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Permohonan Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['permohonan_pending'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('guru_bk.permohonan.index') }}" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">
                    Proses Sekarang →
                </a>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class='bx bx-calendar text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Jadwal Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['jadwal_hari_ini'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('guru_bk.jadwal.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Lihat Jadwal →
                </a>
            </div>
        </div>

        <!-- Selesai Tanpa Laporan -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class='bx bx-file-blank text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Belum Ada Laporan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $statistics['selesai_tanpa_laporan'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('guru_bk.laporan.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">
                    Buat Laporan →
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Permohonan Yang Perlu Diproses -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Permohonan Pending</h3>
                    <i class='bx bx-list-ul text-gray-400'></i>
                </div>
            </div>
            <div class="p-6">
                @forelse($permohonanPending as $permohonan)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $permohonan->siswa->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ $permohonan->siswa->kelas }} - {{ ucfirst($permohonan->jenis_konseling) }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $permohonan->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="showPermohonanDetail({{ $permohonan->id }})" 
                                class="text-blue-600 hover:text-blue-800 p-1 rounded"
                                title="Lihat Detail">
                            <i class='bx bx-show text-sm'></i>
                        </button>
                        <a href="{{ route('guru_bk.permohonan.index') }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs font-medium">
                            Proses
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <i class='bx bx-check-circle text-3xl text-green-300 mb-2'></i>
                    <p class="text-sm text-gray-500">Tidak ada permohonan pending</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Jadwal Hari Ini</h3>
                    <i class='bx bx-calendar-check text-gray-400'></i>
                </div>
            </div>
            <div class="p-6">
                @forelse($jadwalHariIni as $jadwal)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $jadwal->siswa->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ $jadwal->tempat }}</p>
                        <p class="text-xs text-gray-400">{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        @php
                            $statusColors = [
                                'dijadwalkan' => 'bg-blue-100 text-blue-800',
                                'berlangsung' => 'bg-yellow-100 text-yellow-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$jadwal->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($jadwal->status) }}
                        </span>
                        @if($jadwal->status === 'dijadwalkan')
                        <button onclick="updateJadwalStatus({{ $jadwal->id }}, 'berlangsung')" 
                                class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                            Mulai
                        </button>
                        @elseif($jadwal->status === 'berlangsung')
                        <button onclick="updateJadwalStatus({{ $jadwal->id }}, 'selesai')" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium">
                            Selesai
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <i class='bx bx-calendar-x text-3xl text-gray-300 mb-2'></i>
                    <p class="text-sm text-gray-500">Tidak ada jadwal hari ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Permohonan -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full" style="height: 60vh; max-height: 60vh;">
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Detail Permohonan</h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 p-2 rounded-full transition-all duration-200" title="Tutup">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
            </div>
            <div id="detailContent" class="p-6 overflow-y-auto" style="height: calc(60vh - 80px);">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showPermohonanDetail(permohonanId) {
    fetch(`/guru_bk/permohonan/${permohonanId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Informasi Siswa</h4>
                        <p class="text-sm"><span class="font-medium">Nama:</span> ${data.siswa.nama_lengkap}</p>
                        <p class="text-sm"><span class="font-medium">Kelas:</span> ${data.siswa.kelas} ${data.siswa.jurusan}</p>
                        <p class="text-sm"><span class="font-medium">NISN:</span> ${data.siswa.nisn}</p>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Detail Permohonan</h4>
                        <p class="text-sm"><span class="font-medium">Jenis:</span> ${data.jenis_konseling.charAt(0).toUpperCase() + data.jenis_konseling.slice(1)}</p>
                        <p class="text-sm"><span class="font-medium">Topik:</span> ${data.topik_konseling}</p>
                        <p class="text-sm"><span class="font-medium">Tanggal:</span> ${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Ringkasan Masalah</h4>
                        <p class="text-sm text-gray-900 whitespace-pre-line">${data.ringkasan_masalah}</p>
                    </div>
                </div>
            `;
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat detail permohonan');
        });
}

function updateJadwalStatus(jadwalId, newStatus) {
    if (!confirm(`Apakah Anda yakin ingin mengubah status menjadi "${newStatus}"?`)) {
        return;
    }

    const formData = new FormData();
    formData.append('status', newStatus);
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
        alert('Terjadi kesalahan saat mengubah status');
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

<!-- Box Icons CDN -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
@endsection