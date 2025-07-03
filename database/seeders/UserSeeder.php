<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\GuruBK;
use App\Models\Siswa;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'administrator',
        ]);

        $guruBKUser1 = User::create([
            'username' => 'gurubk01',
            'password' => Hash::make('gurubk01'),
            'role' => 'guru_bk',
        ]);

        GuruBK::create([
            'user_id' => $guruBKUser1->id,
            'nip' => '198005152010012001',
            'nama_lengkap' => 'Dra. Sarah Konseling, S.Pd',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1980-05-15',
            'no_telp' => '081234567891',
            'alamat' => 'Jl. Guru BK No. 456, Bandung, Jawa Barat',
        ]);

        $guruBKUser2 = User::create([
            'username' => 'gurubk02',
            'password' => Hash::make('gurubk02'),
            'role' => 'guru_bk',
        ]);

        GuruBK::create([
            'user_id' => $guruBKUser2->id,
            'nip' => '197805152009011001',
            'nama_lengkap' => 'Drs. Budi Konseling, M.Pd',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1978-05-15',
            'no_telp' => '081234567893',
            'alamat' => 'Jl. Guru BK No. 123, Surabaya, Jawa Timur',
        ]);

        $siswaUser1 = User::create([
            'username' => 'siswa01',
            'password' => Hash::make('siswa01'),
            'role' => 'siswa',
        ]);

        Siswa::create([
            'user_id' => $siswaUser1->id,
            'nisn' => '1234567890',
            'nama_lengkap' => 'Ahmad Rizki Pratama',
            'kelas' => 'XII IPA 1',
            'jurusan' => 'IPA',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2005-01-15',
            'tempat_lahir' => 'Jakarta',
            'no_telp' => '081234567892',
            'alamat' => 'Jl. Siswa No. 789, Jakarta Pusat, DKI Jakarta',
        ]);

        $siswaUser2 = User::create([
            'username' => 'siswa02',
            'password' => Hash::make('siswa02'),
            'role' => 'siswa',
        ]);

        Siswa::create([
            'user_id' => $siswaUser2->id,
            'nisn' => '1234567891',
            'nama_lengkap' => 'Siti Nurhaliza',
            'kelas' => 'XII IPS 1',
            'jurusan' => 'IPS',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2005-03-20',
            'tempat_lahir' => 'Medan',
            'no_telp' => '081234567894',
            'alamat' => 'Jl. Siswa No. 456, Medan, Sumatera Utara',
        ]);

        $siswaUser3 = User::create([
            'username' => 'siswa03',
            'password' => Hash::make('siswa03'),
            'role' => 'siswa',
        ]);

        Siswa::create([
            'user_id' => $siswaUser3->id,
            'nisn' => '1234567892',
            'nama_lengkap' => 'Budi Santoso',
            'kelas' => 'XI IPA 2',
            'jurusan' => 'IPA',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2006-07-10',
            'tempat_lahir' => 'Yogyakarta',
            'no_telp' => '081234567895',
            'alamat' => 'Jl. Siswa No. 321, Yogyakarta, DIY',
        ]);
    }
}