@extends('layouts.admin')
@section('title','Reading Passages')
@section('page-title','Bank Soal Reading')
@section('breadcrumb','Admin / Reading')

@section('content')

{{-- Stat strip --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px">
    @foreach([
        ['Passage','fa-book-open','si-blue',$stats['total_passage']],
        ['Total Soal Reading','fa-question-circle','si-green',$stats['total_soal']],
        ['Paket Full','fa-graduation-cap','si-gold',$stats['full']],
        ['Paket Simulasi','fa-flask','si-blue',$stats['simulasi']],
    ] as [$lbl,$ico,$cls,$val])
    <div class="stat-card">
        <div class="stat-icon {{ $cls }}"><i class="fas {{ $ico }}"></i></div>
        <div><div class="stat-val">{{ $val }}</div><div class="stat-label">{{ $lbl }}</div></div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-book-open" style="color:var(--accent);margin-right:8px"></i>
            Daftar Passage Reading
        </h3>
        <a href="{{ route('admin.passage.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Passage Baru
        </a>
    </div>

    {{-- Filter --}}
    <div class="card-body" style="padding:14px 20px;border-bottom:1px solid var(--border)">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
            <input type="text" name="search" class="form-control" style="flex:1;min-width:200px"
                placeholder="Cari judul passage..." value="{{ request('search') }}">
            <select name="tipe_paket" class="form-control" style="width:160px">
                <option value="">Semua Tipe</option>
                @foreach(['full'=>'Tes Full','simulasi'=>'Simulasi','mini'=>'Mini','praktik'=>'Praktik'] as $v=>$l)
                <option value="{{ $v }}" {{ request('tipe_paket')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.passage.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>

    <div class="card-body" style="padding:16px 20px">
        @forelse($passages as $p)
        <div style="border:1px solid var(--border);border-radius:12px;margin-bottom:14px;overflow:hidden">
            {{-- Header passage --}}
            <div style="background:var(--navy-light);padding:14px 18px;
                display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div>
                    <div style="font-weight:700;font-size:15px">
                        {{ $p->judul }}
                    </div>
                    <div style="font-size:12px;color:var(--muted);margin-top:3px">
                        <span style="background:rgba(26,86,219,.15);color:var(--accent);
                            padding:2px 8px;border-radius:6px;font-size:11px;margin-right:6px">
                            {{ strtoupper($p->tipe_paket) }}
                        </span>
                        {{ $p->soal->count() }} soal
                        &nbsp;·&nbsp;
                        {{ mb_strimwidth(strip_tags($p->teks), 0, 80, '...') }}
                    </div>
                </div>
                <div style="display:flex;gap:8px">
                    <a href="{{ route('admin.passage.show', $p->id) }}"
                        class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Kelola Soal
                    </a>
                    <a href="{{ route('admin.passage.edit', $p->id) }}"
                        class="btn btn-outline btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.passage.destroy', $p->id) }}" method="POST"
                        onsubmit="return confirm('Hapus passage ini beserta {{ $p->soal->count() }} soalnya?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Daftar soal dalam passage --}}
            @if($p->soal->count())
            <div style="padding:10px 18px;background:rgba(0,0,0,.1)">
                <div style="display:flex;gap:6px;flex-wrap:wrap">
                    @foreach($p->soal as $s)
                    @php
                        $tipeBadge = [
                            'multiple_choice' => ['#dbeafe','#1d4ed8','MC'],
                            'vocabulary'      => ['#fef3c7','#d97706','VO'],
                            'insert_sentence' => ['#dcfce7','#16a34a','IS'],
                            'click_sentence'  => ['#f3e8ff','#7c3aed','CS'],
                            'prose_summary'   => ['#fee2e2','#dc2626','PS'],
                        ][$s->tipe_soal ?? 'multiple_choice'];
                    @endphp
                    <span title="{{ \App\Models\BankSoal::$tipeSoalMap[$s->tipe_soal ?? 'multiple_choice'] }}"
                        style="background:{{ $tipeBadge[0] }};color:{{ $tipeBadge[1] }};
                        padding:3px 8px;border-radius:6px;font-size:11px;font-weight:700;
                        border:1px solid {{ $tipeBadge[1] }}33">
                        {{ $s->nomor_soal }}. {{ $tipeBadge[2] }}
                    </span>
                    @endforeach
                </div>
                <div style="font-size:11px;color:var(--muted);margin-top:6px">
                    MC=Multiple Choice &nbsp;VO=Vocabulary &nbsp;IS=Insert Sentence
                    &nbsp;CS=Click Sentence &nbsp;PS=Prose Summary
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>Belum ada passage. <a href="{{ route('admin.passage.create') }}">Buat passage pertama</a>.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
