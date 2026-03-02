@extends('layouts.app')
@section('title', 'Riwayat Peminjaman - Perpustakaan Digital')
@section('page-title', 'Riwayat Peminjaman')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Saya
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3" style="width: 50px;">#</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $i => $r)
                            <tr>
                                <td class="ps-3">{{ $riwayats->firstItem() + $i }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($r->buku->cover)
                                            <img src="{{ asset('storage/' . $r->buku->cover) }}" alt="{{ $r->buku->judul }}"
                                                class="book-cover-thumb">
                                        @else
                                            <div class="book-cover-placeholder">
                                                <i class="bi bi-journal-text"></i>
                                            </div>
                                        @endif
                                        <strong>{{ $r->buku->judul }}</strong>
                                    </div>
                                </td>
                                <td>{{ $r->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $r->tgl_kembali_rencana->format('d/m/Y') }}</td>
                                <td>{{ $r->tgl_kembali_aktual ? $r->tgl_kembali_aktual->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $r->status }}">
                                        {{ ucfirst($r->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($r->denda > 0)
                                        <span class="text-danger fw-bold" style="font-size: 13px;">Rp
                                            {{ number_format($r->denda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    @if($r->status === 'dipinjam')
                                        <form action="{{ route('siswa.riwayat.kembalikan', $r->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light border"
                                                onclick="return confirm('Kembalikan buku ini sekarang?')">
                                                <i class="bi bi-box-arrow-in-down me-1"></i>Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-light border text-muted" disabled>
                                            <i class="bi bi-check2-all me-1"></i>Selesai
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-clock"
                                        style="font-size: 36px; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
                                    Belum ada riwayat peminjaman.
                                    <br><a href="{{ route('siswa.pinjam.index') }}" class="mt-2 d-inline-block"
                                        style="font-size: 13px;">Pinjam buku sekarang!</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($riwayats->hasPages())
            <div class="card-footer bg-transparent" style="padding: 16px 22px;">
                {{ $riwayats->links() }}
            </div>
        @endif
    </div>

    <style>
        .book-cover-thumb {
            width: 38px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #f1f5f9;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .book-cover-placeholder {
            width: 38px;
            height: 50px;
            border-radius: 6px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
    </style>
@endsection