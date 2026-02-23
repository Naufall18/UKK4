# 📚 Perpustakaan Sekolah Digital

Aplikasi **Sistem Informasi Perpustakaan Sekolah Digital** berbasis web yang dibangun menggunakan **Laravel 10**, **Bootstrap 5 CDN**, dan **MySQL**.

---

## ✨ Fitur Utama

| Fitur | Admin | Siswa |
|-------|:-----:|:-----:|
| Dashboard Statistik | ✅ | ✅ |
| CRUD Buku | ✅ | ❌ |
| CRUD Anggota | ✅ | ❌ |
| Peminjaman Buku | ❌ | ✅ |
| Pengembalian Buku | ✅ | ❌ |
| Riwayat Peminjaman | ✅ | ✅ |
| Perhitungan Denda Otomatis | ✅ | ✅ |
| Pencarian & Pagination | ✅ | ✅ |

---

## 🛠 Tech Stack

- **Backend:** Laravel 10 (PHP 8.1+)
- **Database:** MySQL 8.0
- **Frontend:** Blade Template + Bootstrap 5 CDN + Bootstrap Icons
- **Server Lokal:** Laragon

---

## 🚀 Instalasi & Setup

### 1. Clone / Copy Project
Salin folder project ke `C:\laragon\www\paket44\`

### 2. Install Dependencies
```bash
cd C:\laragon\www\paket44
composer install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database & Migrasi
```bash
# Buat database 'perpustakaan' di phpMyAdmin
php artisan migrate:fresh --seed
```

### 5. Jalankan
```bash
php artisan serve
```
Buka: **http://localhost:8000** atau **http://paket44.test** (via Laragon)

---

## 🔑 Akun Login

| Role | Username | Password |
|------|----------|----------|
| **Admin** | `admin` | `admin123` |
| **Siswa** | `ahmad` | `siswa123` |
| **Siswa** | `siti` | `siswa123` |

---

## 📁 Struktur Project

```
paket44/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          # Login, Register, Logout
│   │   ├── Admin/
│   │   │   ├── DashboardController.php # Dashboard + statistik
│   │   │   ├── BukuController.php      # CRUD buku
│   │   │   ├── AnggotaController.php   # CRUD anggota/siswa
│   │   │   └── TransaksiController.php # Kelola peminjaman
│   │   └── Siswa/
│   │       ├── DashboardController.php # Dashboard siswa
│   │       ├── PinjamController.php    # Pinjam buku baru
│   │       └── RiwayatController.php   # Riwayat peminjaman
│   ├── Http/Middleware/
│   │   ├── IsAdmin.php                 # Middleware cek role admin
│   │   └── IsSiswa.php                 # Middleware cek role siswa
│   └── Models/
│       ├── User.php                    # Model user (admin/siswa)
│       ├── Buku.php                    # Model buku perpustakaan
│       └── Peminjaman.php             # Model transaksi peminjaman
├── database/
│   ├── migrations/                     # 3 migration files
│   └── seeders/DatabaseSeeder.php     # Data dummy (admin, 2 siswa, 5 buku)
├── resources/views/
│   ├── layouts/                        # Layout sidebar + auth card
│   ├── auth/                           # Login & register
│   ├── admin/                          # 6 view admin
│   └── siswa/                          # 3 view siswa
└── routes/web.php                      # Semua routing
```

---

## 📖 Dokumentasi Lengkap

Dokumentasi lengkap dengan **seluruh source code** tersedia di 4 file berikut:

1. **[DOKUMENTASI.md](DOKUMENTASI.md)** — BAB 1-5: Pendahuluan, Setup, Database, Model, Middleware & Auth
2. **[DOKUMENTASI_PART2.md](DOKUMENTASI_PART2.md)** — BAB 6-8: Semua Controller, Routing, Konfigurasi
3. **[DOKUMENTASI_PART3.md](DOKUMENTASI_PART3.md)** — BAB 9-10: Layout (Sidebar + Auth Card), Halaman Login & Register
4. **[DOKUMENTASI_PART4.md](DOKUMENTASI_PART4.md)** — BAB 11-13: Halaman Admin, Halaman Siswa, Cara Menjalankan
5. **[DOKUMENTASI_PART5.md](DOKUMENTASI_PART5.md)** — Panduan Terminal: Step-by-step dari nol (untuk pemula)

---

## 📊 Diagram Relasi Database (ERD)

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│    users     │       │  peminjamen  │       │    bukus     │
├──────────────┤       ├──────────────┤       ├──────────────┤
│ id (PK)      │──1:N──│ user_id (FK) │       │ id (PK)      │
│ name         │       │ buku_id (FK) │──N:1──│ judul        │
│ username     │       │ tgl_pinjam   │       │ pengarang    │
│ email        │       │ tgl_kembali  │       │ penerbit     │
│ role (enum)  │       │ status       │       │ tahun        │
│ nis          │       │ denda        │       │ kategori     │
│ kelas        │       └──────────────┘       │ stok         │
│ no_anggota   │                              └──────────────┘
└──────────────┘
```

---

## ⚙️ Fitur Teknis

- **Auto-generate No. Anggota** — Format: `SIS-001`, `SIS-002`, dst.
- **Batas Peminjaman** — 7 hari sejak tanggal pinjam
- **Denda Otomatis** — Rp 1.000 per hari keterlambatan
- **Stok Otomatis** — Berkurang saat dipinjam, bertambah saat dikembalikan
- **Pencegahan Duplikat** — Siswa tidak bisa pinjam buku yang sama sebelum dikembalikan
- **Bootstrap 5 Pagination** — Konfigurasi di `AppServiceProvider`

---

*Dibuat dengan ❤️ menggunakan Laravel 10 + Bootstrap 5*
