@extends('layouts.admin')
@section('title','Tambah Materi')
@section('page-title','Tambah Materi')
@section('breadcrumb','Admin / Materi / Tambah')
@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header">
        <h3><i class="fas fa-plus-circle" style="color:var(--accent);margin-right:8px"></i>Form Tambah Materi</h3>
        <a href="{{ route('admin.materi.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.materi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="kategori" class="form-control" required>
                        <option value="reading">Reading</option>
                        <option value="listening">Listening</option>
                        <option value="structure">Structure</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe File</label>
                    <select name="tipe_file" class="form-control">
                        <option value="none">Tidak Ada</option>
                        <option value="pdf">PDF</option>
                        <option value="audio">Audio</option>
                        <option value="video">Video</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Judul Materi *</label>
                <input type="text" name="judul" class="form-control" required value="{{ old('judul') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <input type="text" name="deskripsi" class="form-control" value="{{ old('deskripsi') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Konten / Isi Materi</label>
                <textarea name="konten" class="form-control" rows="8" placeholder="Tulis konten materi di sini...">{{ old('konten') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Upload File <small style="color:var(--text-muted)">(pdf/audio/video, max 50MB)</small></label>
                <input type="file" name="file_upload" class="form-control">
            </div>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Materi</button>
                <a href="{{ route('admin.materi.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
