# DOKUMENTASI LENGKAP PROJECT
# Aplikasi Perpustakaan Sekolah Digital
## Laravel 10 + Bootstrap 5 + MySQL

---

# DAFTAR ISI

1. [Pendahuluan](#bab-1-pendahuluan)
2. [Persiapan & Setup Project](#bab-2-persiapan--setup-project)
3. [Backend — Database Layer](#bab-3-backend--database-layer)
4. [Backend — Model & Relasi](#bab-4-backend--model--relasi)
5. [Backend — Middleware & Autentikasi](#bab-5-backend--middleware--autentikasi)
6. [Backend — Controller](#bab-6-backend--controller)
7. [Backend — Routing](#bab-7-backend--routing)
8. [Backend — Konfigurasi](#bab-8-backend--konfigurasi)
9. [Frontend — Layout](#bab-9-frontend--layout)
10. [Frontend — Halaman Auth](#bab-10-frontend--halaman-auth)
11. [Frontend — Halaman Admin](#bab-11-frontend--halaman-admin)
12. [Frontend — Halaman Siswa](#bab-12-frontend--halaman-siswa)
13. [Cara Menjalankan](#bab-13-cara-menjalankan)

---

# BAB 1: PENDAHULUAN

## 1.1 Tentang Aplikasi
Aplikasi **Perpustakaan Sekolah Digital** adalah sistem informasi perpustakaan berbasis web yang dibangun menggunakan **Laravel 10**. Aplikasi ini memiliki 2 role pengguna: **Admin** (mengelola data) dan **Siswa** (meminjam buku).

## 1.2 Fitur Utama
- Login & Register dengan role-based access
- CRUD Buku (Admin)
- CRUD Anggota/Siswa (Admin)
- Peminjaman & Pengembalian Buku
- Perhitungan Denda Otomatis (Rp 1.000/hari)
- Dashboard Statistik
- Pencarian & Pagination

## 1.3 Tech Stack
| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 10 |
| PHP | 8.1+ |
| Database | MySQL 8.0 |
| Frontend | Blade + Bootstrap 5 CDN |
| Icons | Bootstrap Icons CDN |
| Server | Laragon |

## 1.4 Struktur Folder Project
```
paket44/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── BukuController.php
│   │   │   │   ├── AnggotaController.php
│   │   │   │   └── TransaksiController.php
│   │   │   └── Siswa/
│   │   │       ├── DashboardController.php
│   │   │       ├── PinjamController.php
│   │   │       └── RiwayatController.php
│   │   ├── Kernel.php
│   │   └── Middleware/
│   │       ├── IsAdmin.php
│   │       └── IsSiswa.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Buku.php
│   │   └── Peminjaman.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_bukus_table.php
│   │   └── 2024_01_01_000002_create_peminjamen_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/ (app.blade.php, auth.blade.php)
│   ├── auth/ (login.blade.php, register.blade.php)
│   ├── admin/ (dashboard, buku/, anggota/, transaksi/)
│   └── siswa/ (dashboard, pinjam, riwayat)
├── routes/web.php
└── .env
```

---

# BAB 2: PERSIAPAN & SETUP PROJECT

## 2.1 Install Laravel (via Composer)
```bash
composer create-project laravel/laravel paket44
```

## 2.2 Konfigurasi .env
Buka file `.env` dan ubah bagian berikut:
```env
APP_NAME="Perpustakaan Digital"
APP_URL=http://paket44.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

## 2.3 Buat Database
```sql
CREATE DATABASE perpustakaan;
```

---

# BAB 3: BACKEND — DATABASE LAYER

## 3.1 Migration: Tabel Users
**File:** `database/migrations/2014_10_12_000000_create_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'siswa'])->default('siswa');
            $table->string('nis')->nullable();
            $table->string('kelas')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_anggota')->nullable();
            $table->boolean('status_aktif')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

**Penjelasan:**
- `username` — untuk login, harus unik
- `role` — enum 'admin' atau 'siswa', default 'siswa'
- `nis` — Nomor Induk Siswa (nullable, hanya siswa)
- `no_anggota` — Nomor anggota perpustakaan, auto-generated
- `status_aktif` — boolean, default aktif

---

## 3.2 Migration: Tabel Bukus
**File:** `database/migrations/2024_01_01_000001_create_bukus_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->string('tahun', 4);
            $table->string('kategori');
            $table->integer('stok')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
```

---

## 3.3 Migration: Tabel Peminjamen
**File:** `database/migrations/2024_01_01_000002_create_peminjamen_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali_rencana');
            $table->date('tgl_kembali_aktual')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->integer('denda')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
```

**Penjelasan:**
- `foreignId('user_id')` — relasi ke tabel users (siapa yang pinjam)
- `foreignId('buku_id')` — relasi ke tabel bukus (buku apa yang dipinjam)
- `onDelete('cascade')` — jika user/buku dihapus, data peminjaman ikut terhapus
- `status` — enum: dipinjam, dikembalikan, terlambat
- `denda` — otomatis dihitung saat pengembalian (Rp 1.000 × hari terlambat)

---

## 3.4 Seeder: Data Awal
**File:** `database/seeders/DatabaseSeeder.php`

```php
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
```

---

# BAB 4: BACKEND — MODEL & RELASI

## 4.1 Model User
**File:** `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role',
        'nis', 'kelas', 'no_hp', 'no_anggota', 'status_aktif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'status_aktif' => 'boolean',
    ];

    // Relasi: 1 user punya banyak peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Cek apakah user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Cek apakah user adalah siswa
    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}
```

## 4.2 Model Buku
**File:** `app/Models/Buku.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'pengarang', 'penerbit', 'tahun', 'kategori', 'stok',
    ];

    // Relasi: 1 buku bisa dipinjam berkali-kali
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
```

## 4.3 Model Peminjaman
**File:** `app/Models/Peminjaman.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Nama tabel harus eksplisit karena plural bahasa Indonesia
    protected $table = 'peminjamen';

    protected $fillable = [
        'user_id', 'buku_id', 'tgl_pinjam',
        'tgl_kembali_rencana', 'tgl_kembali_aktual', 'status', 'denda',
    ];

    // Cast kolom date agar bisa pakai Carbon
    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali_aktual' => 'date',
    ];

    // Relasi: peminjaman milik 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: peminjaman untuk 1 buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
```

### Diagram Relasi (ERD)
```
┌──────────┐       ┌──────────────┐       ┌──────────┐
│  users   │       │  peminjamen  │       │  bukus   │
├──────────┤       ├──────────────┤       ├──────────┤
│ id (PK)  │──1:N──│ user_id (FK) │       │ id (PK)  │
│ name     │       │ buku_id (FK) │──N:1──│ judul    │
│ username │       │ tgl_pinjam   │       │ pengarang│
│ role     │       │ status       │       │ stok     │
└──────────┘       │ denda        │       └──────────┘
                   └──────────────┘
```

---

# BAB 5: BACKEND — MIDDLEWARE & AUTENTIKASI

## 5.1 Middleware IsAdmin
**File:** `app/Http/Middleware/IsAdmin.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang diizinkan.');
        }
        return $next($request);
    }
}
```

## 5.2 Middleware IsSiswa
**File:** `app/Http/Middleware/IsSiswa.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isSiswa()) {
            abort(403, 'Akses ditolak. Hanya siswa yang diizinkan.');
        }
        return $next($request);
    }
}
```

## 5.3 Registrasi Middleware di Kernel.php
**File:** `app/Http/Kernel.php` — tambahkan di `$middlewareAliases`:

```php
protected $middlewareAliases = [
    // ... middleware bawaan Laravel ...
    'isAdmin' => \App\Http\Middleware\IsAdmin::class,
    'isSiswa' => \App\Http\Middleware\IsSiswa::class,
];
```

## 5.4 AuthController (Login, Register, Logout)
**File:** `app/Http/Controllers/AuthController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nis' => 'required|string|max:20',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'nullable|string|max:20',
        ]);

        // Auto-generate nomor anggota: SIS-001, SIS-002, dst.
        $lastAnggota = User::where('role', 'siswa')
            ->orderBy('id', 'desc')
            ->first();
        $nextNum = $lastAnggota ? (intval(substr($lastAnggota->no_anggota, 4)) + 1) : 1;
        $noAnggota = 'SIS-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            'no_anggota' => $noAnggota,
            'status_aktif' => true,
        ]);

        Auth::login($user);

        return redirect('/siswa/dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }

    private function redirectByRole()
    {
        if (Auth::user()->isAdmin()) {
            return redirect('/admin/dashboard');
        }
        return redirect('/siswa/dashboard');
    }
}
```

---

# BAB 6: BACKEND — CONTROLLER

Lanjutan kode controller ada di file **DOKUMENTASI_PART2.md**
