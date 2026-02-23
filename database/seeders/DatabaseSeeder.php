<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@perpustakaan.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_anggota' => 'ADM-001',
            'status_aktif' => true,
        ]);

        // Siswa 1
        User::create([
            'name' => 'Ahmad Rizky',
            'username' => 'ahmad',
            'email' => 'ahmad@perpustakaan.test',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
            'nis' => '2024001',
            'kelas' => 'XII RPL 1',
            'no_hp' => '081234567890',
            'no_anggota' => 'SIS-001',
            'status_aktif' => true,
        ]);

        // Siswa 2
        User::create([
            'name' => 'Siti Nurhaliza',
            'username' => 'siti',
            'email' => 'siti@perpustakaan.test',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
            'nis' => '2024002',
            'kelas' => 'XII RPL 2',
            'no_hp' => '081234567891',
            'no_anggota' => 'SIS-002',
            'status_aktif' => true,
        ]);

        // 5 Buku Dummy
        $bukus = [
            [
                'judul' => 'Laskar Pelangi',
                'pengarang' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun' => '2005',
                'kategori' => 'Novel',
                'stok' => 5,
            ],
            [
                'judul' => 'Bumi Manusia',
                'pengarang' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Hasta Mitra',
                'tahun' => '1980',
                'kategori' => 'Novel',
                'stok' => 3,
            ],
            [
                'judul' => 'Fisika Dasar',
                'pengarang' => 'Halliday & Resnick',
                'penerbit' => 'Erlangga',
                'tahun' => '2010',
                'kategori' => 'Sains',
                'stok' => 7,
            ],
            [
                'judul' => 'Matematika Kelas XII',
                'pengarang' => 'Sukino',
                'penerbit' => 'Erlangga',
                'tahun' => '2018',
                'kategori' => 'Pelajaran',
                'stok' => 10,
            ],
            [
                'judul' => 'Sejarah Indonesia Modern',
                'pengarang' => 'M.C. Ricklefs',
                'penerbit' => 'Gadjah Mada University Press',
                'tahun' => '2008',
                'kategori' => 'Sejarah',
                'stok' => 4,
            ],
        ];

        foreach ($bukus as $buku) {
            Buku::create($buku);
        }
    }
}
