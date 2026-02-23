# DOKUMENTASI LENGKAP PROJECT — PART 4
# Halaman Admin & Siswa (Blade Views)

---

# BAB 11: FRONTEND — HALAMAN ADMIN

## 11.1 Dashboard Admin
**File:** `resources/views/admin/dashboard.blade.php`

```html
@extends('layouts.app')
@section('title', 'Dashboard Admin - Perpustakaan Digital')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalBuku }}</h3>
                    <p>Total Buku</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #27ae60, #219a52);">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalAnggota }}</h3>
                    <p>Total Anggota</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $peminjamanAktif }}</h3>
                    <p>Sedang Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $pengembalianHariIni }}</h3>
                    <p>Kembali Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Peminjaman Terbaru -->
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru</span>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanTerbaru as $p)
                            <tr>
                                <td class="ps-3">{{ $p->user->name }}</td>
                                <td>{{ $p->buku->judul }}</td>
                                <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $p->status }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
```

---

## 11.2 Daftar Buku (admin/buku/index.blade.php)
**File:** `resources/views/admin/buku/index.blade.php`

```html
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
                            <th>#</th><th>Judul</th><th>Pengarang</th><th>Penerbit</th>
                            <th>Tahun</th><th>Kategori</th><th>Stok</th><th style="width: 140px;">Aksi</th>
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
```

---

## 11.3 Tambah Buku (admin/buku/create.blade.php)
**File:** `resources/views/admin/buku/create.blade.php`

```html
@extends('layouts.app')
@section('title', 'Tambah Buku - Perpustakaan Digital')
@section('page-title', 'Tambah Buku')

@section('content')
    <div class="card card-custom" style="max-width: 640px;">
        <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Form Tambah Buku</div>
        <div class="card-body">
            <form action="{{ route('admin.buku.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
                </div>
                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="pengarang" value="{{ old('pengarang') }}" required>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ old('penerbit') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="text" class="form-control" id="tahun" name="tahun" value="{{ old('tahun') }}" maxlength="4" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" value="{{ old('kategori') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', 0) }}" min="0" required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
```

---

## 11.4 Edit Buku (admin/buku/edit.blade.php)
**File:** `resources/views/admin/buku/edit.blade.php`

```html
@extends('layouts.app')
@section('title', 'Edit Buku - Perpustakaan Digital')
@section('page-title', 'Edit Buku')

@section('content')
    <div class="card card-custom" style="max-width: 640px;">
        <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Form Edit Buku</div>
        <div class="card-body">
            <form action="{{ route('admin.buku.update', $buku) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $buku->judul) }}" required>
                </div>
                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" required>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="text" class="form-control" id="tahun" name="tahun" value="{{ old('tahun', $buku->tahun) }}" maxlength="4" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" value="{{ old('kategori', $buku->kategori) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', $buku->stok) }}" min="0" required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
```

---

## 11.5 Daftar Anggota (admin/anggota/index.blade.php)
**File:** `resources/views/admin/anggota/index.blade.php`

```html
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
            <form action="{{ route('admin.anggota.index') }}" method="GET" class="mb-3">
                <div class="input-group" style="max-width: 360px;">
                    <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NIS, kelas...">
                    <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.anggota.index') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Nama</th><th>NIS</th><th>Kelas</th>
                            <th>No. Anggota</th><th>No. HP</th><th>Status</th><th style="width: 140px;">Aksi</th>
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
                                    <a href="{{ route('admin.anggota.edit', $anggota) }}" class="btn btn-sm btn-outline-warning">
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
```

---

## 11.6 Tambah Anggota (admin/anggota/create.blade.php)
**File:** `resources/views/admin/anggota/create.blade.php`

```html
@extends('layouts.app')
@section('title', 'Tambah Anggota - Perpustakaan Digital')
@section('page-title', 'Tambah Anggota')

@section('content')
    <div class="card card-custom" style="max-width: 640px;">
        <div class="card-header"><i class="bi bi-person-plus me-2"></i>Form Tambah Anggota</div>
        <div class="card-body">
            <form action="{{ route('admin.anggota.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">No. HP <small class="text-muted">(opsional)</small></label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Min 6 karakter" required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
```

---

## 11.7 Edit Anggota (admin/anggota/edit.blade.php)
**File:** `resources/views/admin/anggota/edit.blade.php`

```html
@extends('layouts.app')
@section('title', 'Edit Anggota - Perpustakaan Digital')
@section('page-title', 'Edit Anggota')

@section('content')
    <div class="card card-custom" style="max-width: 640px;">
        <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Form Edit Anggota</div>
        <div class="card-body">
            <form action="{{ route('admin.anggota.update', $anggota) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $anggota->name) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $anggota->username) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $anggota->email) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis', $anggota->nis) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas', $anggota->kelas) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $anggota->no_hp) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status_aktif" class="form-label">Status</label>
                        <select class="form-select" id="status_aktif" name="status_aktif">
                            <option value="1" {{ old('status_aktif', $anggota->status_aktif) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !old('status_aktif', $anggota->status_aktif) ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
```

---

## 11.8 Transaksi Peminjaman (admin/transaksi/index.blade.php)
**File:** `resources/views/admin/transaksi/index.blade.php`

```html
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
                    <select name="status" class="form-select form-select-sm" style="max-width: 180px;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-lg"></i> Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th>
                            <th>Batas Kembali</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th>
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
                                    <span class="badge badge-{{ $t->status }}">{{ ucfirst($t->status) }}</span>
                                </td>
                                <td>
                                    @if($t->denda > 0)
                                        <span class="text-danger fw-bold">Rp {{ number_format($t->denda, 0, ',', '.') }}</span>
                                    @else - @endif
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
                                    @else <span class="text-muted">-</span> @endif
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
```

---

# BAB 12: FRONTEND — HALAMAN SISWA

## 12.1 Dashboard Siswa
**File:** `resources/views/siswa/dashboard.blade.php`

```html
@extends('layouts.app')
@section('title', 'Dashboard Siswa - Perpustakaan Digital')
@section('page-title', 'Dashboard Siswa')

@section('content')
    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <i class="bi bi-book"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $bukuDipinjam }}</h3>
                    <p>Buku Dipinjam</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalPeminjaman }}</h3>
                    <p>Total Peminjaman</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-info">
                    <h3>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                    <p>Total Denda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku yang sedang dipinjam -->
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-bookmark-check me-2"></i>Buku yang Sedang Dipinjam</span>
            <a href="{{ route('siswa.pinjam.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Pinjam Buku
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Judul Buku</th><th>Tgl Pinjam</th>
                            <th>Batas Kembali</th><th>Sisa Hari</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanAktif as $p)
                            @php
                                $sisaHari = now()->startOfDay()->diffInDays($p->tgl_kembali_rencana, false);
                            @endphp
                            <tr>
                                <td class="ps-3"><strong>{{ $p->buku->judul }}</strong></td>
                                <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $p->tgl_kembali_rencana->format('d/m/Y') }}</td>
                                <td>
                                    @if($sisaHari < 0)
                                        <span class="badge bg-danger">Terlambat {{ abs($sisaHari) }} hari</span>
                                    @elseif($sisaHari <= 2)
                                        <span class="badge bg-warning text-dark">{{ $sisaHari }} hari</span>
                                    @else
                                        <span class="badge bg-success">{{ $sisaHari }} hari</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Tidak ada buku yang sedang dipinjam.
                                    <a href="{{ route('siswa.pinjam.index') }}">Pinjam sekarang!</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
```

---

## 12.2 Pinjam Buku (siswa/pinjam.blade.php)
**File:** `resources/views/siswa/pinjam.blade.php`

```html
@extends('layouts.app')
@section('title', 'Pinjam Buku - Perpustakaan Digital')
@section('page-title', 'Pinjam Buku')

@section('content')
    <div class="card card-custom">
        <div class="card-header"><i class="bi bi-bookmark-plus-fill me-2"></i>Daftar Buku Tersedia</div>
        <div class="card-body">
            <!-- Search -->
            <form action="{{ route('siswa.pinjam.index') }}" method="GET" class="mb-3">
                <div class="input-group" style="max-width: 360px;">
                    <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul, pengarang, kategori...">
                    <button class="btn btn-sm btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('siswa.pinjam.index') }}" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-lg"></i>
                        </a>
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

            <div class="mt-3">{{ $bukus->links() }}</div>
        </div>
    </div>
@endsection
```

---

## 12.3 Riwayat Peminjaman (siswa/riwayat.blade.php)
**File:** `resources/views/siswa/riwayat.blade.php`

```html
@extends('layouts.app')
@section('title', 'Riwayat Peminjaman - Perpustakaan Digital')
@section('page-title', 'Riwayat Peminjaman')

@section('content')
    <div class="card card-custom">
        <div class="card-header"><i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman Saya</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th><th>Judul Buku</th><th>Tgl Pinjam</th>
                            <th>Batas Kembali</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th>
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
                                    <span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span>
                                </td>
                                <td>
                                    @if($r->denda > 0)
                                        <span class="text-danger fw-bold">Rp {{ number_format($r->denda, 0, ',', '.') }}</span>
                                    @else - @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada riwayat peminjaman.
                                    <a href="{{ route('siswa.pinjam.index') }}">Pinjam buku sekarang!</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($riwayats->hasPages())
            <div class="card-footer bg-transparent">{{ $riwayats->links() }}</div>
        @endif
    </div>
@endsection
```

---

# BAB 13: CARA MENJALANKAN

## 13.1 Langkah Instalasi
```bash
# 1. Copy folder project ke C:/laragon/www/paket44/
# 2. Install dependencies
cd C:/laragon/www/paket44
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate
# Edit .env → DB_DATABASE=perpustakaan

# 4. Buat database & migrasi
# Buat database 'perpustakaan' di phpMyAdmin
php artisan migrate:fresh --seed

# 5. Jalankan
php artisan serve
# Buka: http://localhost:8000 atau http://paket44.test
```

## 13.2 Akun Login
| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Siswa | `ahmad` | `siswa123` |
| Siswa | `siti` | `siswa123` |

---

*Perpustakaan Sekolah Digital © 2024 — Laravel 10 + Bootstrap 5*
