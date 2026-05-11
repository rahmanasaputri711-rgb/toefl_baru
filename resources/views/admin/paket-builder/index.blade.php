@extends('layouts.admin')
@section('title','Paket Builder')
@section('page-title','Paket Builder — Bank Soal')
@section('breadcrumb','Admin / Paket Builder')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-box" style="color:var(--accent);margin-right:8px"></i>Paket Soal</h3>
        <a href="{{ route('admin.paket-builder.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Buat Paket Baru
        </a>
    </div>
    <div class="card-body" style="padding:16px 20px">
        @forelse($paketList as $p)
        <div style="border:1px solid var(--border);border-radius:12px;margin-bottom:10px;
            padding:16px 20px;background:var(--navy-light);
            display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="font-size:15px;font-weight:700">{{ $p->nama }}</div>
                <div style="font-size:12.5px;color:var(--muted);margin-top:4px">
                    {{ $p->bank_soal_count }} soal &nbsp;·&nbsp;
                    <span style="font-weight:700;color:{{ $p->status==='valid'?'var(--green)':'var(--muted)' }}">
                        {{ strtoupper($p->status) }}
                    </span>
                    &nbsp;·&nbsp; {{ $p->created_at->format('d M Y') }}
                </div>
            </div>
            <a href="{{ route('admin.paket-builder.paket', $p->id) }}"
                class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Kelola Paket
            </a>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>Belum ada paket soal.<br>
            <a href="{{ route('admin.paket-builder.create') }}">Buat paket pertama</a>.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
