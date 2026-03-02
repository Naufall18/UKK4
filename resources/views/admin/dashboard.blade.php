@extends('layouts.app')
@section('title', 'Dashboard Admin - Perpustakaan Digital')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Banner -->
    <div class="welcome-banner mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Selamat Datang, {{ auth()->user()->name }}! 👋</h4>
                <p class="mb-0">Berikut ringkasan perpustakaan hari ini.</p>
            </div>
            <div class="banner-date">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalBuku }}</h3>
                    <p>Total Buku</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalAnggota }}</h3>
                    <p>Total Anggota</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $peminjamanAktif }}</h3>
                    <p>Sedang Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $pengembalianHariIni }}</h3>
                    <p>Kembali Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Peminjaman Terbaru -->
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru</span>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanTerbaru as $p)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm">{{ strtoupper(substr($p->user->name, 0, 1)) }}</div>
                                        <span>{{ $p->user->name }}</span>
                                    </div>
                                </td>
                                <td><strong>{{ $p->buku->judul }}</strong></td>
                                <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $p->status }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox"
                                        style="font-size: 36px; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
                                    Belum ada data peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .welcome-banner {
            background: linear-gradient(135deg, #4f46e5, #6366f1, #818cf8);
            border-radius: 16px;
            padding: 28px 32px;
            color: #fff;
        }

        .welcome-banner h4 {
            font-weight: 700;
            font-size: 20px;
            color: #fff;
        }

        .welcome-banner p {
            color: rgba(255, 255, 255, 0.75);
            font-size: 14px;
        }

        .welcome-banner .banner-date {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        @media (max-width: 576px) {
            .welcome-banner .banner-date {
                display: none;
            }
        }
    </style>
@endsection