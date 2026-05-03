@extends('layouts.admin')
@section('title','Edit Sesi')
@section('page-title','Edit Sesi Tes')
@section('breadcrumb','Admin / Sesi Tes / Edit')
@section('content')
<div class="card" style="max-width:800px">
    <div class="card-header">
        <h3><i class="fas fa-pen" style="color:var(--gold);margin-right:8px"></i>Edit Sesi: {{ $sesi->judul }}</h3>
        <a href="{{ route('admin.sesi.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.sesi.update',$sesi->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Judul Sesi *</label>
                <input type="text" name="judul" class="form-control" required value="{{ $sesi->judul }}">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2">{{ $sesi->deskripsi }}</textarea>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Waktu Mulai *</label>
                    <input type="datetime-local" name="waktu_mulai" class="form-control" required value="{{ \Carbon\Carbon::parse($sesi->waktu_mulai)->format('Y-m-d\TH:i') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu Selesai *</label>
                    <input type="datetime-local" name="waktu_selesai" class="form-control" required value="{{ \Carbon\Carbon::parse($sesi->waktu_selesai)->format('Y-m-d\TH:i') }}">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Durasi (menit) *</label>
                    <input type="number" name="durasi_menit" class="form-control" required value="{{ $sesi->durasi_menit }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kuota Peserta *</label>
                    <input type="number" name="kuota_peserta" class="form-control" required value="{{ $sesi->kuota_peserta }}">
                </div>
            </div>
            <div style="display:flex;gap:16px;margin-bottom:18px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <input type="checkbox" name="tampilkan_hasil" value="1" {{ $sesi->tampilkan_hasil ? 'checked':'' }} style="accent-color:var(--accent)">
                    <span style="font-size:13px">Tampilkan hasil ke user</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <input type="checkbox" name="tampilkan_pembahasan" value="1" {{ $sesi->tampilkan_pembahasan ? 'checked':'' }} style="accent-color:var(--accent)">
                    <span style="font-size:13px">Tampilkan pembahasan</span>
                </label>
            </div>
            <div class="alert alert-info"><i class="fas fa-info-circle"></i> Mengubah jadwal akan mengirim notifikasi ke semua peserta yang sudah dikonfirmasi.</div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
                <a href="{{ route('admin.sesi.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
