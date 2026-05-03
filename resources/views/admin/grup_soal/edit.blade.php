@extends('layouts.admin')
@section('title','Edit Grup #'.$grup->id)
@section('page-title','Edit Grup Soal')
@section('breadcrumb','Admin / Grup Soal / Edit')
@section('content')
<div style="max-width:820px">

@if($errors->any())
<div class="alert alert-danger" style="margin-bottom:16px">
    <i class="fas fa-exclamation-circle"></i>
    <div>@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-pen" style="color:var(--gold);margin-right:8px"></i>Edit Grup #{{ $grup->id }}</h3>
        <a href="{{ route('admin.grup.show', $grup->id) }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.grup.update', $grup->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:18px">
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-control" readonly
                        value="{{ ucfirst($grup->kategori) }}"
                        style="opacity:.6;cursor:not-allowed">
                    <input type="hidden" name="kategori" value="{{ $grup->kategori }}">
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Part</label>
                    <select name="part" class="form-control">
                        <option value="">-- Pilih Part --</option>
                        @if($grup->kategori==='listening')
                            @foreach(['A'=>'Part A','B'=>'Part B','C'=>'Part C'] as $v=>$l)
                            <option value="{{ $v }}" {{ $grup->part===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Judul Grup</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul',$grup->judul) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi',$grup->deskripsi) }}</textarea>
            </div>

            @if($grup->kategori==='listening')
            {{-- Audio saat ini --}}
            @if($grup->audio_url)
            @php $aUrl = \App\Services\AudioService::resolveUrl($grup->audio_url); @endphp
            <div style="margin-bottom:16px">
                <label class="form-label"><i class="fas fa-music" style="color:#fdba74"></i> Audio Saat Ini</label>
                <div style="background:rgba(234,88,12,.06);border:1px solid rgba(234,88,12,.15);border-radius:10px;padding:14px">
                    <div style="font-size:12.5px;color:#fdba74;margin-bottom:10px">{{ basename($grup->audio_url) }}</div>
                    <div class="toefl-audio-wrap">
                        <div class="tap-bar">
                            <button type="button" class="tap-play-btn" id="btn-ea" onclick="tapToggle('ea')"><span class="tap-play-triangle" id="icon-ea"></span></button>
                            <div class="tap-track-outer" id="track-ea" onclick="tapSeek(event,'ea')">
                                <div class="tap-track-inner"><div class="tap-track-fill" id="fill-ea" style="width:0%"></div></div>
                                <div class="tap-thumb" id="thumb-ea" style="left:0%"></div>
                            </div>
                            <span class="tap-time" id="time-ea">00:00</span>
                            <button type="button" class="tap-vol-btn" onclick="tapToggleMute('ea')"><i class="fas fa-volume-up tap-vol-icon" id="volicon-ea"></i></button>
                            <audio id="aud-ea" data-mode="admin" preload="metadata" src="{{ $aUrl }}"
                                oncanplay="tapOnCanPlay('ea')" ontimeupdate="tapOnTimeUpdate('ea')" onended="tapOnEnded('ea')"></audio>
                        </div>
                        <div class="tap-status" id="status-ea">Klik ▶ untuk preview</div>
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;margin-top:10px;cursor:pointer">
                        <input type="checkbox" name="hapus_audio" value="1" style="accent-color:#dc2626">
                        <span style="font-size:13px;color:#f87171">🗑 Hapus audio ini</span>
                    </label>
                </div>
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Ganti Audio <span style="font-weight:400;color:rgba(255,255,255,.3)">(opsional)</span></label>
                <input type="file" name="audio_file" class="form-control" accept="audio/*" id="audio-file" onchange="onAudioSelect(this)">
                <div id="audio-preview" style="display:none;margin-top:10px">
                    <div class="toefl-audio-wrap">
                        <div class="tap-bar">
                            <button type="button" class="tap-play-btn" id="btn-na" onclick="tapToggle('na')"><span class="tap-play-triangle" id="icon-na"></span></button>
                            <div class="tap-track-outer" id="track-na" onclick="tapSeek(event,'na')">
                                <div class="tap-track-inner"><div class="tap-track-fill" id="fill-na" style="width:0%"></div></div>
                                <div class="tap-thumb" id="thumb-na" style="left:0%"></div>
                            </div>
                            <span class="tap-time" id="time-na">00:00</span>
                            <button type="button" class="tap-vol-btn" onclick="tapToggleMute('na')"><i class="fas fa-volume-up tap-vol-icon" id="volicon-na"></i></button>
                            <audio id="aud-na" data-mode="admin" preload="auto"
                                oncanplay="tapOnCanPlay('na')" ontimeupdate="tapOnTimeUpdate('na')" onended="tapOnEnded('na')"></audio>
                        </div>
                        <div class="tap-status" id="status-na">Klik ▶ preview audio baru</div>
                    </div>
                </div>
            </div>
            @endif

            @if($grup->kategori==='reading')
            <div class="form-group">
                <label class="form-label"><i class="fas fa-align-left" style="color:#93c5fd"></i> Teks Passage</label>
                <textarea name="passage_teks" class="form-control" rows="8">{{ old('passage_teks',$grup->passage_teks) }}</textarea>
            </div>
            @endif

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary" style="padding:11px 28px"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('admin.grup.show', $grup->id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function onAudioSelect(inp) {
    if (!inp.files||!inp.files[0]) return;
    const aud = document.getElementById('aud-na');
    aud.src = URL.createObjectURL(inp.files[0]);
    aud.load();
    document.getElementById('audio-preview').style.display = 'block';
}
</script>
@endpush
