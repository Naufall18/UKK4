@extends('layouts.app')
@section('title', 'Kelola Buku - Perpustakaan Digital')
@section('page-title', 'Kelola Buku')

@section('content')
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-journal-bookmark-fill me-2"></i>Daftar Buku</span>
            <a href="{{ route('admin.buku.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Buku
            </a>
        </div>
        <div class="card-body">
            <!-- Search -->
            <form action="{{ route('admin.buku.index') }}" method="GET" class="mb-3">
                <div class="input-group" style="max-width: 360px;">
                    <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul, pengarang, kategori...">
                    <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-sm btn-outline-danger"><i
                                class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $i => $buku)
                            <tr>
                                <td>{{ $bukus->firstItem() + $i }}</td>
                                <td><strong>{{ $buku->judul }}</strong></td>
                                <td>{{ $buku->pengarang }}</td>
                                <td>{{ $buku->penerbit }}</td>
                                <td>{{ $buku->tahun }}</td>
                                <td><span class="badge bg-secondary">{{ $buku->kategori }}</span></td>
                                <td>
                                    <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $buku->stok }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.buku.edit', $buku) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.buku.destroy', $buku) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $bukus->links() }}
        </div>
    </div>
@endsection