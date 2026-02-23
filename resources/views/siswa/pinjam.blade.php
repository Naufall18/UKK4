@extends('layouts.app')
@section('title', 'Pinjam Buku - Perpustakaan Digital')
@section('page-title', 'Pinjam Buku')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <i class="bi bi-bookmark-plus-fill me-2"></i>Daftar Buku Tersedia
        </div>
        <div class="card-body">
            <!-- Search -->
            <form action="{{ route('siswa.pinjam.index') }}" method="GET" class="mb-3">
                <div class="input-group" style="max-width: 360px;">
                    <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul, pengarang, kategori...">
                    <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('siswa.pinjam.index') }}" class="btn btn-sm btn-outline-danger"><i
                                class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>

            <div class="row g-3">
                @forelse($bukus as $buku)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100" style="border: 1px solid #eee; border-radius: 10px;">
                            <div class="card-body">
                                <h6 class="card-title mb-1" style="font-weight: 600;">{{ $buku->judul }}</h6>
                                <p class="text-muted mb-2" style="font-size: 13px;">
                                    <i class="bi bi-person me-1"></i>{{ $buku->pengarang }}
                                </p>
                                <div style="font-size: 13px; color: #666;">
                                    <div><i class="bi bi-building me-1"></i>{{ $buku->penerbit }} ({{ $buku->tahun }})</div>
                                    <div class="mt-1">
                                        <span class="badge bg-secondary">{{ $buku->kategori }}</span>
                                        <span class="badge bg-success ms-1">Stok: {{ $buku->stok }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 pt-0">
                                <form action="{{ route('siswa.pinjam.store') }}" method="POST"
                                    onsubmit="return confirm('Pinjam buku {{ addslashes($buku->judul) }}?')">
                                    @csrf
                                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-bookmark-plus me-1"></i>Pinjam
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size: 48px;"></i>
                            <p class="mt-2">Tidak ada buku yang tersedia saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">
                {{ $bukus->links() }}
            </div>
        </div>
    </div>
@endsection