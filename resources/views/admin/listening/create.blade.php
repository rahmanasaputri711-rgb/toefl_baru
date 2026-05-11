@extends('layouts.admin')
@section('title','Upload Audio Listening')
@section('page-title','Upload Audio Listening')
@section('breadcrumb','Admin / Listening / Upload')

@section('content')
<div style="max-width:600px">
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-upload" style="color:var(--accent);margin-right:8px"></i>
            Upload Audio Listening
        </h3>
    </div>
    <div class="card-body" style="padding:24px">

        <div style="background:rgba(234,88,12,.07);border:1px solid rgba(234,88,12,.2);
            border-radius:10px;padding:14px 16px;margin-bottom:22px;font-size:13.5px;line-height:1.8">
            <div style="font-weight:700;color:#fb923c;margin-bottom:6px">
                🎧 Konsep 1 Audio → Banyak Soal
            </div>
            <ul style="margin:0;padding-left:18px;color:rgba(255,255,255,.7)">
                <li>Upload <strong>1 file audio .mp3</strong> yang berisi seluruh percakapan listening (±35 menit)</li>
                <li>Setelah upload, tambahkan soal satu per satu dengan menandai <strong>detik kapan soal muncul</strong></li>
                <li>Mahasiswa hanya memutar audio ini sekali — tidak bisa pause/rewind</li>
                <li>Soal akan muncul otomatis mengikuti timestamp audio</li>
            </ul>
        </div>

        <form action="{{ route('admin.listening.store') }}" method="POST"
            enctype="multipart/form-data" id="upload-form">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Paket <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama" class="form-control" required
                    placeholder="cth: TOEFL Full 2026 — Paket A"
                    value="{{ old('nama') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Paket <span style="color:var(--red)">*</span></label>
                <select name="tipe_paket" class="form-control" required>
                    <option value="full">🏆 Tes Full</option>
                    <option value="simulasi">🎯 Simulasi</option>
                    <option value="mini">⚡ Tes Mini</option>
                    <option value="praktik">📚 Praktik</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">File Audio <span style="color:var(--red)">*</span></label>
                <div id="drop-zone" style="border:2px dashed var(--border);border-radius:12px;
                    padding:32px;text-align:center;cursor:pointer;transition:all .2s"
                    onclick="document.getElementById('file-audio').click()"
                    ondragover="event.preventDefault();this.style.borderColor='var(--accent)'"
                    ondragleave="this.style.borderColor='var(--border)'"
                    ondrop="handleDrop(event)">
                    <i class="fas fa-music" id="drop-ico"
                        style="font-size:36px;color:var(--muted);display:block;margin-bottom:10px"></i>
                    <div id="drop-text" style="font-size:14px;font-weight:600;margin-bottom:6px">
                        Drag & drop file MP3 ke sini
                    </div>
                    <div style="font-size:12px;color:var(--muted)">
                        atau klik untuk pilih file · MP3/WAV/OGG · Maks 100MB
                    </div>
                    <div id="file-chosen" style="display:none;margin-top:12px;
                        background:rgba(22,163,74,.1);border:1px solid rgba(22,163,74,.3);
                        border-radius:8px;padding:8px 14px;font-size:13px;color:var(--green)">
                    </div>
                </div>
                <input type="file" id="file-audio" name="audio" accept=".mp3,.wav,.ogg,.m4a"
                    style="display:none" onchange="onFileSelect(this)">
            </div>

            {{-- Preview audio sebelum upload --}}
            <div id="audio-preview-wrap" style="display:none;margin-bottom:16px">
                <label class="form-label">Preview Audio</label>
                <audio id="audio-preview" controls style="width:100%;border-radius:8px"></audio>
            </div>

            {{-- Upload progress --}}
            <div id="progress-wrap" style="display:none;margin-bottom:16px">
                <div style="font-size:13px;margin-bottom:6px">Mengupload...</div>
                <div style="height:6px;background:var(--border);border-radius:3px">
                    <div id="progress-bar" style="height:6px;background:var(--blue);
                        border-radius:3px;width:0%;transition:width .3s"></div>
                </div>
                <div id="progress-pct" style="font-size:12px;color:var(--muted);margin-top:4px">0%</div>
            </div>

            <div style="display:flex;gap:10px">
                <button type="submit" id="btn-upload" class="btn btn-primary" disabled>
                    <i class="fas fa-upload"></i> Upload & Lanjut Tambah Soal
                </button>
                <a href="{{ route('admin.listening.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function onFileSelect(input) {
    const file = input.files[0];
    if (!file) return;

    const chosen = document.getElementById('file-chosen');
    chosen.textContent = '✓ ' + file.name + ' (' + (file.size/1024/1024).toFixed(1) + ' MB)';
    chosen.style.display = 'block';

    // Preview audio
    const url = URL.createObjectURL(file);
    const aud = document.getElementById('audio-preview');
    aud.src  = url;
    document.getElementById('audio-preview-wrap').style.display = 'block';

    document.getElementById('btn-upload').disabled = false;
    document.getElementById('drop-ico').style.color = 'var(--green)';
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('drop-zone').style.borderColor = 'var(--border)';
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const input = document.getElementById('file-audio');
    const dt    = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    onFileSelect(input);
}

// Upload dengan progress bar + redirect setelah selesai
document.getElementById('upload-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const fd   = new FormData(form);
    const xhr  = new XMLHttpRequest();

    document.getElementById('progress-wrap').style.display = 'block';
    document.getElementById('btn-upload').disabled = true;
    document.getElementById('btn-upload').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';

    xhr.upload.onprogress = function(e) {
        if (!e.lengthComputable) return;
        const pct = Math.round((e.loaded / e.total) * 100);
        document.getElementById('progress-bar').style.width = pct + '%';
        document.getElementById('progress-pct').textContent = pct + '%';
    };

    xhr.onload = function() {
        // XHR mengikuti redirect Laravel secara otomatis
        // responseURL = URL final setelah redirect
        if (xhr.status >= 200 && xhr.status < 400) {
            window.location.href = xhr.responseURL;
        } else {
            document.getElementById('btn-upload').disabled = false;
            document.getElementById('btn-upload').innerHTML = '<i class="fas fa-upload"></i> Upload & Lanjut Tambah Soal';
            alert('Upload gagal (status ' + xhr.status + '). Coba lagi.');
        }
    };

    xhr.onerror = function() {
        // Fallback: submit form biasa tanpa progress bar
        document.getElementById('btn-upload').disabled = false;
        document.getElementById('btn-upload').innerHTML = '<i class="fas fa-upload"></i> Upload & Lanjut Tambah Soal';
        form.submit();
    };

    xhr.open('POST', form.action);
    xhr.send(fd);
});
</script>
@endpush