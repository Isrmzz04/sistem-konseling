<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PermohonanKonseling;
use App\Models\JadwalKonseling;
use App\Models\LaporanBimbingan;

class WhatsAppNotificationService
{
    private $fonnte_url;
    private $fonnte_token;

    public function __construct()
    {
        $this->fonnte_url = config('services.fonnte.url', 'https://api.fonnte.com/send');
        $this->fonnte_token = config('services.fonnte.token');
    }

    /**
     * Send WhatsApp notification based on event type
     */
    public function sendNotification($model, string $eventType): bool
    {
        try {
            switch ($eventType) {
                case 'permohonan_masuk':
                    return $this->sendPermohonanMasukNotification($model);
                
                case 'status_updated':
                    return $this->sendStatusUpdateNotification($model);
                
                case 'jadwal_created':
                    return $this->sendJadwalCreatedNotification($model);
                
                case 'jadwal_dibatalkan':
                    return $this->sendJadwalDibatalkanNotification($model);
                
                case 'laporan_ready':
                    return $this->sendLaporanReadyNotification($model);
                
                default:
                    return false;
            }
        } catch (\Exception $e) {
            // Only log to Laravel log, no database
            Log::error("WhatsApp notification failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Notifikasi ke Guru BK: Permohonan Konseling Masuk
     */
    private function sendPermohonanMasukNotification(PermohonanKonseling $permohonan): bool
    {
        $guruBK = $permohonan->guruBK;
        $siswa = $permohonan->siswa;
        
        if (!$guruBK || !$guruBK->no_telp || !$siswa) {
            return false;
        }

        $message = "🔔 *PERMOHONAN KONSELING BARU*\n\n";
        $message .= "📝 *Detail Permohonan:*\n";
        $message .= "• Siswa: {$siswa->nama_lengkap}\n";
        $message .= "• NISN: {$siswa->nisn}\n";
        $message .= "• Kelas: {$siswa->kelas} {$siswa->jurusan}\n";
        $message .= "• Jenis: " . ucfirst($permohonan->jenis_konseling) . "\n";
        $message .= "• Topik: {$permohonan->topik_konseling}\n\n";
        $message .= "⏰ Diajukan: " . $permohonan->created_at->format('d/m/Y H:i') . "\n\n";
        $message .= "Silakan login ke sistem untuk memproses permohonan ini.\n\n";
        $message .= "_Sistem Konseling Digital - " . config('app.name') . "_";

        return $this->sendMessage($guruBK->no_telp, $message);
    }

    /**
     * Notifikasi ke Siswa: Status Permohonan Diupdate
     */
    private function sendStatusUpdateNotification(PermohonanKonseling $permohonan): bool
    {
        $siswa = $permohonan->siswa;
        $guruBK = $permohonan->guruBK;
        
        if (!$siswa || !$siswa->no_telp || !$guruBK) {
            return false;
        }

        $statusIcon = $permohonan->status === 'disetujui' ? '✅' : '❌';
        $statusText = $permohonan->status === 'disetujui' ? 'DISETUJUI' : 'DITOLAK';
        
        $message = "{$statusIcon} *PERMOHONAN KONSELING {$statusText}*\n\n";
        $message .= "📝 *Detail:*\n";
        $message .= "• Topik: {$permohonan->topik_konseling}\n";
        $message .= "• Jenis: " . ucfirst($permohonan->jenis_konseling) . "\n";
        $message .= "• Guru BK: {$guruBK->nama_lengkap}\n";
        $message .= "• Status: *{$statusText}*\n";
        $message .= "• Diproses: " . $permohonan->diproses_at->format('d/m/Y H:i') . "\n\n";
        
        if ($permohonan->catatan_guru_bk) {
            $message .= "💬 *Catatan Guru BK:*\n";
            $message .= "{$permohonan->catatan_guru_bk}\n\n";
        }
        
        if ($permohonan->status === 'disetujui') {
            $message .= "🕒 Silakan menunggu penjadwalan konseling dari guru BK.\n\n";
        }
        
        $message .= "_Sistem Konseling Digital - " . config('app.name') . "_";

        return $this->sendMessage($siswa->no_telp, $message);
    }

    /**
     * Notifikasi ke Siswa: Jadwal Konseling Dibuat
     */
    private function sendJadwalCreatedNotification(JadwalKonseling $jadwal): bool
    {
        $siswa = $jadwal->siswa;
        $guruBK = $jadwal->guruBK;
        $permohonan = $jadwal->permohonanKonseling;
        
        if (!$siswa || !$siswa->no_telp || !$guruBK || !$permohonan) {
            return false;
        }

        $message = "📅 *JADWAL KONSELING TERSEDIA*\n\n";
        $message .= "📝 *Detail Konseling:*\n";
        $message .= "• Topik: {$permohonan->topik_konseling}\n";
        $message .= "• Jenis: " . ucfirst($permohonan->jenis_konseling) . "\n";
        $message .= "• Guru BK: {$guruBK->nama_lengkap}\n\n";
        $message .= "🗓️ *Jadwal:*\n";
        $message .= "• Tanggal: " . $jadwal->tanggal_konseling->format('l, d F Y') . "\n";
        $message .= "• Waktu: " . $jadwal->jam_mulai->format('H:i') . " - " . $jadwal->jam_selesai->format('H:i') . " WIB\n";
        $message .= "• Tempat: {$jadwal->tempat}\n\n";
        
        if ($jadwal->catatan) {
            $message .= "💬 *Catatan:*\n";
            $message .= "{$jadwal->catatan}\n\n";
        }
        
        $message .= "⚠️ *Penting:* Harap hadir tepat waktu sesuai jadwal yang telah ditentukan.\n\n";
        $message .= "_Sistem Konseling Digital - " . config('app.name') . "_";

        return $this->sendMessage($siswa->no_telp, $message);
    }

    /**
     * Notifikasi ke Siswa: Jadwal Konseling Dibatalkan
     */
    private function sendJadwalDibatalkanNotification(JadwalKonseling $jadwal): bool
    {
        $siswa = $jadwal->siswa;
        $guruBK = $jadwal->guruBK;
        $permohonan = $jadwal->permohonanKonseling;
        
        if (!$siswa || !$siswa->no_telp || !$guruBK || !$permohonan) {
            return false;
        }

        $message = "❌ *JADWAL KONSELING DIBATALKAN*\n\n";
        $message .= "📝 *Detail Konseling:*\n";
        $message .= "• Topik: {$permohonan->topik_konseling}\n";
        $message .= "• Guru BK: {$guruBK->nama_lengkap}\n";
        $message .= "• Tanggal: " . $jadwal->tanggal_konseling->format('d/m/Y') . "\n";
        $message .= "• Waktu: " . $jadwal->jam_mulai->format('H:i') . " - " . $jadwal->jam_selesai->format('H:i') . " WIB\n\n";
        
        if ($jadwal->catatan) {
            $message .= "💬 *Alasan Pembatalan:*\n";
            $message .= "{$jadwal->catatan}\n\n";
        }
        
        $message .= "🔄 Guru BK akan menjadwalkan ulang konseling Anda. Silakan tunggu informasi selanjutnya.\n\n";
        $message .= "_Sistem Konseling Digital - " . config('app.name') . "_";

        return $this->sendMessage($siswa->no_telp, $message);
    }

    /**
     * Notifikasi ke Siswa: Laporan Bimbingan Tersedia
     */
    private function sendLaporanReadyNotification(LaporanBimbingan $laporan): bool
    {
        $jadwal = $laporan->jadwalKonseling;
        $siswa = $jadwal->siswa;
        $guruBK = $jadwal->guruBK;
        $permohonan = $jadwal->permohonanKonseling;
        
        if (!$siswa || !$siswa->no_telp || !$guruBK || !$permohonan) {
            return false;
        }

        $message = "📋 *LAPORAN BIMBINGAN TERSEDIA*\n\n";
        $message .= "📝 *Detail Konseling:*\n";
        $message .= "• Topik: {$permohonan->topik_konseling}\n";
        $message .= "• Guru BK: {$guruBK->nama_lengkap}\n";
        $message .= "• Tanggal: " . $jadwal->tanggal_konseling->format('l, d F Y') . "\n";
        $message .= "• Waktu: " . $jadwal->jam_mulai->format('H:i') . " - " . $jadwal->jam_selesai->format('H:i') . " WIB\n\n";
        $message .= "✅ *Status:* Konseling Selesai\n\n";
        $message .= "📄 Laporan hasil bimbingan sudah tersedia dan dapat diunduh melalui sistem.\n\n";
        $message .= "🔗 Silakan login ke sistem untuk mengakses laporan bimbingan Anda.\n\n";
        $message .= "_Sistem Konseling Digital - " . config('app.name') . "_";

        return $this->sendMessage($siswa->no_telp, $message);
    }

    /**
     * Send message via Fonnte API
     */
    private function sendMessage(string $phoneNumber, string $message): bool
    {
        if (!$this->fonnte_token) {
            return false;
        }

        // Format phone number
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);
        
        if (!$phoneNumber) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->fonnte_token,
            ])->post($this->fonnte_url, [
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return isset($result['status']) && $result['status'] === true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Format phone number for Fonnte API
     */
    private function formatPhoneNumber(string $phoneNumber): ?string
    {
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
        
        if (empty($phoneNumber)) {
            return null;
        }
        
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . $phoneNumber;
        }
        
        if (strlen($phoneNumber) < 10 || strlen($phoneNumber) > 15) {
            return null;
        }
        
        return $phoneNumber;
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(string $phoneNumber): bool
    {
        $testMessage = "🔔 Test notifikasi dari Sistem Konseling Digital\n\n";
        $testMessage .= "Jika Anda menerima pesan ini, berarti konfigurasi WhatsApp API berhasil.\n\n";
        $testMessage .= "Waktu: " . now()->format('d/m/Y H:i:s') . "\n\n";
        $testMessage .= "_Sistem Konseling Digital - " . config('app.name') . "_";
        
        return $this->sendMessage($phoneNumber, $testMessage);
    }
}