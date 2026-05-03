@extends('layouts.admin')
@section('title','Grup Soal')
@section('page-title','Grup Soal')
@section('breadcrumb','Admin / Grup Soal')
@section('content')

{{-- Stat --}}
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-orange"><i class="fas fa-headphones-alt"></i></div>
        <div><div class="stat-val">{{ $grups->where('kategori','listening')->count() }}</div><div class="stat-label">Grup Listening</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-book-open"></i></div>
        <div><div class="stat-val">{{ $grups->where('kategori','reading')->count() }}</div><div class="stat-label">Grup Reading</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-layer-group"></i></div>
        <div><div class="stat-val">{{ $grups->sum('soal_count') }}</div><div class="stat-label">Total Soal Terhubung</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-object-group" style="color:var(--accent);margin-right:8px"></i>Daftar Grup Soal</h3>
        <a href="{{ route('admin.grup.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Grup Baru</a>
    </div>

    {{-- Filter --}}
    <div class="card-body" style="border-bottom:1px solid var(--border);padding-bottom:14px">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <div style="position:relative;flex:1;min-width:200px">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px"></i>
                <input type="text" name="search" class="form-control" style="padding-left:36px"
                    placeholder="Cari judul grup..." value="{{ request('search') }}">
            </div>
            <select name="kategori" class="form-control" style="width:160px">
                <option value="">Semua Kategori</option>
                <option value="listening" {{ request('kategori')==='listening'?'selected':'' }}>🎧 Listening</option>
                <option value="reading"   {{ request('kategori')==='reading'  ?'selected':'' }}>📖 Reading</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('admin.grup.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Reset</a>
        </form>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>Judul / Deskripsi</th>
                <th width="110">Kategori</th>
                <th width="80">Part</th>
                <th width="70">Audio</th>
                <th width="70">Teks</th>
                <th width="80">Soal</th>
                <th width="90">Status</th>
                <th width="140">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($grups as $i => $g)
        <tr>
            <td style="color:var(--text-muted);font-size:12px">{{ $grups->firstItem()+$i }}</td>
            <td>
                <div style="font-size:13.5px;font-weight:600">{{ $g->judul ?: '(Tanpa Judul)' }}</div>
                @if($g->deskripsi)
                <div style="font-size:12px;color:var(--text-muted)">{{ Str::limit($g->deskripsi,60) }}</div>
                @endif
            </td>
            <td>
                @if($g->kategori==='listening')
                <span class="badge badge-orange" style="font-size:11px"><i class="fas fa-headphones-alt" style="font-size:9px"></i> Listening</span>
                @else
                <span class="badge badge-blue" style="font-size:11px"><i class="fas fa-book-open" style="font-size:9px"></i> Reading</span>
                @endif
            </td>
            <td>
                @if($g->part)
                <span class="badge badge-gray" style="font-size:11px">Part {{ $g->part }}</span>
                @else<span style="color:var(--text-muted);font-size:12px">—</span>@endif
            </td>
            <td style="text-align:center">
                @if($g->audio_url)
                <i class="fas fa-volume-up" style="color:#16a34a;font-size:17px" title="{{ basename($g->audio_url) }}"></i>
                @else
                <i class="fas fa-volume-off" style="color:var(--border)"></i>
                @endif
            </td>
            <td style="text-align:center">
                @if($g->passage_teks)
                <i class="fas fa-file-alt" style="color:#1a56db;font-size:16px"></i>
                @else
                <i class="fas fa-file" style="color:var(--border)"></i>
                @endif
            </td>
            <td style="text-align:center">
                <span style="font-size:14px;font-weight:700;color:{{ $g->soal_count>0?'var(--accent)':'var(--text-muted)' }}">
                    {{ $g->soal_count }}
                </span>
                <span style="font-size:11px;color:var(--text-muted)"> soal</span>
            </td>
            <td>
                @if($g->is_aktif)
                <span class="badge badge-green" style="font-size:11px"><i class="fas fa-circle" style="font-size:7px"></i> Aktif</span>
                @else
                <span class="badge badge-gray" style="font-size:11px">Nonaktif</span>
                @endif
            </td>
            <td>
                <div style="display:flex;gap:5px">
                    <a href="{{ route('admin.grup.show', $g->id) }}" class="btn btn-primary btn-sm" title="Lihat & kelola soal">
                        <i class="fas fa-list"></i>
                    </a>
                    <a href="{{ route('admin.grup.edit', $g->id) }}" class="btn btn-warning btn-sm" title="Edit grup">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.grup.destroy', $g->id) }}" method="POST"
                        onsubmit="return confirm('Hapus grup ini? Soal yang terhubung tidak akan terhapus.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="9">
            <div class="empty-state">
                <i class="fas fa-object-group"></i>
                <p>Belum ada grup soal. <a href="{{ route('admin.grup.create') }}" style="color:var(--accent)">Buat grup pertama</a></p>
            </div>
        </td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;
        border-top:1px solid var(--border);font-size:13px">
        <div style="color:var(--text-muted)">{{ $grups->total() }} grup</div>
        {{ $grups->withQueryString()->links() }}
    </div>
</div>
@endsection
