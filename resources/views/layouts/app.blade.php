<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ══════════════════════════════════ Sidebar ══════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .sidebar-brand {
            padding: 24px 22px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar-brand h4 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand h4 i {
            font-size: 20px;
            color: #818cf8;
        }

        .sidebar-brand small {
            display: block;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.35);
            margin-top: 4px;
            margin-left: 30px;
            font-weight: 400;
        }

        .sidebar-menu {
            list-style: none;
            padding: 12px 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 22px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            margin: 2px 0;
        }

        .sidebar-menu li a:hover {
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.04);
            border-left-color: rgba(129, 140, 248, 0.4);
        }

        .sidebar-menu li a.active {
            color: #fff;
            background: rgba(99, 102, 241, 0.12);
            border-left-color: #818cf8;
        }

        .sidebar-menu li a i {
            font-size: 17px;
            width: 22px;
            text-align: center;
            opacity: 0.75;
        }

        .sidebar-menu li a.active i,
        .sidebar-menu li a:hover i {
            opacity: 1;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            margin: 8px 16px;
        }

        /* ══════════════════════════════════ Main Content ══════════════════════════════════ */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 14px 28px;
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
            color: #0f172a;
            font-size: 16px;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .topbar .user-info .role-badge {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .content-area {
            padding: 28px;
            animation: fadeIn 0.4s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ══════════════════════════════════ Stat Cards ══════════════════════════════════ */
        .stat-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.07);
            border-color: rgba(99, 102, 241, 0.1);
        }

        .stat-card .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-card .stat-info h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-card .stat-info p {
            margin: 2px 0 0;
            font-size: 12.5px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* ══════════════════════════════════ Cards ══════════════════════════════════ */
        .card-custom {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .card-custom .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 22px;
            font-weight: 600;
            font-size: 14px;
            color: #1e293b;
        }

        .card-custom .card-body {
            padding: 22px;
        }

        .card-custom .card-footer {
            border-top: 1px solid #f1f5f9;
        }

        /* Book cards in siswa/pinjam */
        .card {
            border-radius: 14px !important;
            border: 1px solid #f1f5f9 !important;
            transition: all 0.25s ease;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            border-color: rgba(99, 102, 241, 0.12) !important;
        }

        /* ══════════════════════════════════ Tables ══════════════════════════════════ */
        .table {
            font-size: 13.5px;
        }

        .table thead th {
            font-size: 11.5px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid #f1f5f9;
            background: #fafbfd;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px 16px;
            color: #334155;
            border-bottom: 1px solid #f8fafc;
        }

        .table-hover tbody tr:hover {
            background: rgba(99, 102, 241, 0.02);
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            background-color: rgba(248, 250, 252, 0.6);
        }

        /* ══════════════════════════════════ Badges ══════════════════════════════════ */
        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.2px;
        }

        .badge-dipinjam {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-dikembalikan {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-terlambat {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ══════════════════════════════════ Form Controls ══════════════════════════════════ */
        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-label {
            font-weight: 500;
            font-size: 13px;
            color: #475569;
        }

        /* ══════════════════════════════════ Buttons ══════════════════════════════════ */
        .btn {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: #6366f1;
            border-color: #c7d2fe;
        }

        .btn-outline-primary:hover {
            background: #eef2ff;
            border-color: #6366f1;
            color: #4f46e5;
        }

        .btn-outline-warning {
            color: #d97706;
            border-color: #fde68a;
        }

        .btn-outline-warning:hover {
            background: #fffbeb;
            border-color: #f59e0b;
            color: #b45309;
        }

        .btn-outline-danger {
            color: #dc2626;
            border-color: #fecaca;
        }

        .btn-outline-danger:hover {
            background: #fef2f2;
            border-color: #ef4444;
            color: #b91c1c;
        }

        .btn-success {
            background: linear-gradient(135deg, #059669, #10b981);
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #047857, #059669);
        }

        /* ══════════════════════════════════ Alerts ══════════════════════════════════ */
        .alert {
            border-radius: 12px;
            border: none;
            font-size: 13.5px;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
        }

        /* ══════════════════════════════════ Pagination ══════════════════════════════════ */
        .page-link {
            font-size: 13px;
            color: #6366f1;
            border-radius: 8px !important;
            border: 1px solid #e2e8f0;
            margin: 0 2px;
        }

        .page-link:hover {
            background: #eef2ff;
            color: #4f46e5;
            border-color: #c7d2fe;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border-color: #4f46e5;
        }

        /* ══════════════════════════════════ Search Input Group ══════════════════════════════════ */
        .input-group .form-control {
            border-radius: 10px 0 0 10px;
        }

        .input-group .btn:last-child,
        .input-group a.btn:last-child {
            border-radius: 0 10px 10px 0;
        }

        /* ══════════════════════════════════ Responsive ══════════════════════════════════ */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 16px;
            }

            .topbar {
                padding: 12px 16px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <h4><img src="{{ asset('img/logo.png') }}" alt="Logo"
                    style="width: 28px; height: 28px; object-fit: cover; border-radius: 6px; margin-right: 6px; vertical-align: -4px;">
                Perpustakaan</h4>
            <small>Sekolah Digital</small>
        </div>
        <ul class="sidebar-menu">
            @if (auth()->user()->isAdmin())
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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
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