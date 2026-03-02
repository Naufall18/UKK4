@extends('layouts.app')
@section('title', 'Edit Anggota - Perpustakaan Digital')
@section('page-title', 'Edit Anggota')

@section('content')
    <div class="card card-custom" style="max-width: 680px;">
        <div class="card-header">
            <i class="bi bi-pencil-square me-2"></i>Form Edit Anggota
        </div>
        <div class="card-body">
            <form action="{{ route('admin.anggota.update', $anggota) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $anggota->name) }}"
                        required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ old('username', $anggota->username) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $anggota->email) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis', $anggota->nis) }}"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas"
                            value="{{ old('kelas', $anggota->kelas) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp"
                            value="{{ old('no_hp', $anggota->no_hp) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status_aktif" class="form-label">Status</label>
                        <select class="form-select" id="status_aktif" name="status_aktif">
                            <option value="1" {{ old('status_aktif', $anggota->status_aktif) ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="0" {{ !old('status_aktif', $anggota->status_aktif) ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak
                            diubah)</small></label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan password baru">
                </div>
                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection