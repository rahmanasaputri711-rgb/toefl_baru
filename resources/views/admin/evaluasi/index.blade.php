@extends('layouts.admin')
@section('title','Evaluasi')
@section('page-title','Evaluasi Hasil Tes')
@section('breadcrumb','Admin / Evaluasi')
@section('content')
<div class="grid-2" style="gap:20px;align-items:start">
    <div class="card">
        <div class="card-header"><h3>Buat Evaluasi Baru</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.evaluasi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Sesi Tes *</label>
                    <select name="sesi_id" class="form-control" required>
                        <option value="">-- Pilih Sesi --</option>
                        @foreach($sesiList as $s)
                        <option value="{{ $s->id }}">{{ $s->judul }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Judul Evaluasi *</label>
                    <input type="text" name="judul" class="form-control" required placeholder="cth: Evaluasi Tes Full Mei 2026">
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan *</label>
                    <textarea name="catatan" class="form-control" rows="4" required placeholder="Tuliskan catatan evaluasi..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Rekomendasi</label>
                    <textarea name="rekomendasi" class="form-control" rows="3" placeholder="Rekomendasi untuk peserta..."></textarea>
                </div>
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                        <input type="checkbox" name="untuk_user" value="1" checked style="accent-color:var(--accent)">
                        <span style="font-size:13px">Tampilkan ke user</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Evaluasi</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Daftar Evaluasi</h3></div>
        @forelse($evaluasi as $e)
        <div style="padding:16px;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:start;gap:12px">
                <div style="flex:1">
                    <div style="font-weight:700;font-size:14px">{{ $e->judul }}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:3px">{{ $e->sesiTes->judul ?? '-' }}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:6px">{{ Str::limit($e->catatan,100) }}</div>
                </div>
                <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end">
                    @if($e->is_published) <span class="badge badge-green">Published</span>
                    @else <span class="badge badge-gray">Draft</span> @endif
                    <div style="display:flex;gap:6px">
                        @if(!$e->is_published)
                        <form action="{{ route('admin.evaluasi.publish',$e->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-success btn-sm"><i class="fas fa-paper-plane"></i></button>
                        </form>
                        @endif
                        <form action="{{ route('admin.evaluasi.destroy',$e->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state"><i class="fas fa-star"></i><p>Belum ada evaluasi</p></div>
        @endforelse
        <div class="pagination-wrap">{{ $evaluasi->links() }}</div>
    </div>
</div>
@endsection
