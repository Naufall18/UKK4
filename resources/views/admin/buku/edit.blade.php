@extends('layouts.app')
@section('title', 'Edit Buku - Perpustakaan Digital')
@section('page-title', 'Edit Buku')

@section('content')
    <div class="card card-custom" style="max-width: 640px;">
        <div class="card-header">
            <i class="bi bi-pencil-square me-2"></i>Form Edit Buku
        </div>
        <div class="card-body">
            <form action="{{ route('admin.buku.update', $buku) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $buku->judul) }}"
                        required>
                </div>
                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="pengarang"
                        value="{{ old('pengarang', $buku->pengarang) }}" required>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit"
                        value="{{ old('penerbit', $buku->penerbit) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="text" class="form-control" id="tahun" name="tahun"
                            value="{{ old('tahun', $buku->tahun) }}" maxlength="4" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori"
                            value="{{ old('kategori', $buku->kategori) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok"
                            value="{{ old('stok', $buku->stok) }}" min="0" required>
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