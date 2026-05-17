@extends('layouts.admin')
@section('title','Group Structure')
@section('page-title','Bank Soal Structure')
@section('breadcrumb','Admin / Bank Soal / Structure')

@push('styles')
<style>
.stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px}
.stat-c{background:var(--navy-light);border:1px solid var(--border);border-radius:12px;
    padding:16px 18px;display:flex;align-items:center;gap:14px}
.stat-ico{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;
    justify-content:center;font-size:20px;flex-shrink:0}
.stat-val{font-size:22px;font-weight:800;line-height:1}
.stat-lbl{font-size:12px;color:var(--muted);margin-top:3px}
.mod-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:22px}
.mod-card{border:1.5px solid var(--border);border-radius:14px;padding:22px 20px;
    text-decoration:none;color:var(--text);display:flex;gap:16px;
    align-items:flex-start;transition:all .18s;background:var(--navy-light)}
.mod-card:hover{transform:translateY(-3px);box-shadow:0 6px 20px rgba(0,0,0,.2)}
.mod-ico{width:52px;height:52px;border-radius:12px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;font-size:24px}
.soal-row{display:flex;align-items:center;gap:12px;padding:11px 18px;
    border-bottom:1px solid var(--border)}
.soal-row:last-child{border-bottom:none}
.badge-co{background:rgba(26,86,219,.12);color:var(--accent);
    padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600}
.badge-we{background:rgba(220,38,38,.1);color:#f87171;
    padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600}
</style>
@endpush

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
    <a href="{{ route('admin.soal.group') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:18px;font-weight:800">✏️ Bank Soal Structure</div>
        <div style="font-size:13px;color:var(--muted)">Kelola soal tata bahasa dan ekspresi tertulis</div>
    </div>
    <a href="{{ route('admin.structure.create') }}" class="btn btn-primary btn-sm"
        style="margin-left:auto">
        <i class="fas fa-plus"></i> Tambah Soal Structure
    </a>
</div>

{{-- Stat ─ --}}
@php
    $totalSoal    = \App\Models\BankSoal::where('kategori','structure')->count();
    $totalComp    = \App\Models\BankSoal::where('kategori','structure')->where('sub_bagian','completion')->count();
    $totalWE      = \App\Models\BankSoal::where('kategori','structure')->where('sub_bagian','written_expression')->count();
    $totalAktif   = \App\Models\BankSoal::where('kategori','structure')->where('is_aktif',true)->count();
@endphp
<div class="stat-strip">
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(245,158,11,.15)">✏️</div>
        <div><div class="stat-val">{{ $totalSoal }}</div><div class="stat-lbl">Total Soal Structure</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(26,86,219,.12)">📝</div>
        <div><div class="stat-val">{{ $totalComp }}</div><div class="stat-lbl">Completion</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(220,38,38,.1)">🔍</div>
        <div><div class="stat-val">{{ $totalWE }}</div><div class="stat-lbl">Written Expression</div></div>
    </div>
    <div class="stat-c">
        <div class="stat-ico" style="background:rgba(22,163,74,.12)">✅</div>
        <div><div class="stat-val">{{ $totalAktif }}</div><div class="stat-lbl">Soal Aktif</div></div>
    </div>
</div>

{{-- 2 tipe soal ─ --}}
<div class="mod-grid">

    <div class="mod-card" style="border-color:rgba(26,86,219,.3)">
        <div class="mod-ico" style="background:rgba(26,86,219,.12)">📝</div>
        <div style="flex:1">
            <div style="font-size:15px;font-weight:700;margin-bottom:5px">
                Structure Completion
            </div>
            <div style="font-size:12.5px;color:var(--muted);line-height:1.6;margin-bottom:10px">
                Kalimat rumpang — mahasiswa pilih kata/frasa yang tepat untuk mengisi blank.
            </div>
            <div style="font-size:13px;color:var(--muted);background:var(--bg);
                border-radius:8px;padding:10px 12px;font-style:italic;
                border-left:3px solid rgba(26,86,219,.4)">
                "The students ____ studying in the library."<br>
                <span style="font-size:12px;color:var(--accent)">A. is &nbsp; B. are ✓ &nbsp; C. was &nbsp; D. be</span>
            </div>
            <div style="margin-top:10px;font-size:12px;color:var(--muted)">
                {{ $totalComp }} soal tersimpan
            </div>
        </div>
    </div>

    <div class="mod-card" style="border-color:rgba(220,38,38,.3)">
        <div class="mod-ico" style="background:rgba(220,38,38,.1)">🔍</div>
        <div style="flex:1">
            <div style="font-size:15px;font-weight:700;margin-bottom:5px">
                Written Expression
            </div>
            <div style="font-size:12.5px;color:var(--muted);line-height:1.6;margin-bottom:10px">
                Kalimat dengan 4 kata bergaris bawah — mahasiswa pilih kata yang salah secara gramatikal.
            </div>
            <div style="font-size:13px;color:var(--muted);background:var(--bg);
                border-radius:8px;padding:10px 12px;font-style:italic;
                border-left:3px solid rgba(220,38,38,.4)">
                "The students in the class <u style="color:#f87171">was</u> studying hard."<br>
                <span style="font-size:12px;color:#f87171">A. students &nbsp; B. class &nbsp; C. was ✓ &nbsp; D. studying</span>
            </div>
            <div style="margin-top:10px;font-size:12px;color:var(--muted)">
                {{ $totalWE }} soal tersimpan
            </div>
        </div>
    </div>

</div>

{{-- Cara input ─ --}}
<div style="background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.2);
    border-radius:12px;padding:14px 18px;margin-bottom:18px;font-size:13px;line-height:1.8">
    <strong style="color:#fbbf24">📌 Cara Input Soal Structure:</strong>
    Masuk ke <strong>Paket Builder</strong> → pilih paket → tambah Group Structure →
    tambah Modul → pilih tipe soal (Completion / Written Expression) → input soal.
    Semua soal tersimpan di bank soal dan terhubung ke paket.
</div>

{{-- Daftar soal terbaru ─ --}}
@php
    $soalList = \App\Models\BankSoal::where('kategori','structure')
        ->orderByDesc('created_at')->take(10)->get();
@endphp
@if($soalList->count())
<div class="card">
    <div class="card-header">
        <h3 style="font-size:14px">
            <i class="fas fa-history" style="color:var(--muted);margin-right:7px"></i>
            Soal Structure Terbaru
        </h3>
        <a href="{{ route('admin.structure.create') }}"
            class="btn btn-outline btn-sm" style="font-size:12px">
            Kelola Soal
        </a>
    </div>
    <div class="card-body" style="padding:0">
        @foreach($soalList as $s)
        <div class="soal-row">
            <div style="width:30px;height:30px;border-radius:8px;flex-shrink:0;
                background:{{ $s->sub_bagian==='written_expression'?'rgba(220,38,38,.15)':'rgba(26,86,219,.15)' }};
                display:flex;align-items:center;justify-content:center;
                font-size:12px;font-weight:800;color:{{ $s->sub_bagian==='written_expression'?'#f87171':'var(--accent)' }}">
                {{ $s->nomor_soal ?: '—' }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:13px;white-space:nowrap;overflow:hidden;
                    text-overflow:ellipsis;color:rgba(255,255,255,.85)">
                    {{ mb_strimwidth($s->pertanyaan??'', 0, 65, '...') }}
                </div>
                <div style="margin-top:3px;display:flex;gap:6px">
                    @if($s->sub_bagian === 'written_expression')
                    <span class="badge-we">Written Expression</span>
                    @else
                    <span class="badge-co">Completion</span>
                    @endif
                    <span style="font-size:11px;color:var(--muted)">
                        Jwb: {{ strtoupper($s->jawaban_benar??'-') }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection