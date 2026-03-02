# 🚀 DOKUMENTASI REST API - Perpustakaan Digital

Dokumen ini berisi daftar endpoint API lengkap untuk integrasi dengan frontend (seperti Flutter/React). Semua endpoint berjalan di `http://127.0.0.1:8000/api` (atau `http://10.0.2.2:8000/api` jika dari emulator Android).

**Base URL:** `http://127.0.0.1:8000/api`

---

## 🔐 1. Authentication (Public)

### A. Login User
Digunakan untuk login Admin maupun Siswa.
- **Method:** `POST`
- **Endpoint:** `/login`
- **Body (form-data / raw JSON):**
  ```json
  {
      "username": "admin",
      "password": "admin123"
  }
  ```
- **Response (200 OK):** Mengembalikan `access_token` yang wajib disimpan dan digunakan di menu lainnya.

### B. Register Siswa
- **Method:** `POST`
- **Endpoint:** `/register`
- **Body (form-data / raw JSON):**
  ```json
  {
      "name": "Budi Santoso",
      "username": "budi123",
      "email": "budi@email.com",
      "password": "password",
      "nis": "54321",
      "kelas": "X RPL 1",
      "no_hp": "08123456789"
  }
  ```

---

## 🛡️ 2. Protected Routes (Butuh Token)
Semua route di bawah ini **WAJIB** menyertakan Header Authorization:
**Header:** `Authorization: Bearer <access_token>`

### A. Cek Profile Saat Ini (Gett)
- **Method:** `GET`
- **Endpoint:** `/me`

### B. Logout
- **Method:** `POST`
- **Endpoint:** `/logout`

---

## 📚 3. Buku (Books)

### A. Lihat Semua Buku (Bisa Siswa & Admin)
- **Method:** `GET`
- **Endpoint:** `/buku`
- **Query Params (Opsional):** `?search=kata_kunci` (untuk pencarian)

### B. Detail Buku
- **Method:** `GET`
- **Endpoint:** `/buku/{id}`

### C. Tambah Buku (Khusus Admin)
- **Method:** `POST`
- **Endpoint:** `/buku`
- **Tipe Body:** `multipart/form-data`
- **Form Data:**
  - `judul` (text)
  - `pengarang` (text)
  - `penerbit` (text)
  - `tahun` (text, 4 angka)
  - `kategori` (text)
  - `stok` (number)
  - `cover` (file, opsional)

### D. Edit Buku (Khusus Admin)
Penting: Untuk mengupdate gambar via API, gunakan spoofing method!
- **Method:** `POST`
- **Endpoint:** `/buku/{id}`
- **Tipe Body:** `multipart/form-data`
- **Form Data:** (Sama seperti tambah buku), **TAPI HARUS TAMBAH:**
  - `_method` = `PUT`

### E. Hapus Buku (Khusus Admin)
- **Method:** `DELETE`
- **Endpoint:** `/buku/{id}`

---

## 👨‍🎓 4. Transaksi & Dashboard Siswa (Khusus Role: Siswa)

### A. Stats Dashboard Siswa
- **Method:** `GET`
- **Endpoint:** `/siswa/dashboard`
- **Response:** Mengembalikan total buku dipinjam, riwayat, dan total denda.

### B. Riwayat Peminjaman Siswa
- **Method:** `GET`
- **Endpoint:** `/siswa/riwayat`
- **Response:** List buku yang dipinjam/dikembalikan oleh siswa tersebut.

### C. Pinjam Buku
- **Method:** `POST`
- **Endpoint:** `/siswa/pinjam`
- **Body (form-data / raw JSON):**
  ```json
  {
      "buku_id": 1 
  }
  ```

---

## 👨‍💼 5. Transaksi & Dashboard Admin (Khusus Role: Admin)

### A. Stats Dashboard Admin
- **Method:** `GET`
- **Endpoint:** `/admin/dashboard`
- **Response:** Total buku, anggota, peminjaman aktif hari ini.

### B. Lihat Semua Transaksi
- **Method:** `GET`
- **Endpoint:** `/admin/transaksi`
- **Response:** List riwayat seluruh peminjaman lengkap dengan detail nama siswa dan judul buku.

### C. Kembalikan Buku / Selesaikan Transaksi
- **Method:** `POST`
- **Endpoint:** `/admin/transaksi/{id}/kembalikan`
- Keterangan: `{id}` adalah ID dari transaksi peminjaman (Peminjaman ID), BUKAN ID buku. Sistem otomatis akan menghitung denda jika terlambat dan menambah stok buku kembali.
