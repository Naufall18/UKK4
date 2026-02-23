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
                <table class="table table-striped table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $i => $r)
                            <tr>
                                <td class="ps-3">{{ $riwayats->firstItem() + $i }}</td>
                                <td><strong>{{ $r->buku->judul }}</strong></td>
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
                                        <span class="text-danger fw-bold">Rp {{ number_format($r->denda, 0, ',', '.') }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada riwayat peminjaman. <a href="{{ route('siswa.pinjam.index') }}">Pinjam buku
                                        sekarang!</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($riwayats->hasPages())
            <div class="card-footer bg-transparent">
                {{ $riwayats->links() }}
            </div>
        @endif
    </div>
@endsection