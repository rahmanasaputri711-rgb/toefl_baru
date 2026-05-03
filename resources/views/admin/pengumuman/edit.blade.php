@extends('layouts.admin')
@section('title','Edit Pengumuman')
@section('page-title','Edit Pengumuman')
@section('breadcrumb','Admin / Pengumuman / Edit')
@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header">
        <h3>Edit Pengumuman</h3>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pengumuman.update',$pengumuman->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Judul *</label>
                <input type="text" name="judul" class="form-control" required value="{{ $pengumuman->judul }}">
            </div>
            <div class="form-group">
                <label class="form-label">Konten *</label>
                <textarea name="konten" class="form-control" rows="6" required>{{ $pengumuman->konten }}</textarea>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Target</label>
                    <select name="target" class="form-control">
                        <option value="semua" {{ $pengumuman->target=='semua' ? 'selected':'' }}>Semua</option>
                        <option value="user"  {{ $pengumuman->target=='user'  ? 'selected':'' }}>Hanya User Login</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Berlaku Sampai</label>
                    <input type="datetime-local" name="expired_at" class="form-control"
                        value="{{ $pengumuman->expired_at ? \Carbon\Carbon::parse($pengumuman->expired_at)->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>
            <div style="display:flex;gap:20px;margin-bottom:18px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px">
                    <input type="checkbox" name="is_pinned" value="1" {{ $pengumuman->is_pinned ? 'checked':'' }} style="accent-color:var(--accent)"> Pin di atas
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px">
                    <input type="checkbox" name="is_published" value="1" {{ $pengumuman->is_published ? 'checked':'' }} style="accent-color:var(--accent)"> Publish
                </label>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
