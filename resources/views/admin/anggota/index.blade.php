@extends('layouts.app')
@section('title', 'Kelola Anggota - Perpustakaan Digital')
@section('page-title', 'Kelola Anggota')

@section('content')
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people-fill me-2"></i>Daftar Anggota</span>
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Anggota
            </a>
        </div>
        <div class="card-body">
            <!-- Search -->
            <form action="{{ route('admin.anggota.index') }}" method="GET" class="mb-3">
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text bg-transparent border-end-0"
                        style="border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0; border-right: 0;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search"
                        value="{{ request('search') }}" placeholder="Cari nama, NIS, kelas..." style="border-left: 0;">
                    @if(request('search'))
                        <a href="{{ route('admin.anggota.index') }}"
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
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>No. Anggota</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggotas as $i => $anggota)
                            <tr>
                                <td>{{ $anggotas->firstItem() + $i }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm">{{ strtoupper(substr($anggota->name, 0, 1)) }}</div>
                                        <strong>{{ $anggota->name }}</strong>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $anggota->nis }}</td>
                                <td><span class="badge bg-indigo-soft">{{ $anggota->kelas }}</span></td>
                                <td><code
                                        style="background: #f1f5f9; padding: 3px 8px; border-radius: 6px; color: #475569; font-size: 12px;">{{ $anggota->no_anggota }}</code>
                                </td>
                                <td class="text-muted">{{ $anggota->no_hp ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $anggota->status_aktif ? 'bg-emerald-soft' : 'bg-rose-soft' }}">
                                        <i class="bi {{ $anggota->status_aktif ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                        {{ $anggota->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.anggota.edit', $anggota) }}"
                                            class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.anggota.destroy', $anggota) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus anggota ini?')">
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
                                    <i class="bi bi-person-x"
                                        style="font-size: 36px; display: block; margin-bottom: 8px; opacity: 0.4;"></i>
                                    Belum ada data anggota.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $anggotas->links() }}
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