@extends('layouts.admin')
@section('title','Edit Materi')
@section('page-title','Edit Materi')
@section('breadcrumb','Admin / Materi / Edit')
@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header">
        <h3><i class="fas fa-pen" style="color:var(--gold);margin-right:8px"></i>Edit: {{ $materi->judul }}</h3>
        <a href="{{ route('admin.materi.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.materi.update',$materi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="kategori" class="form-control" required>
                        <option value="reading"   {{ $materi->kategori=='reading'  ? 'selected':'' }}>Reading</option>
                        <option value="listening" {{ $materi->kategori=='listening'? 'selected':'' }}>Listening</option>
                        <option value="structure" {{ $materi->kategori=='structure'? 'selected':'' }}>Structure</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe File</label>
                    <select name="tipe_file" class="form-control">
                        <option value="none"  {{ $materi->tipe_file=='none'  ? 'selected':'' }}>Tidak Ada</option>
                        <option value="pdf"   {{ $materi->tipe_file=='pdf'   ? 'selected':'' }}>PDF</option>
                        <option value="audio" {{ $materi->tipe_file=='audio' ? 'selected':'' }}>Audio</option>
                        <option value="video" {{ $materi->tipe_file=='video' ? 'selected':'' }}>Video</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Judul *</label>
                <input type="text" name="judul" class="form-control" required value="{{ $materi->judul }}">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <input type="text" name="deskripsi" class="form-control" value="{{ $materi->deskripsi }}">
            </div>
            <div class="form-group">
                <label class="form-label">Konten</label>
                <textarea name="konten" class="form-control" rows="8">{{ $materi->konten }}</textarea>
            </div>
            @if($materi->file_url)
            <div style="margin-bottom:12px;padding:10px 14px;background:var(--navy-light);border-radius:8px;font-size:13px;color:var(--accent)">
                <i class="fas fa-file"></i> File saat ini: {{ $materi->file_url }}
            </div>
            @endif
            <div class="form-group">
                <label class="form-label">Ganti File <small style="color:var(--text-muted)">(kosongkan jika tidak diubah)</small></label>
                <input type="file" name="file_upload" class="form-control">
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
                <a href="{{ route('admin.materi.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
