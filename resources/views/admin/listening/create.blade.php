@extends('layouts.admin')
@section('title','Upload Audio Listening')
@section('page-title','Upload Audio Listening')
@section('breadcrumb','Admin / Listening / Upload')

@section('content')
<div style="max-width:640px">
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-upload" style="color:var(--accent);margin-right:8px"></i>
            Upload Audio Listening
        </h3>
    </div>
    <div class="card-body" style="padding:24px">

        <div id="alert" style="display:none;margin-bottom:16px"></div>

        <form id="upload-form" enctype="multipart/form-data">
            @csrf

            {{-- Pilih tipe upload ──────────────────────────────── --}}
            <div class="form-group">
                <label class="form-label">
                    Tipe Upload <span style="color:var(--red)">*</span>
                </label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <label id="card-paket"
                        style="border:2px solid var(--blue);background:rgba(26,86,219,.08);
                        border-radius:12px;padding:16px;cursor:pointer;transition:all .15s"
                        onclick="pilihTipe('paket')">
                        <input type="radio" name="tipe_upload" value="paket"
                            id="tp-paket" style="display:none" checked>
                        <div style="font-size:22px;margin-bottom:8px">📦</div>
                        <div style="font-size:13.5px;font-weight:700;margin-bottom:4px">
                            1 Audio Full Paket
                        </div>
                        <div style="font-size:12px;color:var(--muted);line-height:1.6">
                            Upload 1 file audio untuk seluruh sesi listening (±35 menit).
                            Semua soal terhubung ke audio ini.
                        </div>
                    </label>
                    <label id="card-modul"
                        style="border:2px solid var(--border);
                        border-radius:12px;padding:16px;cursor:pointer;transition:all .15s"
                        onclick="pilihTipe('modul')">
                        <input type="radio" name="tipe_upload" value="modul"
                            id="tp-modul" style="display:none">
                        <div style="font-size:22px;margin-bottom:8px">🧩</div>
                        <div style="font-size:13.5px;font-weight:700;margin-bottom:4px">
                            Audio Per Modul
                        </div>
                        <div style="font-size:12px;color:var(--muted);line-height:1.6">
                            Upload Part A, B, C terpisah. Sistem otomatis hitung
                            offset dan sinkronkan soal ke timeline gabungan.
                        </div>
                    </label>
                </div>
            </div>

            {{-- Field per-modul (muncul jika pilih modul) ──────── --}}
            <div id="field-modul" style="display:none">
                <div style="background:rgba(139,92,246,.07);border:1px solid rgba(139,92,246,.2);
                    border-radius:9px;padding:12px 14px;margin-bottom:14px;font-size:13px;
                    line-height:1.7">
                    <strong style="color:#a78bfa">🧩 Upload Per Modul:</strong>
                    Upload Part A dulu → sistem catat durasinya → upload Part B →
                    offset otomatis dihitung dari durasi Part A. Begitu seterusnya.
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group" style="margin:0">
                        <label class="form-label" style="font-size:12px">
                            Paket Soal <span style="color:var(--red)">*</span>
                        </label>
                        <select name="paket_soal_id" class="form-control">
                            <option value="">-- Pilih paket --</option>
                            @foreach($paketList as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" style="font-size:12px">
                            Urutan Part <span style="color:var(--red)">*</span>
                            <small style="color:var(--muted)">(Part A=1, B=2, C=3)</small>
                        </label>
                        <input type="number" name="urutan_modul" class="form-control"
                            min="1" max="10" value="1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:12px">Keterangan Modul</label>
                    <input type="text" name="keterangan" class="form-control"
                        placeholder="cth: Part A — Short Dialogues (Soal 1-15)">
                </div>
            </div>

            {{-- Field umum ──────────────────────────────────────── --}}
            <div class="form-group">
                <label class="form-label">
                    Nama Audio <span style="color:var(--red)">*</span>
                </label>
                <input type="text" name="nama" class="form-control" required
                    id="inp-nama"
                    placeholder="cth: TOEFL Full 2026 — Paket A">
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Paket <span style="color:var(--red)">*</span></label>
                <select name="tipe_paket" class="form-control" required>
                    <option value="full">🏆 Tes Full</option>
                    {{-- Simulasi tidak menggunakan Bank Soal --}}
                    {{-- Mini tidak menggunakan Bank Soal --}}
                    {{-- Praktik tidak menggunakan Bank Soal --}}
                </select>
            </div>

            {{-- Drop zone file ──────────────────────────────────── --}}
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
                    <div style="font-size:14px;font-weight:600;margin-bottom:6px">
                        Drag & drop file MP3 ke sini
                    </div>
                    <div style="font-size:12px;color:var(--muted)">
                        atau klik untuk pilih file · MP3/WAV/OGG · Maks 200MB
                    </div>
                    <div id="file-info" style="display:none;margin-top:12px;
                        background:rgba(22,163,74,.1);border:1px solid rgba(22,163,74,.3);
                        border-radius:8px;padding:8px 14px;font-size:13px;color:var(--green)">
                    </div>
                </div>
                <input type="file" id="file-audio" name="audio"
                    accept=".mp3,.wav,.ogg,.m4a" style="display:none"
                    onchange="onFileSelect(this)">
            </div>

            {{-- Preview audio ───────────────────────────────────── --}}
            <div id="audio-preview-wrap" style="display:none;margin-bottom:16px">
                <label class="form-label" style="font-size:12px">Preview Audio</label>
                <audio id="audio-preview" controls style="width:100%;border-radius:8px"></audio>
                <div style="font-size:12px;color:var(--muted);margin-top:5px">
                    <span id="preview-durasi"></span>
                </div>
            </div>

            {{-- Progress upload ─────────────────────────────────── --}}
            <div id="progress-wrap" style="display:none;margin-bottom:16px">
                <div style="font-size:13px;margin-bottom:6px">Mengupload...</div>
                <div style="height:6px;background:var(--border);border-radius:3px">
                    <div id="progress-bar" style="height:6px;background:var(--blue);
                        border-radius:3px;width:0%;transition:width .3s"></div>
                </div>
                <div id="progress-pct" style="font-size:12px;color:var(--muted);margin-top:4px">
                    0%
                </div>
            </div>

            <div style="display:flex;gap:10px">
                <button type="button" onclick="doUpload()" id="btn-upload"
                    class="btn btn-primary" disabled>
                    <i class="fas fa-upload"></i> Upload & Lanjut Tambah Soal
                </button>
                <a href="{{ route('admin.listening.index') }}" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
let audioDurasi = 0;

function pilihTipe(t) {
    ['paket','modul'].forEach(x => {
        const c = document.getElementById('card-'+x);
        c.style.borderColor = x===t ? 'var(--blue)' : 'var(--border)';
        c.style.background  = x===t ? 'rgba(26,86,219,.08)' : 'transparent';
        document.getElementById('tp-'+x).checked = x===t;
    });
    document.getElementById('field-modul').style.display = t==='modul' ? 'block' : 'none';

    // Auto-set nama placeholder
    const nomor = document.querySelector('[name=urutan_modul]')?.value || 1;
    const label = {'1':'A','2':'B','3':'C','4':'D'}[nomor] || nomor;
    document.getElementById('inp-nama').placeholder =
        t==='modul'
            ? `cth: Part ${label} — Short Dialogues`
            : 'cth: TOEFL Full 2026 — Paket A';
}

function onFileSelect(input) {
    const file = input.files[0]; if (!file) return;
    document.getElementById('file-info').textContent =
        '✓ ' + file.name + ' (' + (file.size/1024/1024).toFixed(1) + ' MB)';
    document.getElementById('file-info').style.display = 'block';

    const url = URL.createObjectURL(file);
    const aud = document.getElementById('audio-preview');
    aud.src = url;
    document.getElementById('audio-preview-wrap').style.display = 'block';

    aud.onloadedmetadata = () => {
        audioDurasi = Math.round(aud.duration);
        const m = Math.floor(audioDurasi/60), s = audioDurasi%60;
        document.getElementById('preview-durasi').textContent =
            `Durasi: ${m}:${String(s).padStart(2,'0')} (${audioDurasi} detik)`;
    };

    document.getElementById('btn-upload').disabled = false;
    document.getElementById('drop-ico').style.color = 'var(--green)';
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('drop-zone').style.borderColor = 'var(--border)';
    const file = e.dataTransfer.files[0]; if (!file) return;
    const input = document.getElementById('file-audio');
    const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
    onFileSelect(input);
}

function doUpload() {
    const form = document.getElementById('upload-form');
    const fd   = new FormData(form);
    if (audioDurasi > 0) fd.append('durasi_detik', audioDurasi);

    document.getElementById('progress-wrap').style.display = 'block';
    document.getElementById('btn-upload').disabled = true;
    document.getElementById('btn-upload').innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> Mengupload...';

    const xhr = new XMLHttpRequest();
    xhr.upload.onprogress = e => {
        if (!e.lengthComputable) return;
        const pct = Math.round((e.loaded/e.total)*100);
        document.getElementById('progress-bar').style.width = pct+'%';
        document.getElementById('progress-pct').textContent = pct+'%';
    };
    xhr.onload = () => {
        try {
            const d = JSON.parse(xhr.responseText);
            if (d.ok) { window.location.href = d.redirect; }
            else      { showAlert(d.error || 'Upload gagal.', 'danger'); resetBtn(); }
        } catch(e) {
            // Jika bukan JSON → form redirect biasa
            if (xhr.status < 400) window.location.href = xhr.responseURL;
            else { showAlert('Upload gagal (status '+xhr.status+').','danger'); resetBtn(); }
        }
    };
    xhr.onerror = () => { form.submit(); }; // fallback
    xhr.open('POST', '{{ route("admin.listening.store") }}');
    xhr.send(fd);
}

function resetBtn() {
    document.getElementById('btn-upload').disabled = false;
    document.getElementById('btn-upload').innerHTML =
        '<i class="fas fa-upload"></i> Upload & Lanjut Tambah Soal';
}

function showAlert(msg, type) {
    const el = document.getElementById('alert');
    const c  = type==='danger'
        ? ['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171']
        : ['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80'];
    el.style.cssText = `display:block;background:${c[0]};border:1px solid ${c[1]};
        border-radius:8px;padding:11px 14px;color:${c[2]};font-size:13px`;
    el.textContent = msg;
}
</script>
@endpush
