@extends('layouts.user')
@section('title', $materi->judul)
@section('page-title', $materi->judul)
@section('breadcrumb','Home / Materi / Detail')
@section('content')

<div style="display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start">
    <div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div style="font-size:17px;font-weight:700">{{ $materi->judul }}</div>
                    <div style="margin-top:6px;display:flex;gap:8px">
                        <span class="badge {{ $materi->kategori=='reading' ? 'badge-blue':($materi->kategori=='listening'?'badge-orange':'badge-gold') }}">
                            {{ ucfirst($materi->kategori) }}
                        </span>
    
                    </div>
                </div>
                <a href="{{ route('user.materi.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            @if($materi->tipe_file === 'audio' && $materi->file_url)
            <div style="padding:20px;border-bottom:1px solid var(--border)">
                <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;margin-bottom:10px">
                    <i class="fas fa-headphones"></i> File Audio
                </div>
                <audio controls style="width:100%;filter:invert(.8)">
                    <source src="{{ asset('storage/'.$materi->file_url) }}">
                </audio>
            </div>
            @elseif($materi->tipe_file === 'pdf' && $materi->file_url)
            <div style="padding:20px;border-bottom:1px solid var(--border)">
                <a href="{{ asset('storage/'.$materi->file_url) }}" target="_blank" class="btn btn-outline">
                    <i class="fas fa-file-pdf" style="color:var(--red)"></i> Unduh / Lihat PDF
                </a>
            </div>
            @endif

            <div class="card-body">
                <div style="line-height:1.85;font-size:14.5px;color:#cbd5e1;white-space:pre-wrap">{{ $materi->konten }}</div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:14px">
        <div class="card">
            <div class="card-body" style="padding:18px">
                <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;margin-bottom:14px">Materi Lainnya</div>
                @foreach($materiLain as $ml)
                <a href="{{ route('user.materi.show',$ml->id) }}"
                   style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:8px;
                   text-decoration:none;color:var(--text);transition:background .15s;margin-bottom:4px"
                   onmouseover="this.style.background='var(--surface2)'" onmouseout="this.style.background='transparent'">
                    <div style="width:32px;height:32px;border-radius:8px;flex-shrink:0;
                        background:{{ $ml->kategori=='reading' ? 'rgba(59,130,246,.12)':($ml->kategori=='listening'?'rgba(249,115,22,.12)':'rgba(245,158,11,.12)') }};
                        color:{{ $ml->kategori=='reading' ? 'var(--accent)':($ml->kategori=='listening'?'var(--orange)':'var(--gold)') }};
                        display:flex;align-items:center;justify-content:center;font-size:14px">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div style="font-size:12.5px;font-weight:500">{{ Str::limit($ml->judul,38) }}</div>
                </a>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="padding:18px;text-align:center">
                <div style="font-size:12px;color:var(--muted);margin-bottom:12px">Sudah paham materi ini?</div>
                <a href="{{ route('user.latihan.index',['kategori'=>$materi->kategori]) }}" class="btn btn-primary btn-block btn-sm">
                    <i class="fas fa-pen-to-square"></i> Coba Latihan {{ ucfirst($materi->kategori) }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
