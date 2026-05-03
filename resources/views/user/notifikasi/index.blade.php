@extends('layouts.user')
@section('title','Notifikasi')
@section('page-title','Notifikasi')
@section('breadcrumb','Home / Notifikasi')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
    <div>
        @php $unread = $notifikasi->where('is_read',0)->count(); @endphp
        @if($unread > 0)
        <span style="background:rgba(26,86,219,.1);color:var(--blue);padding:4px 12px;
            border-radius:20px;font-size:13px;font-weight:700">
            {{ $unread }} belum dibaca
        </span>
        @endif
    </div>
    @if($notifikasi->where('is_read',0)->count() > 0)
    <form action="{{ route('user.notifikasi.baca-semua') }}" method="POST">
        @csrf @method('PATCH')
        <button class="btn btn-outline btn-sm">
            <i class="fas fa-check-double"></i> Tandai Semua Dibaca
        </button>
    </form>
    @endif
</div>

<div class="card">
    @forelse($notifikasi as $n)
    @php
        $tipeColor = match($n->tipe) {
            'sukses','hasil'   => ['bg'=>'rgba(22,163,74,.08)',   'bd'=>'rgba(22,163,74,.2)',   'ico'=>'check-circle',    'c'=>'var(--green)'],
            'danger','sanksi'  => ['bg'=>'rgba(220,38,38,.08)',   'bd'=>'rgba(220,38,38,.2)',   'ico'=>'exclamation-circle','c'=>'var(--red)'],
            'warning'          => ['bg'=>'rgba(217,119,6,.08)',   'bd'=>'rgba(217,119,6,.2)',   'ico'=>'exclamation-triangle','c'=>'var(--gold)'],
            default            => ['bg'=>'rgba(26,86,219,.06)',   'bd'=>'rgba(26,86,219,.18)',  'ico'=>'bell',            'c'=>'var(--blue)'],
        };
    @endphp
    <div style="display:flex;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);
        background:{{ $n->is_read ? '#fff' : 'rgba(26,86,219,.025)' }};
        transition:background .2s">
        <div style="width:38px;height:38px;border-radius:50%;flex-shrink:0;
            background:{{ $tipeColor['bg'] }};border:1.5px solid {{ $tipeColor['bd'] }};
            display:flex;align-items:center;justify-content:center">
            <i class="fas fa-{{ $tipeColor['ico'] }}" style="color:{{ $tipeColor['c'] }};font-size:15px"></i>
        </div>
        <div style="flex:1;min-width:0">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px">
                <div style="font-size:14px;font-weight:{{ $n->is_read ? '500' : '700' }};
                    color:var(--text)">
                    {{ $n->judul }}
                    @if($n->is_important)
                    <i class="fas fa-star" style="color:var(--gold);font-size:11px;margin-left:4px"></i>
                    @endif
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                    <span style="font-size:11px;color:var(--muted)">
                        {{ $n->created_at->diffForHumans() }}
                    </span>
                    @if(!$n->is_read)
                    <span style="width:8px;height:8px;border-radius:50%;
                        background:var(--blue);flex-shrink:0"></span>
                    @endif
                </div>
            </div>
            <div style="font-size:13px;color:var(--muted);margin-top:3px;line-height:1.6">
                {{ $n->pesan }}
            </div>
            @if(!$n->is_read)
            <form action="{{ route('user.notifikasi.baca', $n->id) }}" method="POST" style="display:inline">
                @csrf @method('PATCH')
                <button class="btn btn-outline btn-sm" style="margin-top:8px;font-size:11.5px;padding:3px 10px">
                    <i class="fas fa-check" style="font-size:10px"></i> Tandai Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:60px 20px">
        <i class="fas fa-bell-slash" style="font-size:36px;color:var(--muted);display:block;margin-bottom:12px"></i>
        <div style="font-size:15px;font-weight:600;margin-bottom:6px">Tidak Ada Notifikasi</div>
        <div style="font-size:13px;color:var(--muted)">Notifikasi akan muncul di sini ketika ada aktivitas.</div>
    </div>
    @endforelse
</div>

<div style="padding:14px 0">{{ $notifikasi->links() }}</div>
@endsection
