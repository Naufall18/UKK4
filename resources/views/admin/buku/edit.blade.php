@extends('layouts.app')
@section('title', 'Edit Buku - Perpustakaan Digital')
@section('page-title', 'Edit Buku')

@section('content')
    <div class="card card-custom" style="max-width: 680px;">
        <div class="card-header">
            <i class="bi bi-pencil-square me-2"></i>Form Edit Buku
        </div>
        <div class="card-body">
            <form action="{{ route('admin.buku.update', $buku) }}" method="POST" enctype="multipart/form-data">
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
                <div class="mb-3">
                    <label for="cover" class="form-label">Cover Buku <small class="text-muted">(kosongkan jika tidak
                            diubah)</small></label>
                    @if($buku->cover)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul }}"
                                style="width: 80px; height: 107px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <small class="d-block text-muted mt-1">Cover saat ini</small>
                        </div>
                    @endif
                    <input type="file" class="form-control" id="cover" name="cover"
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                </div>
                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button>
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection