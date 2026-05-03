@extends('layouts.admin')
@section('title','Detail Pendaftaran')
@section('page-title','Detail Pendaftaran')
@section('breadcrumb','Admin / Pendaftaran / Detail')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header">
        <h3>Detail Pendaftaran</h3>
        <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            @php $rows = [
                ['Nomor Daftar', $p->nomor_pendaftaran ?? '-'],
                ['Nama', $p->user->name ?? '-'],
                ['Email', $p->user->email ?? '-'],
                ['NIM/NIP', $p->nim_nip],
                ['Program Studi', $p->program_studi],
                ['Status Polman', ucfirst($p->status_polman)],
                ['No. Telepon', $p->no_telepon],
                ['Sesi Tes', $p->sesiTes->judul ?? '-'],
            ]; @endphp
            @foreach($rows as [$label, $val])
            <div>
                <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">{{ $label }}</div>
                <div style="font-size:14px">{{ $val }}</div>
            </div>
            @endforeach
        </div>
        <div style="margin-top:20px">
            <div style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Status</div>
            @if($p->status_pendaftaran=='dikonfirmasi') <span class="badge badge-green" style="font-size:13px">Dikonfirmasi</span>
            @elseif($p->status_pendaftaran=='menunggu')  <span class="badge badge-gold"  style="font-size:13px">Menunggu</span>
            @else <span class="badge badge-red" style="font-size:13px">Ditolak</span> @endif
        </div>
        @if($p->catatan_admin)
        <div style="margin-top:16px;padding:12px 16px;background:var(--navy-light);border-radius:8px;font-size:13px;color:var(--text-muted)">
            <strong>Catatan Admin:</strong> {{ $p->catatan_admin }}
        </div>
        @endif
        @if($p->berkas_identitas_url)
        <div style="margin-top:16px">
            <a href="{{ asset('storage/'.$p->berkas_identitas_url) }}" target="_blank" class="btn btn-outline">
                <i class="fas fa-id-card"></i> Lihat Berkas KTM
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
