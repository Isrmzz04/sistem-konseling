<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'administrator',
        ]);

        User::create([
            'name' => 'Guru BK 1',
            'username' => 'gurubk',
            'password' => Hash::make('gurubk123'),
            'role' => 'guru_bk',
        ]);

        User::create([
            'name' => 'Siswa Test',
            'username' => 'siswa',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
        ]);
    }
}