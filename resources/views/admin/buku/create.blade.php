@extends('layouts.app')
@section('title', 'Tambah Buku - Perpustakaan Digital')
@section('page-title', 'Tambah Buku')

@section('content')
    <div class="card card-custom" style="max-width: 680px;">
        <div class="card-header">
            <i class="bi bi-plus-circle me-2"></i>Form Tambah Buku
        </div>
        <div class="card-body">
            <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}"
                        placeholder="Masukkan judul buku" required>
                </div>
                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="pengarang" value="{{ old('pengarang') }}"
                        placeholder="Nama pengarang" required>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" value="{{ old('penerbit') }}"
                        placeholder="Nama penerbit" required>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="text" class="form-control" id="tahun" name="tahun" value="{{ old('tahun') }}"
                            maxlength="4" placeholder="2024" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" value="{{ old('kategori') }}"
                            placeholder="Novel, Sains, dll" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', 0) }}" min="0"
                            required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="cover" class="form-label">Cover Buku <small class="text-muted">(opsional, maks.
                            2MB)</small></label>
                    <input type="file" class="form-control" id="cover" name="cover"
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div id="coverPreview" class="mt-2" style="display: none;">
                        <img id="coverImg" src="" alt="Preview"
                            style="width: 80px; height: 107px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                    </div>
                </div>
                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('cover').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('coverImg').src = e.target.result;
                    document.getElementById('coverPreview').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection