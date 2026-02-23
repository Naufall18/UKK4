@extends('layouts.app')
@section('title', 'Kelola Anggota - Perpustakaan Digital')
@section('page-title', 'Kelola Anggota')

@section('content')
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people-fill me-2"></i>Daftar Anggota</span>
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Anggota
            </a>
        </div>
        <div class="card-body">
            <!-- Search -->
            <form action="{{ route('admin.anggota.index') }}" method="GET" class="mb-3">
                <div class="input-group" style="max-width: 360px;">
                    <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NIS, kelas...">
                    <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.anggota.index') }}" class="btn btn-sm btn-outline-danger"><i
                                class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>No. Anggota</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggotas as $i => $anggota)
                            <tr>
                                <td>{{ $anggotas->firstItem() + $i }}</td>
                                <td><strong>{{ $anggota->name }}</strong></td>
                                <td>{{ $anggota->nis }}</td>
                                <td>{{ $anggota->kelas }}</td>
                                <td><code>{{ $anggota->no_anggota }}</code></td>
                                <td>{{ $anggota->no_hp ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $anggota->status_aktif ? 'bg-success' : 'bg-danger' }}">
                                        {{ $anggota->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.anggota.edit', $anggota) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.anggota.destroy', $anggota) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus anggota ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data anggota.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $anggotas->links() }}
        </div>
    </div>
@endsection