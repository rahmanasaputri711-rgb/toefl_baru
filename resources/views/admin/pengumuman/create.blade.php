@extends('layouts.admin')
@section('title','Buat Pengumuman')
@section('page-title','Buat Pengumuman')
@section('breadcrumb','Admin / Pengumuman / Buat')
@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header">
        <h3>Form Pengumuman</h3>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pengumuman.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul *</label>
                <input type="text" name="judul" class="form-control" required value="{{ old('judul') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Konten *</label>
                <textarea name="konten" class="form-control" rows="6" required>{{ old('konten') }}</textarea>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Target</label>
                    <select name="target" class="form-control">
                        <option value="semua">Semua (termasuk publik)</option>
                        <option value="user">Hanya User Login</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Berlaku Sampai</label>
                    <input type="datetime-local" name="expired_at" class="form-control" value="{{ old('expired_at') }}">
                </div>
            </div>
            <div style="display:flex;gap:20px;margin-bottom:18px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px">
                    <input type="checkbox" name="is_pinned" value="1" style="accent-color:var(--accent)"> Pin di atas
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px">
                    <input type="checkbox" name="is_published" value="1" checked style="accent-color:var(--accent)"> Publish sekarang
                </label>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
