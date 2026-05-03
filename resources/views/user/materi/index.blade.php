@extends('layouts.user')
@section('title','Materi')
@section('page-title','Materi Pembelajaran')
@section('breadcrumb','Home / Materi')
@section('content')

<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    @foreach([''=> 'Semua','reading'=>'Reading','listening'=>'Listening','structure'=>'Structure'] as $val=>$label)
    <a href="{{ route('user.materi.index', $val ? ['kategori'=>$val] : []) }}"
       class="btn btn-sm {{ request('kategori')==$val ? 'btn-primary':'btn-outline' }}">
        @if($val=='reading') <i class="fas fa-book-reader"></i>
        @elseif($val=='listening') <i class="fas fa-headphones"></i>
        @elseif($val=='structure') <i class="fas fa-pen-nib"></i>
        @else <i class="fas fa-th"></i> @endif
        {{ $label }}
    </a>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px">
@forelse($materi as $m)
<div class="card" style="transition:border-color .2s" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
    <div style="padding:20px">
        <div style="display:flex;align-items:flex-start;gap:14px">
            <div style="width:44px;height:44px;border-radius:10px;flex-shrink:0;
                background:{{ $m->kategori=='reading' ? 'rgba(59,130,246,.15)' : ($m->kategori=='listening' ? 'rgba(249,115,22,.15)' : 'rgba(245,158,11,.15)') }};
                color:{{ $m->kategori=='reading' ? 'var(--accent)' : ($m->kategori=='listening' ? 'var(--orange)' : 'var(--gold)') }};
                display:flex;align-items:center;justify-content:center;font-size:18px">
                <i class="fas fa-{{ $m->kategori=='reading' ? 'book-reader' : ($m->kategori=='listening' ? 'headphones' : 'pen-nib') }}"></i>
            </div>
            <div style="flex:1;overflow:hidden">
                <div style="font-weight:700;font-size:14px;margin-bottom:4px">{{ $m->judul }}</div>
                <div style="font-size:12px;color:var(--muted)">{{ Str::limit($m->deskripsi,80) }}</div>
                <div style="display:flex;align-items:center;gap:8px;margin-top:10px">
                    <span class="badge {{ $m->kategori=='reading' ? 'badge-blue' : ($m->kategori=='listening' ? 'badge-orange' : 'badge-gold') }}">
                        {{ ucfirst($m->kategori) }}
                    </span>
                    @if($m->tipe_file !== 'none')
                    <span class="badge badge-purple"><i class="fas fa-paperclip"></i> {{ strtoupper($m->tipe_file) }}</span>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <div style="padding:0 20px 18px">
        <a href="{{ route('user.materi.show',$m->id) }}" class="btn btn-primary btn-sm btn-block">
            <i class="fas fa-play-circle"></i> Baca Materi
        </a>
    </div>
</div>
@empty
<div style="grid-column:1/-1">
    <div class="empty-state"><i class="fas fa-book"></i><p>Belum ada materi tersedia</p></div>
</div>
@endforelse
</div>
@endsection
