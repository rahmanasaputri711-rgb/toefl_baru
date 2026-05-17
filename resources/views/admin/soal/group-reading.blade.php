@extends('layouts.admin')
@section('title','Group Reading')
@section('page-title','Bank Soal Reading')
@section('breadcrumb','Admin / Bank Soal / Reading')

@push('styles')
<style>
.stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px}
.stat-c{background:var(--navy-light);border:1px solid var(--border);border-radius:12px;
    padding:16px 18px;display:flex;align-items:center;gap:14px}
.stat-ico{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;
    justify-content:center;font-size:20px;flex-shrink:0}
.stat-val{font-size:22px;font-weight:800;line-height:1}
.stat-lbl{font-size:12px;color:var(--muted);margin-top:3px}
.mod-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.mod-card{border:1.5px solid var(--border);border-radius:14px;padding:22px 20px;
    text-decoration:none;color:var(--text);display:flex;flex-direction:column;gap:12px;
    transition:all .18s;background:var(--navy-light)}
.mod-card:hover{border-color:var(--accent);transform:translateY(-3px);
    box-shadow:0 6px 20px rgba(0,0,0,.2)}
.mod-ico{width:50px;height:50px;border-radius:12px;display:flex;align-items:center;
    justify-content:center;font-size:22px}
.mod-title{font-size:15px;font-weight:700;margin-bottom:3px}
.mod-desc{font-size:12.5px;color:var(--muted);line-height:1.6}
.mod-tags{display:flex;gap:5px;flex-wrap:wrap}
.mod-tag{font-size:11px;padding:2px 8px;border-radius:20px;font-weight:600}
.mod-action{margin-top:auto;padding:9px 0;border-radius:9px;text-align:center;
    font-size:13px;font-weight:700;border:1.5px solid;text-decoration:none;
    display:block;transition:all .15s}
.mod-action:hover{opacity:.85}
</style>
@endpush

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
    <a href="{{ route('admin.soal.group') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:18px;font-weight:800">📖 Bank Soal Reading</div>
        <div style="font-size:13px;color:var(--muted)">Kelola semua modul soal reading</div>
    </div>
    <a href="{{ route('admin.passage.create') }}" class="btn btn-primary btn-sm"
        style="margin-left:auto">
        <i class="fas fa-plus"></i> Tambah Passage
    </a>
</div>

{{-- Stat ─ --}}
@php
    $totalPassage = \App\Models\Passage::count();
    $totalSoal    = \App\Models\BankSoal::where('kategori','reading')->count();
    $totalFull    = \App\Models\Passage::where('tipe_paket','full')->count();
    $totalSim     = \App\Models\Passage::where('tipe_paket','simulasi')->count();
@endphp
<div class="stat-strip">
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(59,130,246,.15)">📄</div>
        <div><div class="stat-val">{{ $totalPassage }}</div><div class="stat-lbl">Passage</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(22,163,74,.12)">❓</div>
        <div><div class="stat-val">{{ $totalSoal }}</div><div class="stat-lbl">Total Soal Reading</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(245,158,11,.12)">🎓</div>
        <div><div class="stat-val">{{ $totalFull }}</div><div class="stat-lbl">Paket Full</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(139,92,246,.12)">🧪</div>
        <div><div class="stat-val">{{ $totalSim }}</div><div class="stat-lbl">Paket Simulasi</div></div>
    </div>
</div>

{{-- Modul ─ --}}
<div class="mod-grid">

    <a href="{{ route('admin.passage.index') }}" class="mod-card"
        style="border-color:rgba(59,130,246,.25)">
        <div class="mod-ico" style="background:rgba(59,130,246,.12)">📄</div>
        <div>
            <div class="mod-title">Academic Passage</div>
            <div class="mod-desc">Teks bacaan akademik + soal per nomor.<br>
                Berbagai tipe pertanyaan tersedia.</div>
            <div class="mod-tags" style="margin-top:8px">
                <span class="mod-tag" style="background:rgba(59,130,246,.12);color:#3b82f6">Multiple Choice</span>
                <span class="mod-tag" style="background:rgba(245,158,11,.12);color:#f59e0b">Vocabulary</span>
                <span class="mod-tag" style="background:rgba(139,92,246,.12);color:#8b5cf6">Klik Kalimat</span>
            </div>
        </div>
        <span class="mod-action" style="border-color:rgba(59,130,246,.4);color:#3b82f6">
            <i class="fas fa-book-open"></i> Kelola Passage
        </span>
    </a>

    <a href="{{ route('admin.passage.index') }}" class="mod-card"
        style="border-color:rgba(16,185,129,.25)">
        <div class="mod-ico" style="background:rgba(16,185,129,.12)">🔤</div>
        <div>
            <div class="mod-title">Missing Letters</div>
            <div class="mod-desc">Teks dengan kata tersembunyi format [kata].<br>
                User isi blank langsung di teks.</div>
            <div class="mod-tags" style="margin-top:8px">
                <span class="mod-tag" style="background:rgba(16,185,129,.12);color:#10b981">Format [blank]</span>
                <span class="mod-tag" style="background:rgba(16,185,129,.12);color:#10b981">Preview real-time</span>
            </div>
        </div>
        <span class="mod-action" style="border-color:rgba(16,185,129,.4);color:#10b981">
            <i class="fas fa-font"></i> Kelola Missing Letters
        </span>
    </a>

    <a href="{{ route('admin.passage.index') }}" class="mod-card"
        style="border-color:rgba(245,158,11,.25)">
        <div class="mod-ico" style="background:rgba(245,158,11,.12)">📧</div>
        <div>
            <div class="mod-title">Gambar / Email</div>
            <div class="mod-desc">Upload gambar atau screenshot email.<br>
                Tambah soal pilihan ganda per gambar.</div>
            <div class="mod-tags" style="margin-top:8px">
                <span class="mod-tag" style="background:rgba(245,158,11,.12);color:#f59e0b">Upload gambar</span>
                <span class="mod-tag" style="background:rgba(245,158,11,.12);color:#f59e0b">Pilihan ganda</span>
            </div>
        </div>
        <span class="mod-action" style="border-color:rgba(245,158,11,.4);color:#f59e0b">
            <i class="fas fa-envelope"></i> Kelola Email/Gambar
        </span>
    </a>

</div>

{{-- Daftar passage terbaru ─ --}}
@php
    $passages = \App\Models\Passage::with(['soal'])->orderByDesc('created_at')->take(6)->get();
@endphp
@if($passages->count())
<div class="card" style="margin-top:22px">
    <div class="card-header">
        <h3 style="font-size:14px">
            <i class="fas fa-history" style="color:var(--muted);margin-right:7px"></i>
            Passage Terbaru
        </h3>
        <a href="{{ route('admin.passage.index') }}" class="btn btn-outline btn-sm"
            style="font-size:12px">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding:0">
        @foreach($passages as $p)
        <div style="display:flex;align-items:center;gap:12px;padding:12px 18px;
            border-bottom:1px solid var(--border);
            {{ $loop->last?'border-bottom:none':'' }}">
            <div style="width:36px;height:36px;border-radius:8px;flex-shrink:0;
                background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.2);
                display:flex;align-items:center;justify-content:center;font-size:16px">📄</div>
            <div style="flex:1;min-width:0">
                <div style="font-size:13.5px;font-weight:600;
                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $p->judul }}</div>
                <div style="font-size:12px;color:var(--muted)">
                    {{ $p->soal->count() }} soal &nbsp;·&nbsp;
                    <span style="background:rgba(26,86,219,.12);color:var(--accent);
                        padding:1px 7px;border-radius:4px;font-size:11px">
                        {{ strtoupper($p->tipe_paket) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.passage.show', $p->id) }}"
                class="btn btn-outline btn-sm" style="font-size:11px;flex-shrink:0">
                <i class="fas fa-edit"></i> Kelola
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
