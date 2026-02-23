@extends('layouts.app')
@section('title', 'Dashboard Admin - Perpustakaan Digital')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #3498db, #2980b9);">
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
                <div class="icon-box" style="background: linear-gradient(135deg, #27ae60, #219a52);">
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
                <div class="icon-box" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
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
                <div class="icon-box" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
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
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
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
                                <td class="ps-3">{{ $p->user->name }}</td>
                                <td>{{ $p->buku->judul }}</td>
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
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection