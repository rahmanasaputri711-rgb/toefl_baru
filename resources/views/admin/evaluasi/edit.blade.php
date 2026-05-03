@extends('layouts.admin')
@section('title','Edit Evaluasi')
@section('page-title','Edit Evaluasi')
@section('breadcrumb','Admin / Evaluasi / Edit')
@section('content')
<div class="card" style="max-width:820px">
    <div class="card-header">
        <h3>Edit Evaluasi</h3>
        <a href="{{ route('admin.evaluasi.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.evaluasi.update',$evaluasi->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Sesi Tes</label>
                <input type="text" class="form-control" value="{{ $evaluasi->sesiTes->judul ?? '-' }}" disabled>
            </div>
            <div class="form-group">
                <label class="form-label">Judul *</label>
                <input type="text" name="judul" class="form-control" required value="{{ $evaluasi->judul }}">
            </div>
            <div class="form-group">
                <label class="form-label">Catatan *</label>
                <textarea name="catatan" class="form-control" rows="6" required>{{ $evaluasi->catatan }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Rekomendasi</label>
                <textarea name="rekomendasi" class="form-control" rows="4">{{ $evaluasi->rekomendasi }}</textarea>
            </div>
            <div style="display:flex;gap:20px;margin-bottom:18px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px">
                    <input type="checkbox" name="untuk_user" value="1" {{ $evaluasi->untuk_user ? 'checked':'' }} style="accent-color:var(--accent)"> Tampilkan ke user
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px">
                    <input type="checkbox" name="is_published" value="1" {{ $evaluasi->is_published ? 'checked':'' }} style="accent-color:var(--accent)"> Publish
                </label>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
                <a href="{{ route('admin.evaluasi.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
