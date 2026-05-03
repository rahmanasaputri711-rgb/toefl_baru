@extends('layouts.admin')
@section('title','Cari Skor')
@section('page-title','Cari Skor by Nomor Pendaftaran')
@section('breadcrumb','Admin / Laporan / Cari')
@section('content')
<div class="card" style="max-width:500px;margin-bottom:24px">
    <div class="card-header"><h3>Cari Nomor Pendaftaran</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.laporan.cari') }}" style="display:flex;gap:10px">
            @csrf
            <input type="text" name="nomor_pendaftaran" class="form-control" placeholder="cth: TF-2026-0042" required value="{{ request()->old('nomor_pendaftaran') }}">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

@isset($p)
<div class="card" style="max-width:580px">
    <div class="card-header">
        <h3>Hasil Tes</h3>
        <span class="badge badge-blue" style="font-family:'JetBrains Mono',monospace">{{ $p->nomor_pendaftaran }}</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px">
            <div><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Nama</div>{{ $p->user->name ?? '-' }}</div>
            <div><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">NIM/NIP</div>{{ $p->nim_nip }}</div>
            <div><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Prodi</div>{{ $p->program_studi }}</div>
            <div><div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Sesi</div>{{ $p->sesiTes->judul ?? '-' }}</div>
        </div>
        @if($percobaan)
        <div style="background:var(--navy-light);border-radius:10px;padding:20px;text-align:center">
            <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:16px">Skor TOEFL ITP</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
                @foreach(['Listening'=>$percobaan->skor_listening,'Structure'=>$percobaan->skor_structure,'Reading'=>$percobaan->skor_reading] as $label => $skor)
                <div>
                    <div style="font-size:22px;font-weight:800;color:var(--accent)">{{ $skor }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $label }}</div>
                </div>
                @endforeach
            </div>
            <div style="font-size:40px;font-weight:900;color:{{ $percobaan->skor_total>=500 ? 'var(--green)':($percobaan->skor_total>=400?'var(--gold)':'var(--red)') }}">
                {{ $percobaan->skor_total }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:4px">Skor Total TOEFL ITP</div>
        </div>
        @else
        <div class="alert alert-warning"><i class="fas fa-info-circle"></i> Peserta belum mengerjakan atau tes belum selesai.</div>
        @endif
    </div>
</div>
@endisset
@endsection
