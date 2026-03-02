@extends('layouts.app')
@section('title', 'Tambah Anggota - Perpustakaan Digital')
@section('page-title', 'Tambah Anggota')

@section('content')
    <div class="card card-custom" style="max-width: 680px;">
        <div class="card-header">
            <i class="bi bi-person-plus me-2"></i>Form Tambah Anggota
        </div>
        <div class="card-body">
            <form action="{{ route('admin.anggota.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}"
                            placeholder="Username untuk login" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                            placeholder="Email aktif" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis') }}"
                            placeholder="Nomor Induk Siswa" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas') }}"
                            placeholder="Contoh: XII RPL 1" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">No. HP <small class="text-muted">(opsional)</small></label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                            placeholder="No. handphone">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Min 6 karakter" required>
                    </div>
                </div>
                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection