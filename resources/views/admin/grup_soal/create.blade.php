@extends('layouts.admin')
@section('title','Buat Grup Soal')
@section('page-title','Buat Grup Soal Baru')
@section('breadcrumb','Admin / Grup Soal / Buat')
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
        <h3><i class="fas fa-plus-circle" style="color:var(--accent);margin-right:8px"></i>Form Buat Grup Soal</h3>
        <a href="{{ route('admin.grup.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">

        {{-- Info --}}
        <div class="alert alert-info" style="margin-bottom:20px">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Grup Soal</strong> digunakan untuk mengelompokkan beberapa soal di bawah 1 audio atau 1 teks passage.
                Setelah grup dibuat, Anda bisa langsung menambahkan soal-soal ke dalamnya.
            </div>
        </div>

        <form action="{{ route('admin.grup.store') }}" method="POST" enctype="multipart/form-data" id="grup-form">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:18px">
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Kategori <span style="color:var(--red)">*</span></label>
                    <select name="kategori" class="form-control" required id="kat-sel" onchange="onKatChange()">
                        <option value="">-- Pilih --</option>
                        <option value="listening" {{ old('kategori')==='listening'?'selected':'' }}>🎧 Listening</option>
                        <option value="reading"   {{ old('kategori')==='reading'  ?'selected':'' }}>📖 Reading</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:0" id="part-wrap">
                    <label class="form-label">Part</label>
                    <select name="part" class="form-control" id="part-sel">
                        <option value="">-- Pilih Part --</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Judul Grup <span style="color:rgba(255,255,255,.3);font-weight:400">(opsional)</span></label>
                    <input type="text" name="judul" class="form-control"
                        value="{{ old('judul') }}" placeholder="cth: Conversation 1">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi <span style="color:rgba(255,255,255,.3);font-weight:400">(opsional)</span></label>
                <textarea name="deskripsi" class="form-control" rows="2"
                    placeholder="Catatan tambahan...">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- LISTENING: Audio upload --}}
            <div id="audio-section" style="display:none">
                <div style="background:rgba(234,88,12,.06);border:1px solid rgba(234,88,12,.15);
                    border-radius:12px;overflow:hidden;margin-bottom:18px">
                    <div style="padding:12px 16px;border-bottom:1px solid rgba(234,88,12,.12);
                        display:flex;align-items:center;gap:8px">
                        <i class="fas fa-headphones-alt" style="color:#fdba74"></i>
                        <span style="font-size:13px;font-weight:700;color:#fdba74">File Audio Listening</span>
                        <span style="font-size:11px;color:rgba(251,146,60,.5);margin-left:4px">MP3/OGG/WAV — max 20MB</span>
                    </div>
                    <div style="padding:16px">
                        <div id="audio-dz"
                            style="border:2px dashed rgba(234,88,12,.3);border-radius:10px;padding:28px;
                            text-align:center;cursor:pointer;transition:all .2s;background:rgba(234,88,12,.03)"
                            onclick="document.getElementById('audio-file').click()"
                            ondragover="event.preventDefault();this.style.borderColor='#fb923c'"
                            ondragleave="this.style.borderColor='rgba(234,88,12,.3)'"
                            ondrop="dropAudio(event)">
                            <i class="fas fa-cloud-upload-alt" style="font-size:30px;color:rgba(251,146,60,.4);display:block;margin-bottom:10px"></i>
                            <div style="font-size:14px;font-weight:600;color:rgba(255,255,255,.6)">Drag & Drop atau klik untuk pilih audio</div>
                            <div style="font-size:11.5px;color:rgba(255,255,255,.3);margin-top:5px">MP3, OGG, WAV, M4A — max 20MB</div>
                            <input type="file" id="audio-file" name="audio_file" accept="audio/*" style="display:none" onchange="onAudioSelect(this)">
                        </div>

                        <div id="audio-info" style="display:none;margin-top:12px">
                            <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);
                                border-radius:9px;padding:12px 14px;display:flex;align-items:center;gap:12px">
                                <i class="fas fa-file-audio" style="font-size:22px;color:#4ade80;flex-shrink:0"></i>
                                <div style="flex:1">
                                    <div style="font-size:13.5px;font-weight:600;color:#6ee7b7" id="audio-fname">-</div>
                                    <div style="font-size:11.5px;color:rgba(110,231,183,.7)" id="audio-fsize">-</div>
                                </div>
                                <button type="button" onclick="clearAudio()"
                                    style="background:none;border:none;cursor:pointer;color:#f87171;font-size:16px">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>

                            {{-- Preview player --}}
                            <div style="margin-top:12px">
                                <div class="toefl-audio-wrap">
                                    <div class="tap-bar">
                                        <button type="button" class="tap-play-btn" id="btn-new-audio" onclick="tapToggle('new-audio')">
                                            <span class="tap-play-triangle" id="icon-new-audio"></span>
                                        </button>
                                        <div class="tap-track-outer" id="track-new-audio" onclick="tapSeek(event,'new-audio')">
                                            <div class="tap-track-inner">
                                                <div class="tap-track-fill" id="fill-new-audio" style="width:0%"></div>
                                            </div>
                                            <div class="tap-thumb" id="thumb-new-audio" style="left:0%"></div>
                                        </div>
                                        <span class="tap-time" id="time-new-audio">00:00</span>
                                        <button type="button" class="tap-vol-btn" onclick="tapToggleMute('new-audio')">
                                            <i class="fas fa-volume-up tap-vol-icon" id="volicon-new-audio"></i>
                                        </button>
                                        <audio id="aud-new-audio" data-mode="admin" preload="auto"
                                            oncanplay="tapOnCanPlay('new-audio')"
                                            ontimeupdate="tapOnTimeUpdate('new-audio')"
                                            onended="tapOnEnded('new-audio')"></audio>
                                    </div>
                                    <div class="tap-status" id="status-new-audio">Klik ▶ untuk preview</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- READING: Passage teks --}}
            <div id="passage-section" style="display:none">
                <div style="background:rgba(26,86,219,.06);border:1px solid rgba(26,86,219,.15);
                    border-radius:12px;overflow:hidden;margin-bottom:18px">
                    <div style="padding:12px 16px;border-bottom:1px solid rgba(26,86,219,.12);
                        display:flex;align-items:center;gap:8px">
                        <i class="fas fa-align-left" style="color:#93c5fd"></i>
                        <span style="font-size:13px;font-weight:700;color:#93c5fd">Teks Passage</span>
                    </div>
                    <div style="padding:16px">
                        <textarea name="passage_teks" class="form-control" rows="8"
                            placeholder="Tempelkan teks bacaan / passage di sini...&#10;&#10;Soal-soal yang ditambahkan ke grup ini akan merujuk ke teks passage ini.">{{ old('passage_teks') }}</textarea>
                        <div style="font-size:11.5px;color:rgba(255,255,255,.3);margin-top:6px">
                            <i class="fas fa-info-circle"></i>
                            Teks ini akan tampil bersama setiap soal dalam grup ini.
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary" style="padding:11px 28px">
                    <i class="fas fa-save"></i> Buat Grup & Tambah Soal
                </button>
                <a href="{{ route('admin.grup.index') }}" class="btn btn-outline" style="padding:11px 22px">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
const PARTS = {
    listening: [['A','Part A (Short Conversations)'],['B','Part B (Longer Conversations)'],['C','Part C (Talks & Lectures)']],
    reading:   [],
};

function onKatChange() {
    const kat    = document.getElementById('kat-sel').value;
    const opts   = PARTS[kat] || [];
    const partSel = document.getElementById('part-sel');
    partSel.innerHTML = '<option value="">-- Pilih Part --</option>' +
        opts.map(([v,l]) => `<option value="${v}">${l}</option>`).join('');

    document.getElementById('audio-section').style.display   = kat === 'listening' ? 'block' : 'none';
    document.getElementById('passage-section').style.display = kat === 'reading'   ? 'block' : 'none';
}

function onAudioSelect(inp) {
    if (!inp.files || !inp.files[0]) return;
    const f   = inp.files[0];
    const mb  = (f.size/1024/1024).toFixed(2);
    document.getElementById('audio-fname').textContent = f.name;
    document.getElementById('audio-fsize').textContent = mb + ' MB';
    document.getElementById('audio-info').style.display = 'block';
    const aud = document.getElementById('aud-new-audio');
    aud.src   = URL.createObjectURL(f);
    aud.load();
    document.getElementById('status-new-audio').textContent = 'Klik ▶ untuk preview';
}

function dropAudio(e) {
    e.preventDefault();
    const dz   = document.getElementById('audio-dz');
    dz.style.borderColor = 'rgba(234,88,12,.3)';
    const file = e.dataTransfer.files[0];
    if (!file || !file.type.startsWith('audio/')) { alert('File harus berformat audio.'); return; }
    const dt  = new DataTransfer();
    dt.items.add(file);
    const inp = document.getElementById('audio-file');
    inp.files = dt.files;
    onAudioSelect(inp);
}

function clearAudio() {
    document.getElementById('audio-file').value = '';
    document.getElementById('audio-info').style.display = 'none';
    const aud = document.getElementById('aud-new-audio');
    if (aud) { aud.pause(); aud.src = ''; }
}

document.addEventListener('DOMContentLoaded', () => onKatChange());
</script>
@endpush
