@extends('layouts.admin')
@section('title','Buat Evaluasi')
@section('page-title','Buat Evaluasi Tes')
@section('breadcrumb','Admin / Evaluasi / Buat')
@section('content')
<div class="card" style="max-width:820px">
    <div class="card-header">
        <h3>Form Evaluasi</h3>
        <a href="{{ route('admin.evaluasi.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.evaluasi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Pilih Sesi Tes *</label>
                <select name="sesi_id" class="form-control" required>
                    <option value="">-- Pilih Sesi --</option>
                    @foreach($sesiList as $s)
                    <option value="{{ $s->id }}">{{ $s->judul }} ({{ \Carbon\Carbon::parse($s->waktu_mulai)->format('d M Y') }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Judul Evaluasi *</label>
                <input type="text" name="judul" class="form-control" required value="{{ old('judul') }}" placeholder="Evaluasi TOEFL ITP Mei 2026">
            </div>
            <div class="form-group">
                <label class="form-label">Catatan / Analisis *</label>
                <textarea name="catatan" class="form-control" rows="6" required placeholder="Tulis catatan evaluasi, analisis skor, dan temuan..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Rekomendasi <small style="color:var(--text-muted)">(opsional)</small></label>
                <textarea name="rekomendasi" class="form-control" rows="4" placeholder="Rekomendasi untuk peserta dan pengelola..."></textarea>
            </div>
            <div style="display:flex;gap:20px;margin-bottom:18px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px">
                    <input type="checkbox" name="untuk_user" value="1" checked style="accent-color:var(--accent)"> Tampilkan ke user
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px">
                    <input type="checkbox" name="is_published" value="1" style="accent-color:var(--accent)"> Publish sekarang
                </label>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Evaluasi</button>
                <a href="{{ route('admin.evaluasi.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
