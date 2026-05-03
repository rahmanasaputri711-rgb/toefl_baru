@extends('layouts.admin')
@section('title','Buat Paket')
@section('page-title','Buat Paket Soal Baru')
@section('breadcrumb','Admin / Paket Soal / Buat')
@section('content')
<div style="max-width:600px">
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus-circle" style="color:var(--accent);margin-right:8px"></i>Form Buat Paket</h3>
        <a href="{{ route('admin.paket.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <div class="alert alert-info" style="margin-bottom:18px;font-size:13px">
            <i class="fas fa-info-circle"></i>
            Setelah dibuat, Anda akan langsung diarahkan ke halaman editor untuk memilih soal.
            Paket harus memiliki <strong>50 Listening · 40 Structure · 50 Reading</strong> agar valid.
        </div>

        <form action="{{ route('admin.paket.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Paket <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama" class="form-control" required
                    value="{{ old('nama') }}" placeholder="cth: Paket TOEFL ITP — Semester Ganjil 2026">
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Deskripsi <span style="font-weight:400;color:rgba(255,255,255,.3)">(opsional)</span></label>
                <textarea name="deskripsi" class="form-control" rows="2"
                    placeholder="Catatan...">{{ old('deskripsi') }}</textarea>
            </div>
            <div style="margin-top:18px;display:flex;gap:12px">
                <button type="submit" class="btn btn-primary" style="padding:11px 28px">
                    <i class="fas fa-arrow-right"></i> Buat & Lanjut Edit
                </button>
                <a href="{{ route('admin.paket.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
