@extends('layouts.admin')
@section('title','Modul Soal')
@section('page-title','Modul Soal')
@section('breadcrumb','Admin / Bank Soal / Modul Soal')

@push('styles')
<style>
.mod-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-top:8px}
.mod-card{border:1px solid var(--border);border-radius:14px;padding:22px;
    text-decoration:none;color:var(--text);display:flex;gap:18px;
    align-items:flex-start;transition:all .18s;background:var(--navy-light)}
.mod-card:hover{border-color:var(--accent);transform:translateY(-2px);
    box-shadow:0 6px 20px rgba(0,0,0,.2)}
.mod-ico{width:52px;height:52px;border-radius:12px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;font-size:24px}
.mod-title{font-size:15px;font-weight:800;margin-bottom:4px}
.mod-desc{font-size:12.5px;color:var(--muted);line-height:1.6;margin-bottom:10px}
.mod-tags{display:flex;gap:6px;flex-wrap:wrap}
.mod-tag{font-size:11px;padding:2px 9px;border-radius:20px;font-weight:600}
</style>
@endpush

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px">
    <a href="{{ route('admin.soal.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:18px;font-weight:800">Modul Soal</div>
        <div style="font-size:13px;color:var(--muted)">Pilih tipe modul soal yang ingin diinput</div>
    </div>
</div>

<div style="max-width:860px">
<div class="mod-grid">

    <a href="{{ route('admin.passage.create') }}" class="mod-card">
        <div class="mod-ico" style="background:rgba(59,130,246,.12)">📄</div>
        <div>
            <div class="mod-title">Passage</div>
            <div class="mod-desc">Teks bacaan akademik. Admin input teks, lalu tambah soal per nomor dengan berbagai tipe pertanyaan.</div>
            <div class="mod-tags">
                <span class="mod-tag" style="background:rgba(59,130,246,.12);color:#3b82f6">Multiple Choice</span>
                <span class="mod-tag" style="background:rgba(245,158,11,.12);color:#f59e0b">Vocabulary</span>
                <span class="mod-tag" style="background:rgba(139,92,246,.12);color:#8b5cf6">Klik Kalimat</span>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.passage.index') }}" class="mod-card">
        <div class="mod-ico" style="background:rgba(16,185,129,.12)">🔤</div>
        <div>
            <div class="mod-title">Missing Letters</div>
            <div class="mod-desc">Teks dengan kata yang disembunyikan menggunakan format [kata]. User isi langsung di kotak. Nomor soal otomatis dari jumlah blank.</div>
            <div class="mod-tags">
                <span class="mod-tag" style="background:rgba(16,185,129,.12);color:#10b981">Format [blank]</span>
                <span class="mod-tag" style="background:rgba(16,185,129,.12);color:#10b981">Preview real-time</span>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.passage.index') }}" class="mod-card">
        <div class="mod-ico" style="background:rgba(245,158,11,.12)">📧</div>
        <div>
            <div class="mod-title">Gambar / Email</div>
            <div class="mod-desc">Upload 1 gambar atau screenshot email. Lalu tambah soal pilihan ganda satu per satu. Cocok untuk soal membaca email.</div>
            <div class="mod-tags">
                <span class="mod-tag" style="background:rgba(245,158,11,.12);color:#f59e0b">Upload gambar</span>
                <span class="mod-tag" style="background:rgba(245,158,11,.12);color:#f59e0b">Pilihan ganda</span>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.listening.index') }}" class="mod-card">
        <div class="mod-ico" style="background:rgba(234,88,12,.12)">🎧</div>
        <div>
            <div class="mod-title">Listening Audio</div>
            <div class="mod-desc">Upload 1 audio penuh ±35 menit. Tambah soal satu per satu dengan timestamp. Soal muncul otomatis saat audio diputar.</div>
            <div class="mod-tags">
                <span class="mod-tag" style="background:rgba(234,88,12,.12);color:#fb923c">1 audio full</span>
                <span class="mod-tag" style="background:rgba(234,88,12,.12);color:#fb923c">Timeline timestamp</span>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.soal.create') }}" class="mod-card">
        <div class="mod-ico" style="background:rgba(139,92,246,.12)">💬</div>
        <div>
            <div class="mod-title">Best Response</div>
            <div class="mod-desc">Tampilkan dialog singkat atau gambar karakter. User pilih respons yang paling tepat dari 4 pilihan.</div>
            <div class="mod-tags">
                <span class="mod-tag" style="background:rgba(139,92,246,.12);color:#8b5cf6">Dialogue</span>
                <span class="mod-tag" style="background:rgba(139,92,246,.12);color:#8b5cf6">Structure</span>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.soal.create') }}" class="mod-card">
        <div class="mod-ico" style="background:rgba(236,72,153,.12)">🔀</div>
        <div>
            <div class="mod-title">Arrange Sentence</div>
            <div class="mod-desc">Admin input kata-kata terpisah. User susun kata-kata menjadi kalimat yang benar secara gramatikal.</div>
            <div class="mod-tags">
                <span class="mod-tag" style="background:rgba(236,72,153,.12);color:#ec4899">Drag & drop</span>
                <span class="mod-tag" style="background:rgba(236,72,153,.12);color:#ec4899">Structure</span>
            </div>
        </div>
    </a>

</div>
</div>
@endsection
