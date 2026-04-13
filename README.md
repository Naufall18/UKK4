# Perpustakaan Digital (Flutter + Laravel) 📚

Proyek Perpustakaan Digital ini dikembangkan untuk keperluan Uji Kompetensi Keahlian (UKK). Sistem ini memadukan **Backend API berbasis Laravel** dan **Aplikasi Mobile (Frontend) berbasis Flutter**.

Terbagi menjadi dua peran/hak akses utama: **Admin** dan **Siswa (Anggota)**.

## Fitur Utama 🔥

### 1. Sistem Autentikasi & Registrasi Admin Dinamis 🔒
- **Registrasi Siswa**: Secara *default*, semua pengguna yang mendaftar akan diklasifikasikan sebagai **Siswa** (harus mengisi formulir NIS dan Kelas).
- **Cetak PDF Transaksi (Admin)**: Pada halaman transaksi admin terdapat ikon PDF di pojok kanan atas. Pilih untuk mencetak seluruh transaksi atau berdasarkan NIS siswa, kemudian hasil akan dibagikan/simpan sebagai file PDF.
- **Registrasi Admin (Secret Code)**: Untuk mencegah sembarang orang mendaftar sebagai Admin, kami menyematkan field **"Kode Admin (Opsional)"**. 
  - Jika form ini *dikosongkan*, akun akan menjadi akun **Siswa**.
  - Jika form ini *diisi* dengan Kode Rahasia (misal: `UKK2026ADMIN`), sistem backend secara otomatis akan mengangkat akun tersebut menjadi **Admin**. (Kode dapat dikonfigurasi pada environment file `.env` Laravel Anda: `ADMIN_SECRET_CODE=UKK2026ADMIN`).
- **Verifikasi OTP**: Saat mencoba mengganti password di Profil, sistem diwajibkan mengirimkan **6-digit kode OTP** melalui Email (berfungsi via SMTP Mailtrap / integrasi log untuk lingkungan *local*).

### 2. Multi-Role Dashboard 📊
- **Dashboard Admin**:
  - Tampilan statistik kartu interaktif (Total Buku, Total Siswa, Peminjaman Berlansung).
  - Manajemen `Buku` (Tambah Foto, Judul, Kategori, **Deskripsi**, dan Edit Data secara Real-Time).
  - Manajemen `Anggota` (Melihat status keaktifan dan memperbesar/zoom foto profil resmi dari siswa).
  - Manajemen `Transaksi` dengan "Bottom Sheet Modal" yang elegan. Jika buku dikembalikan, dapat menekan **Tandai Selesai**.
- **Dashboard Siswa**:
  - Rekomendasi Buku & Peminjaman Buku Otomatis.
  - Kartu notifikasi Peminjaman Aktif. Jika buku melewati **Batas Kembali**, otomatis memunculkan teks peringatan denda dan **"Terlambat!"** berwarna merah.
  - Detail Riwayat Transaksi. Menampilkan tanggal dikembalikan secara akurat `tanggal_kembali_aktual` dibandingkan tenggat waktu yang seharusnya.

### 3. User Interface (UI) Modern & Mode Gelap (Dark Mode) 🌗
- Dibangun dengan komponen material modern (*Glassmorphism*, bayangan/shadow halus).
- **Dark Mode**: Siswa dan Admin memiliki pengaturan tema di halaman Profil. Pengguna dapat memilih antara **Sistem (Otomatis menyesuaikan mode HP)**, **Cerah**, atau **Gelap**. Merubah seluruh tema warna aplikasi menjadi aksen `Slate/Indigo` yang memanjakan mata!
- **Notifikasi Global Seragam**: `Snackbar` modern yang seragam digunakan untuk peringatan sistem (Error, Sukses, Validasi Data).

## Panduan Instalasi & Pengujian

### Backend (Laravel)
1. **Pindah ke Direktori**: `cd c:\laragon\www\paket44`
2. **Environment**: Pastikan file `.env` sudah ada. Untuk mencoba OTP di perangkat lokal tanpa koneksi SMTP Mailtrap, `MAIL_MAILER` diatur menjadi `log` (OTP muncul di `storage/logs/laravel.log`).
3. **Migrate Database**: `php artisan migrate:fresh --seed`
4. **Jalankan Server API**: `php artisan serve --host=0.0.0.0 --port=8000`

### Frontend (Flutter)
1. **Pindah ke Direktori**: `cd c:\laragon\www\paket44\mobile_paket44`
2. **Ubah Target API**: Edit `lib/data/services/api_service.dart` dan sesuaikan IP dengan komputer Anda (misal `http://192.168.x.x:8000/api`).
3. **Jalankan Aplikasi Mobile**: `flutter run` 

---
*Proyek ini dirancang agar solid saat presentasi UKK dengan penanganan edge-case (error handling Form/API) dan antarmuka premium.*
