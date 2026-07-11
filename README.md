<div align="center">

# 📚 Perpustakaan Digital — Backend API

**REST API untuk sistem perpustakaan sekolah — autentikasi, katalog buku, peminjaman, dan denda. Dibangun dengan Laravel 10 + Sanctum.**

![Laravel](https://img.shields.io/badge/Laravel%2010-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP%208.1-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Sanctum](https://img.shields.io/badge/Sanctum-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

</div>

---

## 📖 Tentang

REST API untuk **Perpustakaan Digital** sekolah, menyajikan data ke [aplikasi mobile Flutter](https://github.com/Naufall18/Perpustakaan-Digital_Mobile). Menangani autentikasi berbasis token (**Sanctum**) dengan verifikasi **OTP**, katalog buku, engine peminjaman (approve → pickup → kembalikan), perhitungan **denda** keterlambatan, dan akses berbasis peran (**Admin** & **Siswa**).

## ✨ Fitur

- 🔐 **Auth Sanctum** — login, register, logout, profil, dengan verifikasi **OTP** & reset password
- 📕 **Buku** — CRUD + upload cover
- 🎓 **Siswa** — dashboard, ajukan pinjam, konfirmasi pengambilan, kembalikan, riwayat
- 🛠️ **Admin** — kelola transaksi (approve/reject/pickup/mark-taken/kembalikan), bayar denda
- 👥 **Anggota** — CRUD + toggle status aktif
- ⚙️ **Pengaturan** — konfigurasi denda & aturan perpustakaan
- 📮 **Postman collection** + dokumentasi API disertakan

## 🔌 Ringkasan Endpoint

**Publik**
```
POST /api/login                POST /api/register
POST /api/send-otp             POST /api/verify-otp
POST /api/send-reset-otp       POST /api/reset-password
```

**Terproteksi** (`auth:sanctum`)
```
GET  /api/me                   POST /api/profile/update
GET  /api/buku                 POST /api/buku          (+ /{id} update, delete)

# Siswa
GET  /api/siswa/dashboard      GET  /api/siswa/riwayat
POST /api/siswa/pinjam         POST /api/siswa/transaksi/{id}/confirm-pickup

# Admin
GET  /api/admin/dashboard      GET  /api/admin/transaksi
POST /api/admin/transaksi/{id}/approve|reject|mark-taken|kembalikan|bayar-denda
GET  /api/admin/anggota        POST /api/admin/anggota  (+ update, toggle-status, delete)
GET  /api/admin/pengaturan     POST /api/admin/pengaturan
```

## 🗄️ Model

`User` · `Buku` · `Peminjaman` · `Pengaturan`

## 🚀 Menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate
# atur koneksi database di .env
php artisan migrate --seed
php artisan serve
```

**Butuh:** PHP 8.1+ · Composer · MySQL

## 🔗 Terkait

- **Mobile App:** [Perpustakaan-Digital_Mobile](https://github.com/Naufall18/Perpustakaan-Digital_Mobile) — Flutter + GetX

## 📄 Lisensi

MIT © [Naufal Dwi Arifianto](https://github.com/Naufall18)
