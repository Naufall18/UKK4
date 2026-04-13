@extends('layouts.app')
@section('title', 'Transaksi Peminjaman - Perpustakaan Digital')
@section('page-title', 'Transaksi Peminjaman')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <i class="bi bi-arrow-left-right me-2"></i>Daftar Transaksi Peminjaman
        </div>
        <div class="card-body">
            <!-- Filter & Search -->
            <form action="{{ route('admin.transaksi.index') }}" method="GET" class="mb-3">
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <div class="input-group" style="max-width: 340px;">
                        <span class="input-group-text bg-transparent border-end-0"
                            style="border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0; border-right: 0;">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" name="search"
                            value="{{ request('search') }}" placeholder="Cari peminjam atau buku..."
                            style="border-left: 0;">
                    </div>
                    <select name="status" class="form-select" style="max-width: 180px;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan
                        </option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-lg me-1"></i>Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $i => $t)
                            <tr>
                                <td>{{ $transaksis->firstItem() + $i }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm">{{ strtoupper(substr($t->user->name, 0, 1)) }}</div>
                                        <div>
                                            <strong style="font-size: 13px;">{{ $t->user->name }}</strong>
                                            <br><small class="text-muted"
                                                style="font-size: 11px;">{{ $t->user->no_anggota }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $t->buku->judul }}</strong></td>
                                <td>{{ $t->tgl_pinjam ? $t->tgl_pinjam->format('d/m/Y') : '-' }}</td>
                                <td>{{ $t->tgl_kembali_rencana ? $t->tgl_kembali_rencana->format('d/m/Y') : '-' }}</td>
                                <td>{{ $t->tgl_kembali_aktual ? $t->tgl_kembali_aktual->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $t->status }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($t->denda > 0)
                                        <span class="text-danger fw-bold" style="font-size: 13px;">Rp
                                            {{ number_format($t->denda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($t->status === 'dipinjam')
                                        <form action="{{ route('admin.transaksi.kembalikan', $t->id) }}" method="POST"
                                            onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check2 me-1"></i>Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox"
                                        style="font-size: 36px; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }
    </style>
@endsection