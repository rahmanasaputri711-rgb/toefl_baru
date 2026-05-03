@extends('layouts.user')
@section('title','Profil')
@section('page-title','Profil Saya')
@section('breadcrumb','Home / Profil')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>Data Profil</h3></div>
    <div class="card-body">
        <form action="{{ route('user.profil.update') }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled style="opacity:.6">
                <small style="color:var(--muted);font-size:11px">Email tidak dapat diubah</small>
            </div>
            <div style="border-top:1px solid var(--border);padding-top:16px;margin-top:4px">
                <div class="form-group">
                    <label class="form-label">Password Baru <small style="color:var(--muted)">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
