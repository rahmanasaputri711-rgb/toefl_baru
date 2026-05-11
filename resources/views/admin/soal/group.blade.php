@extends('layouts.admin')
@section('title','Group Soal')
@section('page-title','Group Soal')
@section('breadcrumb','Admin / Bank Soal / Group Soal')

@push('styles')
<style>
.grp-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:8px}
.grp-card{border:2px solid var(--border);border-radius:16px;padding:28px 24px;
    text-decoration:none;color:var(--text);display:flex;flex-direction:column;
    gap:14px;transition:all .2s;background:var(--navy-light)}
.grp-card:hover{transform:translateY(-4px);box-shadow:0 8px 24px rgba(0,0,0,.25)}
.grp-ico{width:64px;height:64px;border-radius:16px;
    display:flex;align-items:center;justify-content:center;font-size:30px}
.grp-title{font-size:18px;font-weight:800}
.grp-desc{font-size:13px;color:var(--muted);line-height:1.7}
.grp-items{border-top:1px solid var(--border);padding-top:14px;
    display:flex;flex-direction:column;gap:7px}
.grp-item{font-size:12.5px;color:var(--muted);display:flex;align-items:center;gap:8px}
.grp-item i{font-size:12px;width:14px;text-align:center}
.grp-btn{margin-top:auto;padding:10px 0;border-radius:10px;text-align:center;
    font-size:13px;font-weight:700;border:1.5px solid;text-decoration:none;
    display:block;transition:all .15s}
</style>
@endpush

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px">
    <a href="{{ route('admin.soal.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:18px;font-weight:800">Group Soal</div>
        <div style="font-size:13px;color:var(--muted)">Pilih kategori group yang ingin dikelola</div>
    </div>
</div>

<div style="max-width:900px">
<div class="grp-grid">

    {{-- GROUP READING --}}
    <div class="grp-card" style="border-color:rgba(59,130,246,.3)">
        <div class="grp-ico" style="background:rgba(59,130,246,.12)">📖</div>
        <div>
            <div class="grp-title">Group Reading</div>
            <div class="grp-desc">Soal membaca dengan berbagai tipe interaksi teks.</div>
        </div>
        <div class="grp-items">
            <div class="grp-item"><i class="fas fa-file-alt" style="color:#3b82f6"></i> Passage (teks akademik)</div>
            <div class="grp-item"><i class="fas fa-font" style="color:#10b981"></i> Missing Letters</div>
            <div class="grp-item"><i class="fas fa-envelope" style="color:#f59e0b"></i> Gambar / Email</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px">
            <a href="{{ route('admin.passage.index') }}" class="grp-btn"
                style="border-color:rgba(59,130,246,.4);color:#3b82f6">
                <i class="fas fa-book-open"></i> Kelola Passage
            </a>
            <a href="{{ route('admin.passage.create') }}" class="grp-btn"
                style="border-color:var(--border);color:var(--muted)">
                <i class="fas fa-plus"></i> Buat Passage Baru
            </a>
        </div>
    </div>

    {{-- GROUP LISTENING --}}
    <div class="grp-card" style="border-color:rgba(234,88,12,.3)">
        <div class="grp-ico" style="background:rgba(234,88,12,.12)">🎧</div>
        <div>
            <div class="grp-title">Group Listening</div>
            <div class="grp-desc">1 audio penuh ±35 menit. Soal muncul otomatis berdasarkan timestamp.</div>
        </div>
        <div class="grp-items">
            <div class="grp-item"><i class="fas fa-music" style="color:#fb923c"></i> 1 file audio per paket</div>
            <div class="grp-item"><i class="fas fa-clock" style="color:#fb923c"></i> Soal dengan start_second</div>
            <div class="grp-item"><i class="fas fa-random" style="color:#fb923c"></i> Fisher-Yates: pilihan diacak</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px">
            <a href="{{ route('admin.listening.index') }}" class="grp-btn"
                style="border-color:rgba(234,88,12,.4);color:#fb923c">
                <i class="fas fa-headphones"></i> Kelola Listening
            </a>
            <a href="{{ route('admin.listening.create') }}" class="grp-btn"
                style="border-color:var(--border);color:var(--muted)">
                <i class="fas fa-upload"></i> Upload Audio Baru
            </a>
        </div>
    </div>

    {{-- GROUP STRUCTURE --}}
    <div class="grp-card" style="border-color:rgba(245,158,11,.3)">
        <div class="grp-ico" style="background:rgba(245,158,11,.12)">✏️</div>
        <div>
            <div class="grp-title">Group Structure</div>
            <div class="grp-desc">Soal tata bahasa dan susunan kalimat Bahasa Inggris.</div>
        </div>
        <div class="grp-items">
            <div class="grp-item"><i class="fas fa-comments" style="color:#f59e0b"></i> Best Response (dialog)</div>
            <div class="grp-item"><i class="fas fa-sort" style="color:#f59e0b"></i> Arrange Sentence</div>
            <div class="grp-item"><i class="fas fa-random" style="color:#f59e0b"></i> Fisher-Yates: urutan diacak</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px">
            <a href="{{ route('admin.soal.create') }}" class="grp-btn"
                style="border-color:rgba(245,158,11,.4);color:#f59e0b">
                <i class="fas fa-plus"></i> Tambah Soal Structure
            </a>
        </div>
    </div>

</div>
</div>
@endsection
