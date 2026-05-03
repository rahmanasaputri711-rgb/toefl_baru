@extends('layouts.admin')
@section('title','Tambah Soal')
@section('page-title','Tambah Soal Baru')
@section('breadcrumb','Admin / Bank Soal / Tambah')

@section('content')
<div style="max-width:900px">

{{-- Alert error --}}
@if($errors->any())
<div class="alert alert-danger" style="margin-bottom:18px">
    <i class="fas fa-exclamation-circle"></i>
    <div>
        @foreach($errors->all() as $e)
        <div>• {{ $e }}</div>
        @endforeach
    </div>
</div>
@endif

<form action="{{ route('admin.soal.store') }}" method="POST" enctype="multipart/form-data" id="soal-form">
@csrf

{{-- ── ROW 1: Kategori + Kesulitan + Status ── --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-header">
        <h3><i class="fas fa-sliders-h" style="color:var(--accent);margin-right:8px"></i>Informasi Dasar</h3>
        <a href="{{ route('admin.soal.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:16px">
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Kategori <span style="color:var(--red)">*</span></label>
                <select name="kategori" class="form-control" required id="kat-sel" onchange="onKategoriChange()">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="reading"   {{ old('kategori')=='reading'   ?'selected':'' }}>📖 Reading</option>
                    <option value="listening" {{ old('kategori')=='listening' ?'selected':'' }}>🎧 Listening</option>
                    <option value="structure" {{ old('kategori')=='structure' ?'selected':'' }}>✏️ Structure</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Part</label>
                <select name="part" class="form-control" id="part-sel">
                    <option value="">-- Pilih Part --</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Tingkat Kesulitan <span style="color:var(--red)">*</span></label>
                <select name="tingkat_kesulitan" class="form-control" required>
                    <option value="easy"   {{ old('tingkat_kesulitan','medium')=='easy'   ?'selected':'' }}>🟢 Easy</option>
                    <option value="medium" {{ old('tingkat_kesulitan','medium')=='medium' ?'selected':'' }}>🟡 Medium</option>
                    <option value="hard"   {{ old('tingkat_kesulitan','medium')=='hard'   ?'selected':'' }}>🔴 Hard</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Pengaturan</label>
                <div style="display:flex;flex-direction:column;gap:10px;margin-top:8px">
                    <label style="display:flex;align-items:center;gap:9px;cursor:pointer">
                        <input type="checkbox" name="untuk_tes_full" value="1"
                            {{ old('untuk_tes_full',1) ? 'checked' : '' }}
                            style="width:16px;height:16px;accent-color:var(--accent)">
                        <span style="font-size:13px">Untuk Tes Full</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:9px;cursor:pointer">
                        <input type="checkbox" name="is_aktif" value="1"
                            {{ old('is_aktif',1) ? 'checked' : '' }}
                            style="width:16px;height:16px;accent-color:#16a34a">
                        <span style="font-size:13px">Aktifkan Soal</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── SECTION READING: Passage ── --}}
<div id="section-reading" style="display:none">
    <div class="card" style="margin-bottom:16px;border-left:4px solid #1a56db">
        <div class="card-header">
            <h3><i class="fas fa-align-left" style="color:#1a56db;margin-right:8px"></i>Passage / Teks Bacaan</h3>
            <span class="badge badge-blue">Reading</span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Teks Passage</label>
                <textarea name="passage_teks" class="form-control" rows="6"
                    placeholder="Masukkan teks bacaan / passage di sini...">{{ old('passage_teks') }}</textarea>
                <div style="font-size:11.5px;color:var(--text-muted);margin-top:5px">
                    <i class="fas fa-info-circle"></i>
                    Isi teks passage yang akan ditampilkan sebelum pertanyaan. Bisa satu passage untuk beberapa soal.
                </div>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Group ID Passage</label>
                <input type="text" name="group_id" class="form-control"
                    value="{{ old('group_id') }}" placeholder="cth: passage_klimatologi_1"
                    style="max-width:320px">
                <div style="font-size:11.5px;color:var(--text-muted);margin-top:5px">
                    <i class="fas fa-link"></i>
                    Gunakan Group ID yang sama untuk soal-soal dari satu passage yang sama.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── SECTION LISTENING: Upload Audio ── --}}
<div id="section-listening" style="display:none">
    <div class="card" style="margin-bottom:16px;border-left:4px solid #ea580c">
        <div class="card-header">
            <h3><i class="fas fa-headphones-alt" style="color:#ea580c;margin-right:8px"></i>File Audio</h3>
            <span class="badge badge-orange">Listening</span>
        </div>
        <div class="card-body">

            {{-- Drop zone upload --}}
            <div id="audio-dropzone"
                style="border:2px dashed #d1d5db;border-radius:12px;padding:32px;text-align:center;
                       cursor:pointer;transition:all .2s;background:#fafafa;margin-bottom:16px"
                onclick="document.getElementById('audio-file-input').click()"
                ondragover="event.preventDefault();this.style.borderColor='#ea580c';this.style.background='#fff7ed'"
                ondragleave="this.style.borderColor='#d1d5db';this.style.background='#fafafa'"
                ondrop="handleDrop(event)">
                <i class="fas fa-cloud-upload-alt" style="font-size:36px;color:#9ca3af;margin-bottom:12px;display:block"></i>
                <div style="font-size:15px;font-weight:600;color:#374151;margin-bottom:6px">
                    Drag & Drop file audio, atau klik untuk pilih
                </div>
                <div style="font-size:12.5px;color:#9ca3af">
                    Format: MP3, OGG, WAV, M4A — Maksimal 20MB
                </div>
                <input type="file" id="audio-file-input" name="audio_file"
                    accept="audio/*" style="display:none" onchange="handleFileSelect(this)">
            </div>

            {{-- File info (muncul setelah pilih file) --}}
            <div id="file-info" style="display:none;margin-bottom:16px">
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;
                    padding:14px 16px;display:flex;align-items:center;gap:14px">
                    <i class="fas fa-file-audio" style="font-size:24px;color:#16a34a;flex-shrink:0"></i>
                    <div style="flex:1">
                        <div style="font-size:14px;font-weight:600;color:#15803d" id="file-name">-</div>
                        <div style="font-size:12px;color:#16a34a" id="file-size">-</div>
                    </div>
                    <button type="button" onclick="clearFile()"
                        style="background:none;border:none;cursor:pointer;color:#dc2626;font-size:18px">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </div>
            </div>

            {{-- Preview Player (muncul setelah pilih file) --}}
            <div id="audio-preview-wrap" style="display:none">
                <label class="form-label" style="margin-bottom:8px">
                    <i class="fas fa-play-circle" style="color:#ea580c"></i> Preview Audio
                </label>
                <div class="toefl-audio-wrap">
                    <div class="tap-bar">
                        <button type="button" class="tap-play-btn" id="btn-new-audio"
                            onclick="tapToggle('new-audio')" aria-label="Play">
                            <span class="tap-play-triangle" id="icon-new-audio"></span>
                        </button>
                        <div class="tap-track-outer" id="track-new-audio"
                            onclick="tapSeek(event,'new-audio')">
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
                            onended="tapOnEnded('new-audio')">
                        </audio>
                    </div>
                    <div class="tap-status" id="status-new-audio">Klik ▶ untuk preview</div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── PERTANYAAN ── --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-header">
        <h3><i class="fas fa-question-circle" style="color:var(--accent);margin-right:8px"></i>Pertanyaan & Jawaban</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Teks Pertanyaan <span style="color:var(--red)">*</span></label>
            <textarea name="pertanyaan" class="form-control" required rows="3"
                placeholder="Masukkan pertanyaan di sini...">{{ old('pertanyaan') }}</textarea>
        </div>

        {{-- Pilihan Jawaban --}}
        <div style="background:var(--navy-light);border-radius:12px;padding:18px;margin-bottom:16px">
            <div style="font-size:12px;font-weight:700;color:var(--text-muted);text-transform:uppercase;
                letter-spacing:.8px;margin-bottom:14px">
                <i class="fas fa-list-ul"></i> Pilihan Jawaban
            </div>
            @foreach(['a','b','c','d'] as $opt)
            @php
                $colors = ['a'=>'#3b82f6','b'=>'#10b981','c'=>'#f59e0b','d'=>'#ef4444'];
            @endphp
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <div style="width:32px;height:32px;border-radius:8px;background:{{ $colors[$opt] }};
                    color:#fff;display:flex;align-items:center;justify-content:center;
                    font-size:13px;font-weight:800;flex-shrink:0">
                    {{ strtoupper($opt) }}
                </div>
                <input type="text" name="pilihan_{{ $opt }}" class="form-control"
                    required placeholder="Masukkan pilihan {{ strtoupper($opt) }}..."
                    value="{{ old('pilihan_'.$opt) }}" style="margin-bottom:0">
            </div>
            @endforeach
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Jawaban Benar <span style="color:var(--red)">*</span></label>
                <select name="jawaban_benar" class="form-control" required>
                    @foreach(['a','b','c','d'] as $opt)
                    <option value="{{ $opt }}" {{ old('jawaban_benar')===$opt?'selected':'' }}>
                        Pilihan {{ strtoupper($opt) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Pembahasan <span style="color:var(--text-muted);font-weight:400">(opsional)</span></label>
                <textarea name="pembahasan" class="form-control" rows="2"
                    placeholder="Jelaskan kenapa jawaban tersebut benar...">{{ old('pembahasan') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- ── ACTION BUTTONS ── --}}
<div style="display:flex;gap:12px;align-items:center">
    <button type="submit" class="btn btn-primary" style="padding:12px 32px;font-size:15px">
        <i class="fas fa-save"></i> Simpan Soal
    </button>
    <a href="{{ route('admin.soal.index') }}" class="btn btn-outline" style="padding:12px 24px">
        <i class="fas fa-times"></i> Batal
    </a>
    <span style="font-size:12.5px;color:var(--text-muted);margin-left:4px">
        <i class="fas fa-info-circle"></i> Field bertanda * wajib diisi
    </span>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
// ── Kategori change handler ──────────────────────────────────
const PARTS = {
    listening: [['A','Part A (Short Conversations)'],['B','Part B (Longer Conversations)'],['C','Part C (Talks)']],
    structure: [['A','Part A (Structure)'],['B','Part B (Written Expression)']],
    reading:   [],
};
function onKategoriChange() {
    const val     = document.getElementById('kat-sel').value;
    const partSel = document.getElementById('part-sel');
    const opts    = PARTS[val] || [];
    partSel.innerHTML = '<option value="">-- Pilih Part --</option>' +
        opts.map(([v,l]) => `<option value="${v}">${l}</option>`).join('');
    document.getElementById('section-reading').style.display   = val === 'reading'   ? 'block' : 'none';
    document.getElementById('section-listening').style.display = val === 'listening' ? 'block' : 'none';
}

// Jalankan saat halaman load (untuk old value setelah validasi gagal)
document.addEventListener('DOMContentLoaded', function() {
    onKategoriChange();
});

// ── Audio file handling ──────────────────────────────────────
function handleFileSelect(input) {
    if (!input.files || !input.files[0]) return;
    showFileInfo(input.files[0]);
    loadAudioPreview(input.files[0]);
}

function handleDrop(event) {
    event.preventDefault();
    const dz = document.getElementById('audio-dropzone');
    dz.style.borderColor = '#d1d5db';
    dz.style.background  = '#fafafa';
    const files = event.dataTransfer.files;
    if (!files.length) return;
    const file = files[0];
    if (!file.type.startsWith('audio/')) {
        alert('File harus berupa audio (MP3, OGG, WAV, M4A)');
        return;
    }
    // Set ke input file
    const dt  = new DataTransfer();
    dt.items.add(file);
    const inp = document.getElementById('audio-file-input');
    inp.files = dt.files;
    showFileInfo(file);
    loadAudioPreview(file);
}

function showFileInfo(file) {
    const mb = (file.size / 1024 / 1024).toFixed(2);
    document.getElementById('file-name').textContent = file.name;
    document.getElementById('file-size').textContent = mb + ' MB';
    document.getElementById('file-info').style.display = 'block';
}

function loadAudioPreview(file) {
    const aud = document.getElementById('aud-new-audio');
    if (!aud) return;
    aud.src = URL.createObjectURL(file);
    aud.load();
    document.getElementById('audio-preview-wrap').style.display = 'block';
    document.getElementById('status-new-audio').textContent = 'Klik ▶ untuk preview';
}

function clearFile() {
    document.getElementById('audio-file-input').value = '';
    document.getElementById('file-info').style.display = 'none';
    document.getElementById('audio-preview-wrap').style.display = 'none';
    const aud = document.getElementById('aud-new-audio');
    if (aud) { aud.pause(); aud.src = ''; }
    // Reset dropzone
    document.getElementById('audio-dropzone').style.borderColor = '#d1d5db';
    document.getElementById('audio-dropzone').style.background  = '#fafafa';
}

// ── Form validation ──────────────────────────────────────────
document.getElementById('soal-form').addEventListener('submit', function(e) {
    const kat = document.getElementById('kat-sel').value;
    if (!kat) {
        e.preventDefault();
        alert('Pilih kategori soal terlebih dahulu.');
        return;
    }
});
</script>
@endpush
