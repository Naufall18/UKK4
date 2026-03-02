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
            <form action="{{ route('siswa.pinjam.index') }}" method="GET" class="mb-4">
                <div class="input-group" style="max-width: 440px;">
                    <span class="input-group-text bg-transparent border-end-0"
                        style="border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0; border-right: 0;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search"
                        value="{{ request('search') }}" placeholder="Cari judul, pengarang, kategori..."
                        style="border-left: 0;">
                    @if(request('search'))
                        <a href="{{ route('siswa.pinjam.index') }}"
                            class="btn btn-outline-danger btn-sm d-flex align-items-center"
                            style="border-radius: 0 10px 10px 0;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            <div class="row g-3">
                @forelse($bukus as $buku)
                    <div class="col-md-6 col-lg-4">
                        <div class="book-card">
                            <div class="book-card-header">
                                @if($buku->cover)
                                    <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul }}"
                                        class="book-cover-card">
                                @else
                                    <div class="book-cover-placeholder">
                                        <i class="bi bi-journal-bookmark"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="book-title">{{ $buku->judul }}</h6>
                                    <p class="book-author"><i class="bi bi-person me-1"></i>{{ $buku->pengarang }}</p>
                                </div>
                            </div>
                            <div class="book-meta">
                                <div><i class="bi bi-building me-1"></i>{{ $buku->penerbit }} ({{ $buku->tahun }})</div>
                                <div class="mt-2 d-flex gap-1">
                                    <span class="badge bg-indigo-soft">{{ $buku->kategori }}</span>
                                    <span class="badge bg-emerald-soft">Stok: {{ $buku->stok }}</span>
                                </div>
                            </div>
                            <form action="{{ route('siswa.pinjam.store') }}" method="POST"
                                onsubmit="return confirm('Pinjam buku {{ addslashes($buku->judul) }}?')">
                                @csrf
                                <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                <button type="submit" class="btn btn-primary btn-sm w-100 mt-3">
                                    <i class="bi bi-bookmark-plus me-1"></i>Pinjam Buku
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                            <p class="mt-2">Tidak ada buku yang tersedia saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $bukus->links() }}
            </div>
        </div>
    </div>

    <style>
        .book-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease;
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            border-color: rgba(99, 102, 241, 0.15);
        }

        .book-card-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 12px;
        }

        .book-cover-card {
            width: 52px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #f1f5f9;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .book-cover-placeholder {
            width: 52px;
            height: 70px;
            border-radius: 8px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .book-title {
            font-weight: 600;
            font-size: 14.5px;
            color: #1e293b;
            margin: 0 0 2px;
            line-height: 1.3;
        }

        .book-author {
            font-size: 12.5px;
            color: #94a3b8;
            margin: 0;
        }

        .book-meta {
            font-size: 12.5px;
            color: #64748b;
            flex-grow: 1;
        }

        .bg-indigo-soft {
            background: #eef2ff;
            color: #4f46e5;
            font-weight: 600;
        }

        .bg-emerald-soft {
            background: #ecfdf5;
            color: #059669;
            font-weight: 600;
        }
    </style>
@endsection