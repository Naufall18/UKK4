@extends('layouts.app')
@section('title', 'Kelola Buku - Perpustakaan Digital')
@section('page-title', 'Kelola Buku')

@section('content')
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-journal-bookmark-fill me-2"></i>Daftar Buku</span>
            <a href="{{ route('admin.buku.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Buku
            </a>
        </div>
        <div class="card-body">
            <!-- Search -->
            <form action="{{ route('admin.buku.index') }}" method="GET" class="mb-3">
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text bg-transparent border-end-0"
                        style="border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0; border-right: 0;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search"
                        value="{{ request('search') }}" placeholder="Cari judul, pengarang, kategori..."
                        style="border-left: 0;">
                    @if(request('search'))
                        <a href="{{ route('admin.buku.index') }}"
                            class="btn btn-outline-danger btn-sm d-flex align-items-center"
                            style="border-radius: 0 10px 10px 0;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Buku</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $i => $buku)
                            <tr>
                                <td>{{ $bukus->firstItem() + $i }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($buku->cover)
                                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul }}"
                                                class="book-cover-thumb">
                                        @else
                                            <div class="book-cover-placeholder">
                                                <i class="bi bi-journal-text"></i>
                                            </div>
                                        @endif
                                        <strong>{{ $buku->judul }}</strong>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $buku->pengarang }}</td>
                                <td class="text-muted">{{ $buku->penerbit }}</td>
                                <td>{{ $buku->tahun }}</td>
                                <td><span class="badge bg-indigo-soft">{{ $buku->kategori }}</span></td>
                                <td>
                                    <span class="badge {{ $buku->stok > 0 ? 'bg-emerald-soft' : 'bg-rose-soft' }}">
                                        {{ $buku->stok }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.buku.edit', $buku) }}" class="btn btn-sm btn-outline-warning"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.buku.destroy', $buku) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus buku ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-journal-x"
                                        style="font-size: 36px; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
                                    Belum ada data buku.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bukus->links() }}
            </div>
        </div>
    </div>

    <style>
        .book-cover-thumb {
            width: 42px;
            height: 56px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #f1f5f9;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .book-cover-placeholder {
            width: 42px;
            height: 56px;
            border-radius: 6px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
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

        .bg-rose-soft {
            background: #fff1f2;
            color: #e11d48;
            font-weight: 600;
        }
    </style>
@endsection