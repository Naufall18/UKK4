<div align="center">
  <h1>📚 e-Library API (Backend)</h1>
  <p><strong>RESTful API for Digital Library System - UKK Project</strong></p>
  
  <p>
    <img src="https://img.shields.io/badge/Laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  </p>
</div>

---

## 📖 Deskripsi
Ini adalah repository **Backend (REST API)** untuk sistem Perpustakaan Digital, dibangun secara khusus untuk memenuhi standar proyek Uji Kompetensi Keahlian (UKK). 

Sistem ini berfungsi sebagai inti pemrosesan data (Server-Side) yang melayani request dari aplikasi mobile (Frontend) dengan respon JSON yang cepat, aman, dan terstruktur.

## ✨ Fitur Utama
- **🔐 Secure Authentication:** Dilengkapi dengan sistem autentikasi Token-based (API Tokens/JWT) untuk menjaga keamanan endpoints.
- **👥 Role Management:** Pemisahan akses endpoint yang ketat antara **Admin** (Pengelola Perpustakaan) dan **Siswa** (Anggota/Peminjam).
- **📚 Book & Inventory API:** CRUD lengkap untuk manajemen buku, kategori, dan stok.
- **🔄 Transaction Engine:** Logika pemrosesan peminjaman, pengembalian, persetujuan admin, perhitungan denda otomatis, dan riwayat transaksi.
- **📊 Analytics Data:** Endpoint khusus untuk menyuplai data statistik (grafik peminjaman, buku terpopuler) ke Dashboard.

## 🚀 Instalasi & Menjalankan API
1. Clone repository ini.
2. Buka terminal dan jalankan `composer install`.
3. Copy file `.env.example` menjadi `.env` dan atur konfigurasi database MySQL Anda.
4. Jalankan `php artisan key:generate`.
5. Jalankan migrasi dan seeder: `php artisan migrate:fresh --seed`.
6. Jalankan server lokal: `php artisan serve`.
7. API akan berjalan di `http://localhost:8000`.

---
<div align="center">
  <i>Dikembangkan dengan ❤️ oleh Naufal untuk Proyek UKK.</i>
</div>
