@extends('layouts.base')

@section('page-title', 'Dashboard')

@section('main-content')
<div class="">
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class='bx bx-user-voice text-white text-xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">
                            Selamat Datang, {{ auth()->user()->guruBK->nama_lengkap ?? auth()->user()->username }}!
                        </h1>
                        <p class="text-gray-600 mt-1 flex items-center">
                            <i class='bx bx-calendar mr-2 text-blue-500'></i>
                            {{ now()->format('l, d F Y') }}
                        </p>
                    </div>
                </div>
                <div class="text-right bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-semibold text-gray-800">{{ now()->format('H:i') }}</div>
                    <div class="text-sm text-gray-500">WIB</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:border-yellow-200 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-time text-yellow-600'></i>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['permohonan_pending'] }}</p>
                    <p class="text-sm text-yellow-600 font-medium">Permohonan Pending</p>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('guru_bk.permohonan.index') }}" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium flex items-center group">
                    Proses Sekarang
                    <i class='bx bx-right-arrow-alt ml-1 group-hover:translate-x-1 transition-transform'></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:border-blue-200 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-calendar text-blue-600'></i>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['jadwal_hari_ini'] }}</p>
                    <p class="text-sm text-blue-600 font-medium">Jadwal Hari Ini</p>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('guru_bk.jadwal.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center group">
                    Lihat Jadwal
                    <i class='bx bx-right-arrow-alt ml-1 group-hover:translate-x-1 transition-transform'></i>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 hover:border-red-200 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class='bx bx-file-blank text-red-600'></i>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-800">{{ $statistics['selesai_tanpa_laporan'] }}</p>
                    <p class="text-sm text-red-600 font-medium">Belum Ada Laporan</p>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('guru_bk.laporan.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center group">
                    Buat Laporan
                    <i class='bx bx-right-arrow-alt ml-1 group-hover:translate-x-1 transition-transform'></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-list-ul text-yellow-600'></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Permohonan Pending</h3>
                            <p class="text-sm text-gray-500">Memerlukan perhatian</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-5">
                @forelse($permohonanPending as $permohonan)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50 rounded-lg px-2 -mx-2 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-yellow-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-user text-yellow-600 text-xs'></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $permohonan->siswa->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $permohonan->siswa->kelas }} • {{ ucfirst($permohonan->jenis_konseling) }}</p>
                            <p class="text-xs text-gray-400 mt-1 flex items-center">
                                <i class='bx bx-time-five mr-1'></i>
                                {{ $permohonan->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class='bx bx-check-circle text-2xl text-green-600'></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Tidak ada permohonan pending</p>
                    <p class="text-xs text-gray-400 mt-1">Semua permohonan telah diproses</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-calendar-check text-blue-600'></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Jadwal Hari Ini</h3>
                            <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-5">
                @forelse($jadwalHariIni as $jadwal)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50 rounded-lg px-2 -mx-2 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-user-voice text-blue-600 text-xs'></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $jadwal->siswa->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500 flex items-center">
                                <i class='bx bx-map-pin mr-1'></i>
                                {{ $jadwal->tempat }}
                            </p>
                            <p class="text-xs text-gray-400 flex items-center">
                                <i class='bx bx-time mr-1'></i>
                                {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-2">
                        @php
                            $statusConfig = [
                                'dijadwalkan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'bx-calendar'],
                                'berlangsung' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'bx-play-circle'],
                                'selesai' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'bx-check-circle'],
                                'dibatalkan' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'bx-x-circle']
                            ];
                            $config = $statusConfig[$jadwal->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'bx-info-circle'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                            <i class='bx {{ $config['icon'] }} mr-1'></i>
                            {{ ucfirst($jadwal->status) }}
                        </span>
                        <div class="flex space-x-1">
                            @if($jadwal->status === 'dijadwalkan')
                            <button onclick="updateJadwalStatus({{ $jadwal->id }}, 'berlangsung')" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded-md text-xs font-medium transition-colors">
                                <i class='bx bx-play mr-1'></i>Mulai
                            </button>
                            @elseif($jadwal->status === 'berlangsung')
                            <button onclick="updateJadwalStatus({{ $jadwal->id }}, 'selesai')" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded-md text-xs font-medium transition-colors">
                                <i class='bx bx-check mr-1'></i>Selesai
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class='bx bx-calendar-x text-2xl text-gray-400'></i>
                    </div>
                    <p class="text-sm text-gray-500 font-medium">Tidak ada jadwal hari ini</p>
                    <p class="text-xs text-gray-400 mt-1">Jadwal kosong untuk hari ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 bg-black/50 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-lg w-full" style="height: 60vh; max-height: 60vh;">
            <div class="p-6 border-b border-gray-100 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                            <i class='bx bx-detail text-blue-600'></i>
                        </div>
                        <h3 class="font-medium text-gray-800">Detail Permohonan</h3>
                    </div>
                    <button onclick="closeModal()" class="w-8 h-8 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md flex items-center justify-center transition-colors" title="Tutup">
                        <i class='bx bx-x text-lg'></i>
                    </button>
                </div>
            </div>
            <div id="detailContent" class="p-6 overflow-y-auto" style="height: calc(60vh - 80px);">
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
                        <div class="flex items-center space-x-2 mb-3">
                            <i class='bx bx-user text-gray-600'></i>
                            <h4 class="font-medium text-gray-900">Informasi Siswa</h4>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-medium text-gray-700">Nama:</span> ${data.siswa.nama_lengkap}</p>
                            <p class="text-sm"><span class="font-medium text-gray-700">Kelas:</span> ${data.siswa.kelas} ${data.siswa.jurusan}</p>
                            <p class="text-sm"><span class="font-medium text-gray-700">NISN:</span> ${data.siswa.nisn}</p>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center space-x-2 mb-3">
                            <i class='bx bx-clipboard text-blue-600'></i>
                            <h4 class="font-medium text-gray-900">Detail Permohonan</h4>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm"><span class="font-medium text-gray-700">Jenis:</span> ${data.jenis_konseling.charAt(0).toUpperCase() + data.jenis_konseling.slice(1)}</p>
                            <p class="text-sm"><span class="font-medium text-gray-700">Topik:</span> ${data.topik_konseling}</p>
                            <p class="text-sm"><span class="font-medium text-gray-700">Tanggal:</span> ${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center space-x-2 mb-3">
                            <i class='bx bx-message-detail text-green-600'></i>
                            <h4 class="font-medium text-gray-900">Ringkasan Masalah</h4>
                        </div>
                        <p class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">${data.ringkasan_masalah}</p>
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

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

@endsection