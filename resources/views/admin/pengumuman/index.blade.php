@extends('layouts.admin')
@section('title','Pengumuman')
@section('page-title','Pengumuman')
@section('breadcrumb','Admin / Pengumuman')
@section('content')
<div class="grid-2" style="gap:20px;align-items:start">
    <div class="card">
        <div class="card-header"><h3>Buat Pengumuman</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.pengumuman.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Judul *</label>
                    <input type="text" name="judul" class="form-control" required placeholder="Judul pengumuman...">
                </div>
                <div class="form-group">
                    <label class="form-label">Konten *</label>
                    <textarea name="konten" class="form-control" rows="5" required placeholder="Isi pengumuman..."></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Target</label>
                        <select name="target" class="form-control">
                            <option value="semua">Semua (Publik)</option>
                            <option value="user">User Login Saja</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Expired (opsional)</label>
                        <input type="date" name="expired_at" class="form-control">
                    </div>
                </div>
                <div style="display:flex;gap:16px;margin-bottom:18px">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" name="is_published" value="1" checked style="accent-color:var(--accent)">
                        <span style="font-size:13px">Langsung publish</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" name="is_pinned" value="1" style="accent-color:var(--accent)">
                        <span style="font-size:13px">Pin di atas</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-bullhorn"></i> Buat Pengumuman</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Daftar Pengumuman</h3></div>
        @forelse($pengumuman as $p)
        <div style="padding:16px;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:start;gap:12px">
                <div>
                    <div style="font-weight:700;font-size:14px">
                        @if($p->is_pinned) <i class="fas fa-thumbtack" style="color:var(--gold);margin-right:5px"></i> @endif
                        {{ $p->judul }}
                    </div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:3px">{{ Str::limit($p->konten,80) }}</div>
                    <div style="margin-top:6px;display:flex;gap:6px">
                        @if($p->is_published) <span class="badge badge-green">Published</span>
                        @else <span class="badge badge-gray">Draft</span> @endif
                        <span class="badge badge-blue">{{ $p->target == 'semua' ? 'Publik' : 'User' }}</span>
                    </div>
                </div>
                <div style="display:flex;gap:6px;flex-shrink:0">
                    <form action="{{ route('admin.pengumuman.toggle',$p->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline btn-sm" title="{{ $p->is_published ? 'Unpublish':'Publish' }}">
                            <i class="fas fa-{{ $p->is_published ? 'eye-slash':'eye' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.pengumuman.destroy',$p->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state"><i class="fas fa-bullhorn"></i><p>Belum ada pengumuman</p></div>
        @endforelse
        <div class="pagination-wrap">{{ $pengumuman->links() }}</div>
    </div>
</div>
@endsection
