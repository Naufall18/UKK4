# 📖 DOKUMENTASI LENGKAP — Perpustakaan Digital

**Panduan Pembuatan Aplikasi Web Perpustakaan dari Awal Sampai Akhir**

> Dokumen ini menjelaskan secara detail seluruh proses pembuatan aplikasi perpustakaan digital berbasis Laravel 10,
> mulai dari setup project, pembuatan database, backend (controllers, models, middleware), hingga frontend (views, layouts, CSS).

---

## Daftar Isi

1. [Setup Project](#1-setup-project-laravel)
2. [Desain Database](#2-desain-database)
3. [Models (Eloquent)](#3-models-eloquent)
4. [Middleware](#4-middleware-role-based-access)
5. [Routing](#5-routing)
6. [Controllers — Backend Logic](#6-controllers--backend-logic)
7. [Views — Frontend](#7-views--frontend)
8. [Desain UI & CSS](#8-desain-ui--css)

---

## 1. Setup Project Laravel

### 1.1 Buat Project Baru

```bash
# Pastikan Composer sudah terinstall
composer create-project laravel/laravel paket44

cd paket44
```

### 1.2 Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paket44
DB_USERNAME=root
DB_PASSWORD=
```

### 1.3 Buat Database

Buka MySQL (via Laragon/phpMyAdmin) dan buat database:

```sql
CREATE DATABASE paket44;
```

### 1.4 Storage Link

```bash
php artisan storage:link
```

Perintah ini membuat symbolic link dari `public/storage` ke `storage/app/public` agar file yang diupload (seperti cover buku) bisa diakses dari browser.

---

## 2. Desain Database

### 2.1 Migrasi Tabel Users

**File:** `database/migrations/2014_10_12_000000_create_users_table.php`

```php
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
    $table->boolean('status_aktif')->default(true);
    $table->rememberToken();
    $table->timestamps();
});
```

**Penjelasan kolom:**
- `role` — Menentukan jenis pengguna (admin atau siswa)
- `nis` — Nomor Induk Siswa
- `no_anggota` — Nomor anggota perpustakaan (otomatis: SIS-001, SIS-002, ...)
- `status_aktif` — Status keanggotaan (aktif/nonaktif)

### 2.2 Migrasi Tabel Buku

**File:** `database/migrations/2024_01_01_000001_create_bukus_table.php`

```php
Schema::create('bukus', function (Blueprint $table) {
    $table->id();
    $table->string('judul');
    $table->string('pengarang');
    $table->string('penerbit');
    $table->string('tahun', 4);
    $table->string('kategori');
    $table->integer('stok')->default(0);
    $table->string('cover')->nullable();  // path cover image
    $table->timestamps();
});
```

### 2.3 Migrasi Tabel Peminjaman

**File:** `database/migrations/2024_01_01_000002_create_peminjamen_table.php`

```php
Schema::create('peminjamen', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('buku_id')->constrained('bukus')->onDelete('cascade');
    $table->date('tgl_pinjam');
    $table->date('tgl_kembali_rencana');
    $table->date('tgl_kembali_aktual')->nullable();
    $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
    $table->integer('denda')->default(0);
    $table->timestamps();
});
```

**Penjelasan relasi:**
- `user_id` → Foreign key ke tabel `users`
- `buku_id` → Foreign key ke tabel `bukus`
- `tgl_kembali_rencana` → Otomatis 7 hari setelah tanggal pinjam
- `denda` → Dihitung Rp 1.000/hari keterlambatan

### 2.4 Jalankan Migrasi

```bash
php artisan migrate
```

---

## 3. Models (Eloquent)

### 3.1 Model User

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
        'name', 'username', 'email', 'password',
        'role', 'nis', 'kelas', 'no_hp',
        'no_anggota', 'status_aktif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'status_aktif' => 'boolean',
    ];

    // Relasi: User memiliki banyak peminjaman
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

### 3.2 Model Buku

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
        'judul', 'pengarang', 'penerbit',
        'tahun', 'kategori', 'stok', 'cover',
    ];

    // Relasi: Buku memiliki banyak peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
```

### 3.3 Model Peminjaman

**File:** `app/Models/Peminjaman.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamen';

    protected $fillable = [
        'user_id', 'buku_id', 'tgl_pinjam',
        'tgl_kembali_rencana', 'tgl_kembali_aktual',
        'status', 'denda',
    ];

    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali_aktual' => 'date',
    ];

    // Relasi: Peminjaman milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Peminjaman milik satu Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
```

---

## 4. Middleware (Role-Based Access)

### 4.1 Middleware IsAdmin

**File:** `app/Http/Middleware/IsAdmin.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
```

### 4.2 Middleware IsSiswa

**File:** `app/Http/Middleware/IsSiswa.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsSiswa
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isSiswa()) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
```

### 4.3 Registrasi Middleware

Tambahkan di `app/Http/Kernel.php` pada `$middlewareAliases`:

```php
protected $middlewareAliases = [
    // ...middleware bawaan Laravel...
    'isAdmin' => \App\Http\Middleware\IsAdmin::class,
    'isSiswa' => \App\Http\Middleware\IsSiswa::class,
];
```

---

## 5. Routing

**File:** `routes/web.php`

```php
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\PinjamController;
use App\Http\Controllers\Siswa\RiwayatController;
use Illuminate\Support\Facades\Route;

// Landing Page (untuk tamu)
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes — hanya untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes — memerlukan login + role admin
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/buku', BukuController::class);
    Route::resource('/anggota', AnggotaController::class);
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
});

// Siswa Routes — memerlukan login + role siswa
Route::prefix('siswa')->middleware(['auth', 'isSiswa'])->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pinjam', [PinjamController::class, 'index'])->name('pinjam.index');
    Route::post('/pinjam', [PinjamController::class, 'store'])->name('pinjam.store');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
});
```

**Penjelasan:**
- `Route::resource()` membuat otomatis route CRUD (index, create, store, edit, update, destroy)
- `middleware('guest')` — hanya bisa diakses jika belum login
- `middleware(['auth', 'isAdmin'])` — harus login DAN role admin
- `name('admin.')` — prefix nama route (contoh: `admin.buku.index`)

---

## 6. Controllers — Backend Logic

### 6.1 AuthController (Login, Register, Logout)

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

        if (Auth::attempt($request->only('username', 'password'))) {
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
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nis'      => 'required|string|max:20',
            'kelas'    => 'required|string|max:50',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        // Generate nomor anggota otomatis
        $lastAnggota = User::where('role', 'siswa')
            ->orderBy('id', 'desc')->first();
        $nextNum = $lastAnggota
            ? (intval(substr($lastAnggota->no_anggota, 4)) + 1)
            : 1;
        $noAnggota = 'SIS-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'name'        => $request->name,
            'username'    => $request->username,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'siswa',
            'nis'         => $request->nis,
            'kelas'       => $request->kelas,
            'no_hp'       => $request->no_hp,
            'no_anggota'  => $noAnggota,
            'status_aktif' => true,
        ]);

        Auth::login($user);

        return redirect('/siswa/dashboard')
            ->with('success', 'Registrasi berhasil!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Redirect berdasarkan role
    private function redirectByRole()
    {
        if (Auth::user()->isAdmin()) {
            return redirect('/admin/dashboard');
        }
        return redirect('/siswa/dashboard');
    }
}
```

### 6.2 Admin DashboardController

**File:** `app/Http/Controllers/Admin/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'siswa')->count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $pengembalianHariIni = Peminjaman::where('status', 'dikembalikan')
            ->whereDate('tgl_kembali_aktual', Carbon::today())
            ->count();

        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        return view('admin.dashboard', compact(
            'totalBuku', 'totalAnggota',
            'peminjamanAktif', 'pengembalianHariIni',
            'peminjamanTerbaru'
        ));
    }
}
```

### 6.3 BukuController (CRUD + Upload Cover)

**File:** `app/Http/Controllers/Admin/BukuController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $bukus = $query->orderBy('judul')->paginate(10);
        return view('admin.buku.index', compact('bukus'));
    }

    public function create()
    {
        return view('admin.buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'tahun'     => 'required|string|max:4',
            'kategori'  => 'required|string|max:100',
            'stok'      => 'required|integer|min:0',
            'cover'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('cover');

        // Upload cover jika ada
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($data);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('admin.buku.edit', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit'  => 'required|string|max:255',
            'tahun'     => 'required|string|max:4',
            'kategori'  => 'required|string|max:100',
            'stok'      => 'required|integer|min:0',
            'cover'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('cover');

        if ($request->hasFile('cover')) {
            // Hapus cover lama
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }
        $buku->delete();

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
```

### 6.4 AnggotaController (CRUD Anggota)

**File:** `app/Http/Controllers/Admin/AnggotaController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('no_anggota', 'like', "%{$search}%");
            });
        }

        $anggotas = $query->orderBy('name')->paginate(10);
        return view('admin.anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nis'      => 'required|string|max:20',
            'kelas'    => 'required|string|max:50',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        // Generate nomor anggota otomatis
        $lastAnggota = User::where('role', 'siswa')
            ->orderBy('id', 'desc')->first();
        $nextNum = $lastAnggota
            ? (intval(substr($lastAnggota->no_anggota, 4)) + 1) : 1;
        $noAnggota = 'SIS-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'siswa',
            'nis'          => $request->nis,
            'kelas'        => $request->kelas,
            'no_hp'        => $request->no_hp,
            'no_anggota'   => $noAnggota,
            'status_aktif' => true,
        ]);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(User $anggotum)
    {
        return view('admin.anggota.edit', ['anggota' => $anggotum]);
    }

    public function update(Request $request, User $anggotum)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:255|unique:users,username,' . $anggotum->id,
            'email'       => 'required|email|max:255|unique:users,email,' . $anggotum->id,
            'nis'         => 'required|string|max:20',
            'kelas'       => 'required|string|max:50',
            'no_hp'       => 'nullable|string|max:20',
            'status_aktif' => 'required|boolean',
        ]);

        $data = $request->only(['name', 'username', 'email', 'nis', 'kelas', 'no_hp', 'status_aktif']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $anggotum->update($data);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(User $anggotum)
    {
        $anggotum->delete();

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
```

### 6.5 TransaksiController (Peminjaman & Pengembalian)

**File:** `app/Http/Controllers/Admin/TransaksiController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan.');
        }

        $today = Carbon::today();
        $denda = 0;

        // Hitung denda jika terlambat (Rp 1.000 per hari)
        if ($today->gt($peminjaman->tgl_kembali_rencana)) {
            $hariTerlambat = $today->diffInDays($peminjaman->tgl_kembali_rencana);
            $denda = $hariTerlambat * 1000;
        }

        $peminjaman->update([
            'tgl_kembali_aktual' => $today,
            'status'             => 'dikembalikan',
            'denda'              => $denda,
        ]);

        // Kembalikan stok buku +1
        $peminjaman->buku->increment('stok');

        $message = 'Buku berhasil dikembalikan.';
        if ($denda > 0) {
            $message .= ' Denda: Rp ' . number_format($denda, 0, ',', '.');
        }

        return back()->with('success', $message);
    }
}
```

### 6.6 Siswa DashboardController

**File:** `app/Http/Controllers/Siswa/DashboardController.php`

```php
<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $bukuDipinjam = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')->count();

        $totalPeminjaman = Peminjaman::where('user_id', $userId)->count();

        $totalDenda = Peminjaman::where('user_id', $userId)->sum('denda');

        $peminjamanAktif = Peminjaman::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->orderBy('tgl_kembali_rencana')->get();

        return view('siswa.dashboard', compact(
            'bukuDipinjam', 'totalPeminjaman',
            'totalDenda', 'peminjamanAktif'
        ));
    }
}
```

### 6.7 PinjamController (Proses Peminjaman Buku)

**File:** `app/Http/Controllers/Siswa/PinjamController.php`

```php
<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinjamController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::where('stok', '>', 0);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $bukus = $query->orderBy('judul')->paginate(10);
        return view('siswa.pinjam', compact('bukus'));
    }

    public function store(Request $request)
    {
        $request->validate(['buku_id' => 'required|exists:bukus,id']);

        $userId = Auth::id();
        $bukuId = $request->buku_id;

        // Cek apakah sudah meminjam buku yang sama
        $sudahPinjam = Peminjaman::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda masih meminjam buku ini.');
        }

        $buku = Buku::findOrFail($bukuId);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        // Buat peminjaman baru
        Peminjaman::create([
            'user_id'             => $userId,
            'buku_id'             => $bukuId,
            'tgl_pinjam'          => Carbon::today(),
            'tgl_kembali_rencana' => Carbon::today()->addDays(7),
            'status'              => 'dipinjam',
        ]);

        // Kurangi stok buku
        $buku->decrement('stok');

        return back()->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam.');
    }
}
```

---

## 7. Views — Frontend

### 7.1 Struktur Layout

Aplikasi menggunakan 2 layout utama:

| Layout | File | Dipakai untuk |
|--------|------|---------------|
| Auth Layout | `layouts/auth.blade.php` | Login & Register |
| App Layout | `layouts/app.blade.php` | Dashboard admin & siswa |

Setiap view menggunakan `@extends('layouts.xxx')` dan `@section('content')`.

### 7.2 Contoh View — Login

**File:** `resources/views/auth/login.blade.php`

```blade
@extends('layouts.auth')
@section('title', 'Login - Perpustakaan Digital')

@section('content')
    <h5 class="mb-4 text-center" style="font-weight: 600;">Masuk ke Akun</h5>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                value="{{ old('username') }}" required autofocus>
            @error('username')
                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>
    </form>

    <p class="text-center mt-3" style="font-size: 13px;">
        Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
    </p>
@endsection
```

### 7.3 Cara Buku Menampilkan Cover

```blade
{{-- Jika buku punya cover --}}
@if($buku->cover)
    <img src="{{ asset('storage/' . $buku->cover) }}"
         alt="{{ $buku->judul }}"
         style="width: 42px; height: 56px; object-fit: cover; border-radius: 6px;">
@else
    {{-- Placeholder jika tidak ada cover --}}
    <div class="book-cover-placeholder">
        <i class="bi bi-journal-text"></i>
    </div>
@endif
```

---

## 8. Desain UI & CSS

### 8.1 Konsep Desain

- **Modern & Elegant** — Warna gelap (dark slate) + aksen indigo
- **Glassmorphism** — Efek blur transparan pada card dan topbar
- **Micro-animations** — Hover, fade-in, floating elements
- **Font Inter** — Tipografi modern dari Google Fonts
- **Rounded corners** — Border radius konsisten (12-16px)
- **Soft badges** — Warna pastel untuk status label

### 8.2 Palet Warna

| Nama | Hex | Penggunaan |
|------|-----|------------|
| Dark Slate | `#1e293b` | Sidebar, heading |
| Indigo | `#4f46e5` `#6366f1` | Primary accent, buttons |
| Emerald | `#059669` | Status aktif, stok tersedia |
| Amber | `#f59e0b` | Warning, sisa hari |
| Rose | `#e11d48` | Danger, terlambat |
| Slate 50 | `#f8fafc` | Background |

### 8.3 Sidebar (App Layout)

```css
.sidebar {
    width: 260px;
    height: 100vh;
    background: linear-gradient(180deg, #1e293b, #0f172a);
    position: fixed;
    color: #94a3b8;
}

.sidebar .nav-link.active {
    background: rgba(99, 102, 241, 0.12);
    color: #fff;
    border-left: 3px solid #6366f1;
}
```

### 8.4 Glassmorphic Topbar

```css
.topbar {
    backdrop-filter: blur(16px);
    background: rgba(255, 255, 255, 0.85);
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}
```

### 8.5 Stat Card

```css
.stat-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    border: 1px solid #f1f5f9;
    border-radius: 14px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.25s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
}
```

---

## 🏃 Menjalankan Project

```bash
# Pindah ke folder project
cd C:\laragon\www\paket44

# Install dependencies (pertama kali saja)
composer install

# Jalankan migrasi database
php artisan migrate

# Buat storage link
php artisan storage:link

# Jalankan development server
php artisan serve

# Buka di browser: http://127.0.0.1:8000
```

---

## 📋 Ringkasan Fitur per File

| # | File | Fungsi |
|---|------|--------|
| 1 | `AuthController.php` | Login, register, logout, redirect by role |
| 2 | `Admin\DashboardController.php` | Statistik perpustakaan |
| 3 | `Admin\BukuController.php` | CRUD buku + upload cover |
| 4 | `Admin\AnggotaController.php` | CRUD anggota perpustakaan |
| 5 | `Admin\TransaksiController.php` | Daftar peminjaman + pengembalian + hitung denda |
| 6 | `Siswa\DashboardController.php` | Statistik pribadi siswa |
| 7 | `Siswa\PinjamController.php` | Cari & pinjam buku |
| 8 | `Siswa\RiwayatController.php` | Riwayat peminjaman siswa |

---

**Dibuat dengan ❤️ — Perpustakaan Digital, Sekolah Digital**
