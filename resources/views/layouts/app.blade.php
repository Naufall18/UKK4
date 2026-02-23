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

        .stat-card .stat-info h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
        }

        .stat-card .stat-info p {
            margin: 0;
            font-size: 13px;
            color: #888;
        }

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

        .card-custom .card-body {
            padding: 20px;
        }

        /* Table */
        .table th {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
            font-size: 14px;
        }

        /* Status badges */
        .badge-dipinjam {
            background: #f39c12;
            color: #fff;
        }

        .badge-dikembalikan {
            background: #27ae60;
            color: #fff;
        }

        .badge-terlambat {
            background: #e74c3c;
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
            }

            .main-content {
                margin-left: 0;
            }
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