@extends('layouts.auth')
@section('title', 'Register - Perpustakaan Digital')

@section('content')
    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                placeholder="Nama lengkap" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}"
                placeholder="Username untuk login" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                placeholder="Email aktif" required>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label for="nis" class="form-label">NIS</label>
                <input type="text" class="form-control" id="nis" name="nis" value="{{ old('nis') }}" placeholder="NIS"
                    required>
            </div>
            <div class="col-6 mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas') }}"
                    placeholder="Contoh: XII RPL 1" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">No. HP <small class="text-muted">(opsional)</small></label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                placeholder="No. handphone">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter"
                required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                placeholder="Ulangi password" required>
        </div>
        <button type="submit" class="btn btn-login">
            <i class="bi bi-person-plus me-1"></i> Daftar
        </button>
        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </form>
@endsection