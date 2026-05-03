@extends('layouts.admin')
@section('title','Materi')
@section('page-title','Materi Pembelajaran')
@section('breadcrumb','Admin / Materi')
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Materi</h3>
        <a href="{{ route('admin.materi.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Materi</a>
    </div>
    <div class="card-body" style="padding-bottom:8px">
        <form method="GET" class="search-bar">
            <select name="kategori" class="form-control" style="width:160px" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="reading"   {{ request('kategori')=='reading'   ? 'selected':'' }}>Reading</option>
                <option value="listening" {{ request('kategori')=='listening' ? 'selected':'' }}>Listening</option>
                <option value="structure" {{ request('kategori')=='structure' ? 'selected':'' }}>Structure</option>
            </select>
        </form>
    </div>
    <table class="tbl">
        <thead><tr><th>Urutan</th><th>Judul</th><th>Kategori</th><th>Tipe File</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($materi as $m)
        <tr>
            <td>
                <div style="font-weight:600">{{ $m->judul }}</div>
                @if($m->deskripsi)<div style="font-size:11px;color:var(--text-muted)">{{ Str::limit($m->deskripsi,60) }}</div>@endif
            </td>
            <td>
                @if($m->kategori=='reading')   <span class="badge badge-blue">Reading</span>
                @elseif($m->kategori=='listening') <span class="badge badge-orange">Listening</span>
                @else <span class="badge badge-gold">Structure</span> @endif
            </td>
            <td><span class="badge badge-gray">{{ strtoupper($m->tipe_file) }}</span></td>
            <td>
                <div style="display:flex;gap:6px">
                    <a href="{{ route('admin.materi.edit',$m->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                    <form action="{{ route('admin.materi.destroy',$m->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7"><div class="empty-state"><i class="fas fa-book"></i><p>Belum ada materi</p></div></td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap"><div class="pg-info">{{ $materi->total() }} materi</div>{{ $materi->links() }}</div>
</div>
@endsection
