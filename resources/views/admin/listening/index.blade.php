@extends('layouts.admin')
@section('title','Listening — Bank Soal')
@section('page-title','Bank Soal Listening')
@section('breadcrumb','Admin / Listening')

@section('content')

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-headphones" style="color:var(--accent);margin-right:8px"></i>
            Paket Audio Listening
        </h3>
        <a href="{{ route('admin.listening.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Upload Audio Baru
        </a>
    </div>

    <div class="card-body" style="padding:16px 20px">
        @forelse($paketList as $p)
        <div style="border:1px solid var(--border);border-radius:12px;
            margin-bottom:12px;overflow:hidden;transition:border-color .15s"
            onmouseover="this.style.borderColor='var(--accent)'"
            onmouseout="this.style.borderColor='var(--border)'">

            <div style="background:var(--navy-light);padding:16px 18px;
                display:flex;align-items:center;gap:16px;flex-wrap:wrap">

                {{-- Icon --}}
                <div style="width:44px;height:44px;border-radius:10px;flex-shrink:0;
                    background:rgba(234,88,12,.15);border:1px solid rgba(234,88,12,.3);
                    display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-music" style="color:#fb923c;font-size:18px"></i>
                </div>

                {{-- Info --}}
                <div style="flex:1;min-width:0">
                    <div style="font-size:15px;font-weight:700;margin-bottom:4px">
                        {{ $p->nama }}
                    </div>
                    <div style="font-size:12.5px;color:var(--muted);
                        display:flex;gap:12px;flex-wrap:wrap">
                        <span>
                            <span style="background:rgba(26,86,219,.15);color:var(--accent);
                                padding:1px 8px;border-radius:5px;font-size:11px;font-weight:600">
                                {{ strtoupper($p->tipe_paket) }}
                            </span>
                        </span>
                        <span><i class="fas fa-question-circle" style="font-size:11px"></i>
                            {{ $p->soal_list_count }} soal
                        </span>
                        @if($p->durasi_detik > 0)
                        <span><i class="fas fa-clock" style="font-size:11px"></i>
                            {{ $p->durasi_format }}
                        </span>
                        @endif
                        <span style="color:{{ $p->is_aktif ? 'var(--green)' : 'var(--red)' }}">
                            <i class="fas fa-circle" style="font-size:8px"></i>
                            {{ $p->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                {{-- Aksi --}}
                <div style="display:flex;gap:8px;flex-shrink:0">
                    <a href="{{ route('admin.listening.show', $p->id) }}"
                        class="btn btn-primary btn-sm">
                        <i class="fas fa-headphones"></i> Kelola Soal
                    </a>
                    <form action="{{ route('admin.listening.destroy', $p->id) }}" method="POST"
                        onsubmit="return confirm('Hapus paket ini beserta {{ $p->soal_list_count }} soalnya?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Progress bar soal --}}
            @php $pct = min(100, ($p->soal_list_count / 50) * 100); @endphp
            <div style="padding:8px 18px 10px;background:rgba(0,0,0,.15)">
                <div style="display:flex;justify-content:space-between;
                    font-size:11.5px;color:var(--muted);margin-bottom:4px">
                    <span>Progress soal</span>
                    <span>{{ $p->soal_list_count }} / 50</span>
                </div>
                <div style="height:4px;background:var(--border);border-radius:2px">
                    <div style="height:4px;border-radius:2px;width:{{ $pct }}%;
                        background:{{ $pct >= 100 ? 'var(--green)' : 'var(--accent)' }};
                        transition:width .3s"></div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-headphones"></i>
            <p>Belum ada paket audio listening.<br>
            <a href="{{ route('admin.listening.create') }}">Upload audio pertama</a>.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
