# DOKUMENTASI LENGKAP PROJECT — PART 5
# Panduan Terminal: Step-by-Step dari Nol

> **Untuk teman-teman yang sudah bisa membuat project Laravel, tapi belum tau langkah selanjutnya.**
> Ikuti semua perintah di bawah ini secara berurutan di terminal/CMD.

---

# LANGKAH 1: Buat Project Laravel Baru

Buka terminal di folder `C:\laragon\www\`, lalu jalankan:

```bash
composer create-project laravel/laravel paket44
cd paket44
```

---

# LANGKAH 2: Konfigurasi Database

## 2.1 Edit file `.env`
Buka file `.env` di root project, cari bagian database dan ubah:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

## 2.2 Buat database di MySQL
Buka **phpMyAdmin** (http://localhost/phpmyadmin), lalu:
1. Klik **"New"** di sidebar kiri
2. Ketik nama database: `perpustakaan`
3. Klik **"Create"**

Atau kalau mau lewat terminal Laragon:
```bash
mysql -u root -e "CREATE DATABASE perpustakaan;"
```

---

# LANGKAH 3: Buat Migration (Tabel Database)

## 3.1 Edit Migration Users
Laravel sudah membuat file migration users. **Hapus isi file lama** dan ganti:

Buka file: `database/migrations/2014_10_12_000000_create_users_table.php`

**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 3.1**.

## 3.2 Buat Migration Buku
Jalankan di terminal:

```bash
php artisan make:migration create_bukus_table
```

Ini akan membuat file baru di folder `database/migrations/`.
Buka file yang baru dibuat (nama file berisi tanggal + `create_bukus_table`), **hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 3.2**.

## 3.3 Buat Migration Peminjaman
```bash
php artisan make:migration create_peminjamen_table
```

Buka file yang baru dibuat, **hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 3.3**.

---

# LANGKAH 4: Buat Model

## 4.1 Edit Model User
Model User sudah ada. Buka file: `app/Models/User.php`

**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 4.1**.

## 4.2 Buat Model Buku
```bash
php artisan make:model Buku
```

Buka file: `app/Models/Buku.php`
**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 4.2**.

## 4.3 Buat Model Peminjaman
```bash
php artisan make:model Peminjaman
```

Buka file: `app/Models/Peminjaman.php`
**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 4.3**.

---

# LANGKAH 5: Buat Seeder (Data Dummy)

Buka file: `database/seeders/DatabaseSeeder.php`

**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 3.4**.

---

# LANGKAH 6: Buat Middleware

## 6.1 Buat Middleware IsAdmin
```bash
php artisan make:middleware IsAdmin
```

Buka file: `app/Http/Middleware/IsAdmin.php`
**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 5.1**.

## 6.2 Buat Middleware IsSiswa
```bash
php artisan make:middleware IsSiswa
```

Buka file: `app/Http/Middleware/IsSiswa.php`
**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 5.2**.

## 6.3 Daftarkan Middleware di Kernel.php
Buka file: `app/Http/Kernel.php`

Cari bagian `$middlewareAliases` (biasanya di baris paling bawah array), lalu **tambahkan 2 baris ini** di dalam array:

```php
'isAdmin' => \App\Http\Middleware\IsAdmin::class,
'isSiswa' => \App\Http\Middleware\IsSiswa::class,
```

Sehingga hasilnya seperti:
```php
protected $middlewareAliases = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    // ... middleware lain ...
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'isAdmin' => \App\Http\Middleware\IsAdmin::class,   // <-- tambahkan
    'isSiswa' => \App\Http\Middleware\IsSiswa::class,   // <-- tambahkan
];
```

---

# LANGKAH 7: Buat Controller

## 7.1 AuthController
```bash
php artisan make:controller AuthController
```

Buka file: `app/Http/Controllers/AuthController.php`
**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI.md BAB 5.4**.

## 7.2 Admin Controllers
Jalankan semua perintah berikut satu per satu:

```bash
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/BukuController
php artisan make:controller Admin/AnggotaController
php artisan make:controller Admin/TransaksiController
```

Buka masing-masing file, **hapus semua isinya**, lalu paste kode dari:
- `app/Http/Controllers/Admin/DashboardController.php` → **DOKUMENTASI_PART2.md BAB 6.1**
- `app/Http/Controllers/Admin/BukuController.php` → **DOKUMENTASI_PART2.md BAB 6.2**
- `app/Http/Controllers/Admin/AnggotaController.php` → **DOKUMENTASI_PART2.md BAB 6.3**
- `app/Http/Controllers/Admin/TransaksiController.php` → **DOKUMENTASI_PART2.md BAB 6.4**

## 7.3 Siswa Controllers
```bash
php artisan make:controller Siswa/DashboardController
php artisan make:controller Siswa/PinjamController
php artisan make:controller Siswa/RiwayatController
```

Buka masing-masing file, **hapus semua isinya**, lalu paste kode dari:
- `app/Http/Controllers/Siswa/DashboardController.php` → **DOKUMENTASI_PART2.md BAB 6.5**
- `app/Http/Controllers/Siswa/PinjamController.php` → **DOKUMENTASI_PART2.md BAB 6.6**
- `app/Http/Controllers/Siswa/RiwayatController.php` → **DOKUMENTASI_PART2.md BAB 6.7**

---

# LANGKAH 8: Setting Routes

Buka file: `routes/web.php`

**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI_PART2.md BAB 7**.

---

# LANGKAH 9: Setting Pagination Bootstrap 5

Buka file: `app/Providers/AppServiceProvider.php`

**Hapus semua isinya**, lalu paste kode dari **DOKUMENTASI_PART2.md BAB 8.1**.

---

# LANGKAH 10: Buat View (Tampilan Frontend)

## 10.1 Buat Folder-Folder View
Jalankan perintah berikut di terminal untuk membuat semua folder yang dibutuhkan:

**Windows (CMD):**
```cmd
mkdir resources\views\layouts
mkdir resources\views\auth
mkdir resources\views\admin\buku
mkdir resources\views\admin\anggota
mkdir resources\views\admin\transaksi
mkdir resources\views\siswa
```

**Windows (PowerShell):**
```powershell
New-Item -ItemType Directory -Force -Path "resources\views\layouts"
New-Item -ItemType Directory -Force -Path "resources\views\auth"
New-Item -ItemType Directory -Force -Path "resources\views\admin\buku"
New-Item -ItemType Directory -Force -Path "resources\views\admin\anggota"
New-Item -ItemType Directory -Force -Path "resources\views\admin\transaksi"
New-Item -ItemType Directory -Force -Path "resources\views\siswa"
```

## 10.2 Buat File-File View
Buat file-file berikut (file kosong dulu, nanti isinya di-paste):

**Windows (CMD):**
```cmd
type nul > resources\views\layouts\app.blade.php
type nul > resources\views\layouts\auth.blade.php
type nul > resources\views\auth\login.blade.php
type nul > resources\views\auth\register.blade.php
type nul > resources\views\admin\dashboard.blade.php
type nul > resources\views\admin\buku\index.blade.php
type nul > resources\views\admin\buku\create.blade.php
type nul > resources\views\admin\buku\edit.blade.php
type nul > resources\views\admin\anggota\index.blade.php
type nul > resources\views\admin\anggota\create.blade.php
type nul > resources\views\admin\anggota\edit.blade.php
type nul > resources\views\admin\transaksi\index.blade.php
type nul > resources\views\siswa\dashboard.blade.php
type nul > resources\views\siswa\pinjam.blade.php
type nul > resources\views\siswa\riwayat.blade.php
```

**Windows (PowerShell):**
```powershell
# Layout
New-Item -Force "resources\views\layouts\app.blade.php"
New-Item -Force "resources\views\layouts\auth.blade.php"

# Auth
New-Item -Force "resources\views\auth\login.blade.php"
New-Item -Force "resources\views\auth\register.blade.php"

# Admin
New-Item -Force "resources\views\admin\dashboard.blade.php"
New-Item -Force "resources\views\admin\buku\index.blade.php"
New-Item -Force "resources\views\admin\buku\create.blade.php"
New-Item -Force "resources\views\admin\buku\edit.blade.php"
New-Item -Force "resources\views\admin\anggota\index.blade.php"
New-Item -Force "resources\views\admin\anggota\create.blade.php"
New-Item -Force "resources\views\admin\anggota\edit.blade.php"
New-Item -Force "resources\views\admin\transaksi\index.blade.php"

# Siswa
New-Item -Force "resources\views\siswa\dashboard.blade.php"
New-Item -Force "resources\views\siswa\pinjam.blade.php"
New-Item -Force "resources\views\siswa\riwayat.blade.php"
```

## 10.3 Paste Isi View
Buka setiap file view yang baru dibuat, lalu paste kode sesuai referensi:

| File | Ambil kode dari |
|------|----------------|
| `layouts/app.blade.php` | **DOKUMENTASI_PART3.md BAB 9.1** |
| `layouts/auth.blade.php` | **DOKUMENTASI_PART3.md BAB 9.2** |
| `auth/login.blade.php` | **DOKUMENTASI_PART3.md BAB 10.1** |
| `auth/register.blade.php` | **DOKUMENTASI_PART3.md BAB 10.2** |
| `admin/dashboard.blade.php` | **DOKUMENTASI_PART4.md BAB 11.1** |
| `admin/buku/index.blade.php` | **DOKUMENTASI_PART4.md BAB 11.2** |
| `admin/buku/create.blade.php` | **DOKUMENTASI_PART4.md BAB 11.3** |
| `admin/buku/edit.blade.php` | **DOKUMENTASI_PART4.md BAB 11.4** |
| `admin/anggota/index.blade.php` | **DOKUMENTASI_PART4.md BAB 11.5** |
| `admin/anggota/create.blade.php` | **DOKUMENTASI_PART4.md BAB 11.6** |
| `admin/anggota/edit.blade.php` | **DOKUMENTASI_PART4.md BAB 11.7** |
| `admin/transaksi/index.blade.php` | **DOKUMENTASI_PART4.md BAB 11.8** |
| `siswa/dashboard.blade.php` | **DOKUMENTASI_PART4.md BAB 12.1** |
| `siswa/pinjam.blade.php` | **DOKUMENTASI_PART4.md BAB 12.2** |
| `siswa/riwayat.blade.php` | **DOKUMENTASI_PART4.md BAB 12.3** |

---

# LANGKAH 11: Jalankan Migrasi & Seeding

Setelah semua file di atas sudah di-paste, jalankan perintah ini di terminal:

```bash
php artisan migrate:fresh --seed
```

**Penjelasan:**
- `migrate:fresh` — menghapus semua tabel lama, lalu membuat tabel baru dari migration
- `--seed` — mengisi data dummy (admin, 2 siswa, 5 buku) dari DatabaseSeeder

**Output yang diharapkan:**
```
Dropping all tables ............................... 53ms DONE
Running migrations ................................ DONE
  2014_10_12_000000_create_users_table ............ DONE
  2024_01_01_000001_create_bukus_table ............ DONE
  2024_01_01_000002_create_peminjamen_table ....... DONE
Database seeding completed successfully.
```

> ⚠️ **Jika ada error**, kemungkinan besar ada kode yang salah di-paste. Baca pesan error-nya, cek file yang disebutkan, dan pastikan kode sudah benar.

---

# LANGKAH 12: Jalankan Aplikasi

```bash
php artisan serve
```

**Output:**
```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server
```

Buka browser dan akses: **http://localhost:8000**

Atau kalau pakai **Laragon** (sudah auto-detect), langsung buka: **http://paket44.test**

---

# LANGKAH 13: Test Login

Buka http://localhost:8000 → otomatis redirect ke halaman login.

## Login sebagai Admin:
| Field | Isi |
|-------|-----|
| Username | `admin` |
| Password | `admin123` |

## Login sebagai Siswa:
| Field | Isi |
|-------|-----|
| Username | `ahmad` |
| Password | `siswa123` |

---

# RINGKASAN SEMUA PERINTAH TERMINAL

Berikut **rangkuman semua perintah** yang harus dijalankan (secara berurutan):

```bash
# 1. Buat project Laravel
composer create-project laravel/laravel paket44
cd paket44

# 2. Buat database (via terminal, atau bisa lewat phpMyAdmin)
mysql -u root -e "CREATE DATABASE perpustakaan;"

# 3. Buat migration buku & peminjaman
php artisan make:migration create_bukus_table
php artisan make:migration create_peminjamen_table

# 4. Buat model
php artisan make:model Buku
php artisan make:model Peminjaman

# 5. Buat middleware
php artisan make:middleware IsAdmin
php artisan make:middleware IsSiswa

# 6. Buat controller - Auth
php artisan make:controller AuthController

# 7. Buat controller - Admin
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/BukuController
php artisan make:controller Admin/AnggotaController
php artisan make:controller Admin/TransaksiController

# 8. Buat controller - Siswa
php artisan make:controller Siswa/DashboardController
php artisan make:controller Siswa/PinjamController
php artisan make:controller Siswa/RiwayatController

# 9. Buat folder view
mkdir resources\views\layouts
mkdir resources\views\auth
mkdir resources\views\admin\buku
mkdir resources\views\admin\anggota
mkdir resources\views\admin\transaksi
mkdir resources\views\siswa

# 10. Jalankan migrasi + seeding
#     (SETELAH semua file sudah di-edit dan di-paste kodenya!)
php artisan migrate:fresh --seed

# 11. Jalankan server
php artisan serve
```

---

# CHECKLIST SEBELUM MENJALANKAN

Sebelum menjalankan `php artisan migrate:fresh --seed`, pastikan semua file sudah di-edit:

- [ ] `.env` → DB_DATABASE=perpustakaan
- [ ] `database/migrations/*_create_users_table.php` → kode dari BAB 3.1
- [ ] `database/migrations/*_create_bukus_table.php` → kode dari BAB 3.2
- [ ] `database/migrations/*_create_peminjamen_table.php` → kode dari BAB 3.3
- [ ] `database/seeders/DatabaseSeeder.php` → kode dari BAB 3.4
- [ ] `app/Models/User.php` → kode dari BAB 4.1
- [ ] `app/Models/Buku.php` → kode dari BAB 4.2
- [ ] `app/Models/Peminjaman.php` → kode dari BAB 4.3
- [ ] `app/Http/Middleware/IsAdmin.php` → kode dari BAB 5.1
- [ ] `app/Http/Middleware/IsSiswa.php` → kode dari BAB 5.2
- [ ] `app/Http/Kernel.php` → tambahkan 2 middleware alias
- [ ] `app/Http/Controllers/AuthController.php` → kode dari BAB 5.4
- [ ] `app/Http/Controllers/Admin/DashboardController.php` → BAB 6.1
- [ ] `app/Http/Controllers/Admin/BukuController.php` → BAB 6.2
- [ ] `app/Http/Controllers/Admin/AnggotaController.php` → BAB 6.3
- [ ] `app/Http/Controllers/Admin/TransaksiController.php` → BAB 6.4
- [ ] `app/Http/Controllers/Siswa/DashboardController.php` → BAB 6.5
- [ ] `app/Http/Controllers/Siswa/PinjamController.php` → BAB 6.6
- [ ] `app/Http/Controllers/Siswa/RiwayatController.php` → BAB 6.7
- [ ] `routes/web.php` → kode dari BAB 7
- [ ] `app/Providers/AppServiceProvider.php` → kode dari BAB 8.1
- [ ] 15 file view (lihat tabel di Langkah 10.3)

---

# TROUBLESHOOTING (MASALAH UMUM)

## Error: "SQLSTATE[HY000] [1049] Unknown database 'perpustakaan'"
**Solusi:** Database belum dibuat. Buat dulu lewat phpMyAdmin atau terminal:
```bash
mysql -u root -e "CREATE DATABASE perpustakaan;"
```

## Error: "Class 'App\Http\Middleware\IsAdmin' not found"
**Solusi:** Middleware belum ditambahkan di `Kernel.php`. Pastikan sudah menambahkan di `$middlewareAliases`.

## Error: "Target class [Admin\DashboardController] does not exist"
**Solusi:** Cek namespace di controller. Harus:
```php
namespace App\Http\Controllers\Admin;
```

## Error: "View [layouts.app] not found"
**Solusi:** File view belum dibuat. Pastikan sudah membuat folder dan file di `resources/views/layouts/app.blade.php`.

## Error: "Route [admin.dashboard] not defined"
**Solusi:** File `routes/web.php` belum di-update. Paste kode dari BAB 7.

## Halaman blank / putih
**Solusi:** Cek error di terminal (tempat `php artisan serve` berjalan). Biasanya ada typo di kode.

## Error: "CSRF token mismatch"
**Solusi:** Pastikan setiap form punya `@csrf` di baris pertama setelah tag `<form>`.

---

*Selamat mengerjakan! Jika ada error, baca pesan errornya dengan teliti dan cocokkan dengan file yang disebutkan.* 🚀
