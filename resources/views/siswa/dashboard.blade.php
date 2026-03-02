@extends('layouts.app')
@section('title', 'Dashboard Siswa - Perpustakaan Digital')
@section('page-title', 'Dashboard Siswa')

@section('content')
    <!-- Welcome Banner -->
    <div class="welcome-banner mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Halo, {{ auth()->user()->name }}! 📚</h4>
                <p class="mb-0">Jelajahi koleksi dan kelola peminjaman bukumu di sini.</p>
            </div>
            <div class="banner-date">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="bi bi-book"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $bukuDipinjam }}</h3>
                    <p>Buku Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalPeminjaman }}</h3>
                    <p>Total Peminjaman</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-info">
                    <h3>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                    <p>Total Denda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku yang sedang dipinjam -->
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-bookmark-check me-2"></i>Buku yang Sedang Dipinjam</span>
            <a href="{{ route('siswa.pinjam.index') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Pinjam Buku
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Sisa Hari</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanAktif as $p)
                            @php
                                $sisaHari = now()->startOfDay()->diffInDays($p->tgl_kembali_rencana, false);
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="book-icon-sm">
                                            <i class="bi bi-journal-text"></i>
                                        </div>
                                        <strong>{{ $p->buku->judul }}</strong>
                                    </div>
                                </td>
                                <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</td>
                                <td>
                                    @if($sisaHari < 0)
                                        <span class="badge bg-rose-soft">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Terlambat {{ abs($sisaHari) }} hari
                                        </span>
                                    @elseif($sisaHari <= 2)
                                        <span class="badge bg-amber-soft">
                                            <i class="bi bi-clock me-1"></i>{{ $sisaHari }} hari
                                        </span>
                                    @else
                                        <span class="badge bg-emerald-soft">
                                            <i class="bi bi-check-circle me-1"></i>{{ $sisaHari }} hari
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-book"
                                        style="font-size: 36px; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
                                    Tidak ada buku yang sedang dipinjam.
                                    <br><a href="{{ route('siswa.pinjam.index') }}" class="mt-2 d-inline-block"
                                        style="font-size: 13px;">Pinjam sekarang!</a>
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

        .book-icon-sm {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #eef2ff;
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .bg-rose-soft {
            background: #fff1f2;
            color: #e11d48;
            font-weight: 600;
        }

        .bg-amber-soft {
            background: #fffbeb;
            color: #b45309;
            font-weight: 600;
        }

        .bg-emerald-soft {
            background: #ecfdf5;
            color: #059669;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .welcome-banner .banner-date {
                display: none;
            }
        }
    </style>
@endsection