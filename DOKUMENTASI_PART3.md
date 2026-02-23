# DOKUMENTASI LENGKAP PROJECT — PART 3
# Frontend (Blade Views)

---

# BAB 9: FRONTEND — LAYOUT

## 9.1 Layout Utama (app.blade.php)
**File:** `resources/views/layouts/app.blade.php`

Layout ini digunakan oleh semua halaman setelah login. Terdiri dari **sidebar** (navigasi kiri) dan **topbar** (atas).

```html
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f4f6f8;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 240px;
            background: #2c3e50;
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand small {
            display: block;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 4px;
            margin-left: 30px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 10px 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: #3d566e;
            color: #fff;
        }

        .sidebar-menu li a i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 5px 0;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e3e6e8;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar h5 {
            margin: 0;
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #555;
        }

        .topbar .user-info .role-badge {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            background: #3498db;
            color: #fff;
            font-weight: 500;
        }

        .content-area {
            padding: 24px;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .stat-card .icon-box {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
        }

        .stat-card .stat-info h3 { margin: 0; font-size: 24px; font-weight: 700; color: #2c3e50; }
        .stat-card .stat-info p { margin: 0; font-size: 13px; color: #888; }

        .card-custom {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .card-custom .card-header {
            background: transparent;
            border-bottom: 1px solid #eee;
            padding: 16px 20px;
            font-weight: 600;
        }

        .card-custom .card-body { padding: 20px; }

        /* Table */
        .table th {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table td { vertical-align: middle; font-size: 14px; }

        /* Status badges */
        .badge-dipinjam { background: #f39c12; color: #fff; }
        .badge-dikembalikan { background: #27ae60; color: #fff; }
        .badge-terlambat { background: #e74c3c; color: #fff; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-book"></i> Perpustakaan</h4>
            <small>Sekolah Digital</small>
        </div>
        <ul class="sidebar-menu">
            @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.buku.index') }}"
                        class="{{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill"></i> Kelola Buku
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.anggota.index') }}"
                        class="{{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Kelola Anggota
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transaksi.index') }}"
                        class="{{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i> Transaksi
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('siswa.dashboard') }}"
                        class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.pinjam.index') }}"
                        class="{{ request()->routeIs('siswa.pinjam.*') ? 'active' : '' }}">
                        <i class="bi bi-bookmark-plus-fill"></i> Pinjam Buku
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.riwayat.index') }}"
                        class="{{ request()->routeIs('siswa.riwayat.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat
                    </a>
                </li>
            @endif
            <div class="sidebar-divider"></div>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit(); return false;">
                        <i class="bi bi-box-arrow-left"></i> Logout
                    </a>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h5>@yield('page-title', 'Dashboard')</h5>
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <span class="role-badge">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
```

---

## 9.2 Layout Auth (auth.blade.php)
**File:** `resources/views/layouts/auth.blade.php`

Layout khusus halaman login & register. Card putih di tengah layar.

```html
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e8ecf1 0%, #d5dde5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px 36px;
        }
        .auth-brand { text-align: center; margin-bottom: 30px; }
        .auth-brand .icon {
            width: 60px; height: 60px; background: #2c3e50;
            border-radius: 16px; display: inline-flex;
            align-items: center; justify-content: center; margin-bottom: 12px;
        }
        .auth-brand .icon i { font-size: 28px; color: #fff; }
        .auth-brand h4 { margin: 0; font-weight: 700; color: #2c3e50; }
        .auth-brand p { margin: 4px 0 0; font-size: 14px; color: #888; }
        .form-label { font-weight: 500; font-size: 14px; color: #555; }
        .form-control { border-radius: 8px; padding: 10px 14px; border: 1px solid #ddd; }
        .form-control:focus { border-color: #3498db; box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15); }
        .btn-login {
            background: #3498db; border: none; color: #fff;
            border-radius: 8px; padding: 11px; font-weight: 600;
            width: 100%; transition: background 0.2s;
        }
        .btn-login:hover { background: #2980b9; color: #fff; }
        .auth-footer { text-align: center; margin-top: 20px; font-size: 14px; color: #888; }
        .auth-footer a { color: #3498db; text-decoration: none; font-weight: 500; }
        .auth-footer a:hover { text-decoration: underline; }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="auth-brand">
            <div class="icon"><i class="bi bi-book"></i></div>
            <h4>Perpustakaan Digital</h4>
            <p>Sekolah Digital</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:14px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:14px;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
```

---

# BAB 10: FRONTEND — HALAMAN AUTH

## 10.1 Login (login.blade.php)
**File:** `resources/views/auth/login.blade.php`

```html
@extends('layouts.auth')
@section('title', 'Login - Perpustakaan Digital')

@section('content')
    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}"
                placeholder="Masukkan username" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password"
                required>
        </div>
        <button type="submit" class="btn btn-login">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>
        <div class="auth-footer">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>
    </form>
@endsection
```

## 10.2 Register (register.blade.php)
**File:** `resources/views/auth/register.blade.php`

```html
@extends('layouts.auth')
@section('title', 'Register - Perpustakaan Digital')

@section('content')
    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                placeholder="Nama lengkap" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}"
                placeholder="Username untuk login" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                placeholder="Email aktif" required>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label for="nis" class="form-label">NIS</label>
                <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis') }}" placeholder="NIS"
                    required>
            </div>
            <div class="col-6 mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas') }}"
                    placeholder="Contoh: XII RPL 1" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">No. HP <small class="text-muted">(opsional)</small></label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                placeholder="No. handphone">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter"
                required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                placeholder="Ulangi password" required>
        </div>
        <button type="submit" class="btn btn-login">
            <i class="bi bi-person-plus me-1"></i> Daftar
        </button>
        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </form>
@endsection
```

---

Lanjutan halaman Admin & Siswa ada di file **DOKUMENTASI_PART4.md**
