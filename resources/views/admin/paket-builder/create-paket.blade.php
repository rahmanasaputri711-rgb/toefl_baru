@extends('layouts.admin')
@section('title','Buat Paket')
@section('page-title','Buat Paket Soal Baru')
@section('breadcrumb','Admin / Paket Builder / Buat Paket')

@section('content')
<div style="max-width:560px">
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus" style="color:var(--accent);margin-right:8px"></i>Paket Baru</h3>
        <a href="{{ route('admin.paket-builder.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body" style="padding:24px">
        <div style="background:rgba(26,86,219,.07);border:1px solid rgba(26,86,219,.18);
            border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;line-height:1.8">
            <strong style="color:var(--accent)">1 Paket Soal</strong> terdiri dari seluruh kategori TOEFL
            (Reading, Listening, Structure). Setiap kategori dikelola dalam <strong>Group</strong>
            masing-masing, dan setiap group terdiri dari satu atau lebih <strong>Modul</strong>.
        </div>
        <form action="{{ route('admin.paket-builder.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Paket <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama" class="form-control" required
                    placeholder="cth: Paket Soal TOEFL ITP 2026 — Paket A"
                    value="{{ old('nama') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"
                    placeholder="Keterangan opsional...">{{ old('deskripsi') }}</textarea>
            </div>
            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Buat & Tambah Group
                </button>
                <a href="{{ route('admin.paket-builder.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
