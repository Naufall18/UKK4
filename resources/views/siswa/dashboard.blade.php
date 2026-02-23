@extends('layouts.app')
@section('title', 'Dashboard Siswa - Perpustakaan Digital')
@section('page-title', 'Dashboard Siswa')

@section('content')
    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
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
                <div class="icon-box" style="background: linear-gradient(135deg, #3498db, #2980b9);">
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
                <div class="icon-box" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
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
            <a href="{{ route('siswa.pinjam.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Pinjam Buku
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
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
                                <td class="ps-3"><strong>{{ $p->buku->judul }}</strong></td>
                                <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</td>
                                <td>
                                    @if($sisaHari < 0)
                                        <span class="badge bg-danger">Terlambat {{ abs($sisaHari) }} hari</span>
                                    @elseif($sisaHari <= 2)
                                        <span class="badge bg-warning text-dark">{{ $sisaHari }} hari</span>
                                    @else
                                        <span class="badge bg-success">{{ $sisaHari }} hari</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Tidak ada buku yang sedang dipinjam. <a href="{{ route('siswa.pinjam.index') }}">Pinjam
                                        sekarang!</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection