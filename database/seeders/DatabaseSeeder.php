<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===================== USERS =====================
        // Admin utama
        User::updateOrCreate(['username' => 'admin'], [
            'name' => 'Administrator',
            'email' => 'admin@perpustakaan.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_anggota' => 'ADM-001',
            'status_aktif' => true,
        ]);

        // Siswa-siswa
        $siswaData = [
            ['name' => 'Ahmad Rizky', 'username' => 'ahmad', 'email' => 'ahmad@perpustakaan.test', 'nis' => '2024001', 'kelas' => 'XII RPL 1', 'no_hp' => '081234567890', 'no_anggota' => 'SIS-001'],
            ['name' => 'Siti Nurhaliza', 'username' => 'siti', 'email' => 'siti@perpustakaan.test', 'nis' => '2024002', 'kelas' => 'XII RPL 2', 'no_hp' => '081234567891', 'no_anggota' => 'SIS-002'],
            ['name' => 'Budi Santoso', 'username' => 'budi', 'email' => 'budi@perpustakaan.test', 'nis' => '2024003', 'kelas' => 'XI RPL 1', 'no_hp' => '081234567892', 'no_anggota' => 'SIS-003'],
            ['name' => 'Dewi Lestari', 'username' => 'dewi', 'email' => 'dewi@perpustakaan.test', 'nis' => '2024004', 'kelas' => 'XI TKJ 1', 'no_hp' => '081234567893', 'no_anggota' => 'SIS-004'],
            ['name' => 'Rafi Pratama', 'username' => 'rafi', 'email' => 'rafi@perpustakaan.test', 'nis' => '2024005', 'kelas' => 'X RPL 1', 'no_hp' => '081234567894', 'no_anggota' => 'SIS-005'],
            ['name' => 'Anisa Putri', 'username' => 'anisa', 'email' => 'anisa@perpustakaan.test', 'nis' => '2024006', 'kelas' => 'X TKJ 2', 'no_hp' => '081234567895', 'no_anggota' => 'SIS-006'],
            ['name' => 'Fajar Nugraha', 'username' => 'fajar', 'email' => 'fajar@perpustakaan.test', 'nis' => '2024007', 'kelas' => 'XII MM 1', 'no_hp' => '081234567896', 'no_anggota' => 'SIS-007'],
            ['name' => 'Rina Wati', 'username' => 'rina', 'email' => 'rina@perpustakaan.test', 'nis' => '2024008', 'kelas' => 'XI AKL 1', 'no_hp' => '081234567897', 'no_anggota' => 'SIS-008'],
        ];

        $siswaIds = [];
        foreach ($siswaData as $s) {
            $user = User::updateOrCreate(['username' => $s['username']], array_merge($s, [
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'status_aktif' => true,
            ]));
            $siswaIds[] = $user->id;
        }

        // Siswa nonaktif (untuk test support request)
        User::updateOrCreate(['username' => 'nonaktif'], [
            'name' => 'Siswa Nonaktif',
            'email' => 'nonaktif@perpustakaan.test',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
            'nis' => '2024099',
            'kelas' => 'XII RPL 1',
            'no_hp' => '081234567899',
            'no_anggota' => 'SIS-099',
            'status_aktif' => false,
            'support_request' => 'Mohon aktifkan kembali akun saya, saya ingin meminjam buku untuk tugas.',
        ]);

        // ===================== BUKU =====================
        $bukuData = [
            // Novel
            ['judul' => 'Laskar Pelangi', 'pengarang' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun' => '2005', 'kategori' => 'Novel', 'stok' => 5, 'deskripsi' => 'Novel inspiratif tentang pendidikan di Belitung.', 'lokasi_rak' => 'A1-01'],
            ['judul' => 'Bumi Manusia', 'pengarang' => 'Pramoedya Ananta Toer', 'penerbit' => 'Hasta Mitra', 'tahun' => '1980', 'kategori' => 'Novel', 'stok' => 3, 'deskripsi' => 'Tetralogi Buru tentang perjuangan kemerdekaan.', 'lokasi_rak' => 'A1-02'],
            ['judul' => 'Perahu Kertas', 'pengarang' => 'Dee Lestari', 'penerbit' => 'Bentang Pustaka', 'tahun' => '2009', 'kategori' => 'Novel', 'stok' => 4, 'deskripsi' => 'Kisah cinta dan passion seni rupa.', 'lokasi_rak' => 'A1-03'],
            ['judul' => 'Ayat-Ayat Cinta', 'pengarang' => 'Habiburrahman El Shirazy', 'penerbit' => 'Republika', 'tahun' => '2004', 'kategori' => 'Novel', 'stok' => 3, 'deskripsi' => 'Novel religi tentang kehidupan mahasiswa Indonesia di Mesir.', 'lokasi_rak' => 'A1-04'],
            ['judul' => 'Negeri 5 Menara', 'pengarang' => 'Ahmad Fuadi', 'penerbit' => 'Gramedia', 'tahun' => '2009', 'kategori' => 'Novel', 'stok' => 4, 'deskripsi' => 'Kisah inspiratif kehidupan santri di pesantren.', 'lokasi_rak' => 'A1-05'],

            // Pelajaran
            ['judul' => 'Matematika Kelas XII', 'pengarang' => 'Sukino', 'penerbit' => 'Erlangga', 'tahun' => '2018', 'kategori' => 'Pelajaran', 'stok' => 10, 'deskripsi' => 'Buku pelajaran matematika SMA kelas 12.', 'lokasi_rak' => 'B1-01'],
            ['judul' => 'Fisika Dasar', 'pengarang' => 'Halliday & Resnick', 'penerbit' => 'Erlangga', 'tahun' => '2010', 'kategori' => 'Pelajaran', 'stok' => 7, 'deskripsi' => 'Buku referensi fisika dasar untuk SMA.', 'lokasi_rak' => 'B1-02'],
            ['judul' => 'Kimia Dasar', 'pengarang' => 'Raymond Chang', 'penerbit' => 'Erlangga', 'tahun' => '2012', 'kategori' => 'Pelajaran', 'stok' => 6, 'deskripsi' => 'Buku pegangan kimia dasar.', 'lokasi_rak' => 'B1-03'],
            ['judul' => 'Biologi Campbell', 'pengarang' => 'Neil Campbell', 'penerbit' => 'Erlangga', 'tahun' => '2015', 'kategori' => 'Pelajaran', 'stok' => 5, 'deskripsi' => 'Referensi biologi komprehensif.', 'lokasi_rak' => 'B1-04'],
            ['judul' => 'Bahasa Indonesia Kelas XI', 'pengarang' => 'Suherli', 'penerbit' => 'Kemendikbud', 'tahun' => '2017', 'kategori' => 'Pelajaran', 'stok' => 8, 'deskripsi' => 'Buku teks bahasa Indonesia kelas 11.', 'lokasi_rak' => 'B1-05'],

            // Pemrograman
            ['judul' => 'Belajar PHP & MySQL', 'pengarang' => 'Lukmanul Hakim', 'penerbit' => 'Lokomedia', 'tahun' => '2014', 'kategori' => 'Pemrograman', 'stok' => 4, 'deskripsi' => 'Panduan lengkap belajar PHP dan MySQL.', 'lokasi_rak' => 'C1-01'],
            ['judul' => 'Flutter Mobile Development', 'pengarang' => 'Eric Windmill', 'penerbit' => 'Manning', 'tahun' => '2020', 'kategori' => 'Pemrograman', 'stok' => 3, 'deskripsi' => 'Panduan membangun aplikasi mobile dengan Flutter.', 'lokasi_rak' => 'C1-02'],
            ['judul' => 'JavaScript: The Good Parts', 'pengarang' => 'Douglas Crockford', 'penerbit' => 'O\'Reilly', 'tahun' => '2008', 'kategori' => 'Pemrograman', 'stok' => 5, 'deskripsi' => 'Panduan inti JavaScript.', 'lokasi_rak' => 'C1-03'],
            ['judul' => 'Python untuk Data Science', 'pengarang' => 'Wes McKinney', 'penerbit' => 'O\'Reilly', 'tahun' => '2017', 'kategori' => 'Pemrograman', 'stok' => 4, 'deskripsi' => 'Analisis data menggunakan Python.', 'lokasi_rak' => 'C1-04'],
            ['judul' => 'Laravel: Up & Running', 'pengarang' => 'Matt Stauffer', 'penerbit' => 'O\'Reilly', 'tahun' => '2019', 'kategori' => 'Pemrograman', 'stok' => 3, 'deskripsi' => 'Framework Laravel dari dasar hingga mahir.', 'lokasi_rak' => 'C1-05'],

            // Agama
            ['judul' => 'Fiqh Islam', 'pengarang' => 'Sulaiman Rasjid', 'penerbit' => 'Sinar Baru', 'tahun' => '2012', 'kategori' => 'Agama', 'stok' => 6, 'deskripsi' => 'Panduan fiqh Islam lengkap.', 'lokasi_rak' => 'D1-01'],
            ['judul' => 'Tafsir Al-Misbah', 'pengarang' => 'M. Quraish Shihab', 'penerbit' => 'Lentera Hati', 'tahun' => '2002', 'kategori' => 'Agama', 'stok' => 3, 'deskripsi' => 'Tafsir Al-Quran komprehensif.', 'lokasi_rak' => 'D1-02'],
            ['judul' => 'Sirah Nabawiyah', 'pengarang' => 'Syaikh Shafiyyurrahman', 'penerbit' => 'Pustaka Al-Kautsar', 'tahun' => '2006', 'kategori' => 'Agama', 'stok' => 4, 'deskripsi' => 'Sejarah kehidupan Nabi Muhammad SAW.', 'lokasi_rak' => 'D1-03'],

            // Sejarah
            ['judul' => 'Sejarah Indonesia Modern', 'pengarang' => 'M.C. Ricklefs', 'penerbit' => 'Gadjah Mada University Press', 'tahun' => '2008', 'kategori' => 'Sejarah', 'stok' => 4, 'deskripsi' => 'Sejarah Indonesia dari era kolonial hingga modern.', 'lokasi_rak' => 'E1-01'],
            ['judul' => 'Soekarno: Penyambung Lidah Rakyat', 'pengarang' => 'Cindy Adams', 'penerbit' => 'Gunung Agung', 'tahun' => '1966', 'kategori' => 'Sejarah', 'stok' => 3, 'deskripsi' => 'Autobiografi Presiden Soekarno.', 'lokasi_rak' => 'E1-02'],

            // Sains
            ['judul' => 'Cosmos', 'pengarang' => 'Carl Sagan', 'penerbit' => 'Random House', 'tahun' => '1980', 'kategori' => 'Sains', 'stok' => 3, 'deskripsi' => 'Eksplorasi alam semesta dan sains populer.', 'lokasi_rak' => 'F1-01'],
            ['judul' => 'A Brief History of Time', 'pengarang' => 'Stephen Hawking', 'penerbit' => 'Bantam Books', 'tahun' => '1988', 'kategori' => 'Sains', 'stok' => 4, 'deskripsi' => 'Pengantar kosmologi dan fisika teoritis.', 'lokasi_rak' => 'F1-02'],

            // Ensiklopedia
            ['judul' => 'Ensiklopedia Indonesia', 'pengarang' => 'Tim Penulis', 'penerbit' => 'Ichtiar Baru', 'tahun' => '2000', 'kategori' => 'Ensiklopedia', 'stok' => 2, 'deskripsi' => 'Ensiklopedia umum Indonesia.', 'lokasi_rak' => 'G1-01'],
            ['judul' => 'Ensiklopedia Sains & Teknologi', 'pengarang' => 'Tim Penulis', 'penerbit' => 'Gramedia', 'tahun' => '2010', 'kategori' => 'Ensiklopedia', 'stok' => 2, 'deskripsi' => 'Ensiklopedia sains dan teknologi.', 'lokasi_rak' => 'G1-02'],
        ];

        // Create placeholder covers
        Storage::disk('public')->makeDirectory('covers');

        $bukuIds = [];
        $colorSchemes = [
            '4F46E5',
            '7C3AED',
            '2563EB',
            '0891B2',
            '059669',
            'D97706',
            'DC2626',
            'DB2777',
            '4338CA',
            '0D9488',
            '6D28D9',
            'B91C1C',
            '15803D',
            '1D4ED8',
            '9333EA',
            'C2410C',
            '0369A1',
            '7E22CE',
            'B45309',
            '047857',
            '6366F1',
            'E11D48',
            '16A34A',
            '2563EB',
            '9333EA'
        ];

        foreach ($bukuData as $i => $data) {
            $color = $colorSchemes[$i % count($colorSchemes)];
            $titleEnc = urlencode($data['judul']);
            $coverUrl = "https://placehold.co/400x560/{$color}/ffffff?text={$titleEnc}";

            $buku = Buku::updateOrCreate(['judul' => $data['judul']], array_merge($data, [
                'cover' => $coverUrl,
            ]));
            $bukuIds[] = $buku->id;
        }

        // ===================== TRANSAKSI CONTOH =====================
        // Clear old transactions
        Peminjaman::truncate();

        // 1. Approved & sedang dipinjam (normal)
        Peminjaman::create([
            'user_id' => $siswaIds[0], // Ahmad
            'buku_id' => $bukuIds[0],  // Laskar Pelangi
            'durasi_hari' => 7,
            'status_approval' => 'approved',
            'status' => 'dipinjam',
            'tgl_pinjam' => Carbon::today()->subDays(2),
            'tgl_kembali_rencana' => Carbon::today()->addDays(5),
        ]);

        // 2. Terlambat (sudah lewat batas)
        Peminjaman::create([
            'user_id' => $siswaIds[1], // Siti
            'buku_id' => $bukuIds[5],  // Matematika
            'durasi_hari' => 7,
            'status_approval' => 'approved',
            'status' => 'dipinjam',
            'tgl_pinjam' => Carbon::today()->subDays(14),
            'tgl_kembali_rencana' => Carbon::today()->subDays(7),
        ]);

        // 3. Terlambat (sudah lewat batas lama)
        Peminjaman::create([
            'user_id' => $siswaIds[2], // Budi
            'buku_id' => $bukuIds[10], // Belajar PHP
            'durasi_hari' => 7,
            'status_approval' => 'approved',
            'status' => 'dipinjam',
            'tgl_pinjam' => Carbon::today()->subDays(21),
            'tgl_kembali_rencana' => Carbon::today()->subDays(14),
        ]);

        // 4. Pending (menunggu approval)
        Peminjaman::create([
            'user_id' => $siswaIds[3], // Dewi
            'buku_id' => $bukuIds[11], // Flutter
            'durasi_hari' => 14,
            'status_approval' => 'pending',
            'status' => 'dipinjam',
        ]);

        // 5. Pending
        Peminjaman::create([
            'user_id' => $siswaIds[4], // Rafi
            'buku_id' => $bukuIds[15], // Fiqh Islam
            'durasi_hari' => 7,
            'status_approval' => 'pending',
            'status' => 'dipinjam',
        ]);

        // 6. Dikembalikan (riwayat selesai)
        Peminjaman::create([
            'user_id' => $siswaIds[0], // Ahmad
            'buku_id' => $bukuIds[3],  // Ayat-Ayat Cinta
            'durasi_hari' => 7,
            'status_approval' => 'approved',
            'status' => 'dikembalikan',
            'tgl_pinjam' => Carbon::today()->subDays(20),
            'tgl_kembali_rencana' => Carbon::today()->subDays(13),
            'tgl_kembali_aktual' => Carbon::today()->subDays(15),
            'kondisi_buku' => 'baik',
        ]);

        // 7. Dikembalikan terlambat dengan denda
        Peminjaman::create([
            'user_id' => $siswaIds[5], // Anisa
            'buku_id' => $bukuIds[6],  // Fisika Dasar
            'durasi_hari' => 7,
            'status_approval' => 'approved',
            'status' => 'terlambat',
            'tgl_pinjam' => Carbon::today()->subDays(30),
            'tgl_kembali_rencana' => Carbon::today()->subDays(23),
            'tgl_kembali_aktual' => Carbon::today()->subDays(20),
            'denda' => 3000,
            'kondisi_buku' => 'baik',
        ]);

        // 8. Dikembalikan dengan kerusakan
        Peminjaman::create([
            'user_id' => $siswaIds[6], // Fajar
            'buku_id' => $bukuIds[18], // Sejarah Indonesia
            'durasi_hari' => 14,
            'status_approval' => 'approved',
            'status' => 'dikembalikan',
            'tgl_pinjam' => Carbon::today()->subDays(25),
            'tgl_kembali_rencana' => Carbon::today()->subDays(11),
            'tgl_kembali_aktual' => Carbon::today()->subDays(12),
            'kondisi_buku' => 'rusak_ringan',
            'catatan_kondisi' => 'Halaman 45-50 sedikit robek di bagian sudut.',
            'denda_kerusakan' => 10000,
            'denda' => 10000,
        ]);
    }
}
