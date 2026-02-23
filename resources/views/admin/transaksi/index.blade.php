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
                <div class="d-flex gap-2 flex-wrap">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control form-control-sm" name="search"
                            value="{{ request('search') }}" placeholder="Cari peminjam atau buku...">
                        <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                    <select name="status" class="form-select form-select-sm" style="max-width: 180px;"
                        onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan
                        </option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-danger"><i
                                class="bi bi-x-lg"></i> Reset</a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $i => $t)
                            <tr>
                                <td>{{ $transaksis->firstItem() + $i }}</td>
                                <td>
                                    <strong>{{ $t->user->name }}</strong>
                                    <br><small class="text-muted">{{ $t->user->no_anggota }}</small>
                                </td>
                                <td>{{ $t->buku->judul }}</td>
                                <td>{{ $t->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $t->tgl_kembali_rencana->format('d/m/Y') }}</td>
                                <td>{{ $t->tgl_kembali_aktual ? $t->tgl_kembali_aktual->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $t->status }}">
                                        {{ ucfirst($t->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($t->denda > 0)
                                        <span class="text-danger fw-bold">Rp {{ number_format($t->denda, 0, ',', '.') }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($t->status === 'dipinjam')
                                        <form action="{{ route('admin.transaksi.kembalikan', $t->id) }}" method="POST"
                                            onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check2"></i> Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Belum ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $transaksis->links() }}
        </div>
    </div>
@endsection