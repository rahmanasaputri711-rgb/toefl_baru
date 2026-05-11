@extends('layouts.admin')
@section('title','Reading Builder')
@section('page-title','Reading Builder')
@section('breadcrumb','Admin / Reading Builder')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-book-open" style="color:var(--accent);margin-right:8px"></i>
            Paket Soal Reading
        </h3>
        <a href="{{ route('admin.paket.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Buat Paket Baru
        </a>
    </div>
    <div class="card-body" style="padding:16px 20px">
        @forelse($paketList as $p)
        <div style="border:1px solid var(--border);border-radius:12px;margin-bottom:12px;
            padding:16px 20px;background:var(--navy-light);
            display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="font-size:15px;font-weight:700">{{ $p->nama }}</div>
                <div style="font-size:12.5px;color:var(--muted);margin-top:4px">
                    {{ $p->detail->count() }} soal
                    &nbsp;·&nbsp;
                    <span style="color:{{ $p->status==='valid'?'var(--green)':($p->status==='draft'?'var(--muted)':'var(--red)') }}">
                        {{ strtoupper($p->status) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.reading-builder.paket', $p->id) }}"
                class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Kelola Reading
            </a>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>Belum ada paket soal. <a href="{{ route('admin.paket.create') }}">Buat paket baru</a>.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
