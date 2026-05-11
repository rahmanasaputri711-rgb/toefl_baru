@extends('layouts.admin')
@section('title','Kelola Soal — '.$passage->judul)
@section('page-title','Kelola Soal Reading')
@section('breadcrumb','Admin / Reading / '.$passage->judul)

@push('styles')
<style>
.tipe-tab{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}
.tipe-tab button{padding:8px 16px;border-radius:8px;border:1.5px solid var(--border);
    background:var(--navy-light);color:var(--muted);cursor:pointer;font-family:inherit;
    font-size:13px;font-weight:600;transition:all .15s}
.tipe-tab button.active{background:var(--blue);color:#fff;border-color:var(--blue)}
.form-soal{display:none}
.form-soal.active{display:block}
.soal-row{display:flex;align-items:center;justify-content:space-between;gap:12px;
    padding:12px 16px;border-bottom:1px solid var(--border);transition:background .15s}
.soal-row:hover{background:rgba(255,255,255,.03)}
.badge-tipe{padding:3px 9px;border-radius:6px;font-size:11px;font-weight:700}
</style>
@endpush

@section('content')

{{-- Info passage --}}
<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="padding:18px 20px">
        {{-- ════ FORM: Fill Missing Letters ════ --}}
            <form action="{{ route('admin.passage.storeSoal', $passage->id) }}" method="POST"
                class="form-soal" id="form-fill_missing_letters">
                @csrf
                <input type="hidden" name="tipe_soal" value="fill_missing_letters">
                <input type="hidden" name="pilihan_a" value="-">
                <input type="hidden" name="pilihan_b" value="-">
                <input type="hidden" name="pilihan_c" value="-">
                <input type="hidden" name="pilihan_d" value="-">

                {{-- Metadata simpel untuk Fill Missing Letters --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Kesulitan <span style="color:var(--red)">*</span></label>
                        <select name="tingkat_kesulitan" class="form-control" required>
                            <option value="easy">Easy</option>
                            <option value="medium" selected>Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Skill / Materi</label>
                        <input type="text" name="skill_materi" class="form-control"
                            placeholder="cth: Vocabulary, Reading Comprehension">
                    </div>
                </div>
                {{-- nomor_soal default 1 karena 1 teks = 1 soal --}}
                <input type="hidden" name="nomor_soal" value="{{ $nomorBerikut ?? 1 }}">

                {{-- Panduan ─────────────────────────────── --}}
                <div style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);
                    border-radius:10px;padding:14px 16px;margin-bottom:16px;font-size:13px;line-height:1.9">
                    <strong style="color:#34d399;display:block;margin-bottom:6px">
                        🔤 Konsep: 1 Teks = 1 Soal dengan Banyak Blank
                    </strong>
                    <div style="color:rgba(255,255,255,.7)">
                        Tulis <strong style="color:#fff">1 teks lengkap</strong>, tandai blank dengan kurung siku.
                        Seluruh teks ini tersimpan sebagai <strong style="color:#fff">1 soal</strong>.<br><br>
                        <code style="background:#1e293b;padding:4px 10px;border-radius:4px;
                            color:#34d399;display:block;margin:6px 0;font-size:12.5px">
                            Built b[y] tiny ani[mals] called coral polyps, th[ese] reefs gr[ow]...
                        </code>
                        Setiap <code style="color:#fbbf24">[...]</code> = 1 blank yang harus diisi user.<br>
                        Sistem otomatis menyimpan jawaban dari isi <code style="color:#fbbf24">[...]</code>.<br>
                        Jika ada <strong style="color:#fff">10 blank</strong>, user mengisi 10 kotak dalam 1 layar.
                    </div>
                </div>

                {{-- Textarea teks dengan tag blank ──────── --}}
                <div class="form-group">
                    <label class="form-label">
                        Teks dengan Tag Blank <span style="color:var(--red)">*</span>
                        <small style="color:var(--muted)">— gunakan [huruf/kata] untuk bagian yang disembunyikan</small>
                    </label>
                    <textarea name="fill_text" id="fm-input" class="form-control"
                        rows="10" required style="resize:vertical;min-height:200px" oninput="previewFM(this.value)"
                        placeholder="Coral reefs are one of the most diverse marine ecosystems on Earth. Built b[y] tiny ani[mals] called coral polyps, th[ese] reefs gr[ow] slowly ov[er] time b[y] forming ha[rd] calcium carbonate skele[tons]...">{{ old('fill_text') }}</textarea>
                </div>

                {{-- Preview real-time ──────────────────── --}}
                <div style="margin-bottom:14px">
                    <label class="form-label" style="display:flex;align-items:center;gap:8px">
                        Preview Tampilan User
                        <span id="fm-blank-count"
                            style="background:rgba(16,185,129,.15);color:#34d399;
                            padding:2px 10px;border-radius:20px;font-size:11px;font-weight:700">
                            0 blank
                        </span>
                    </label>
                    <div id="fm-preview" style="background:var(--bg);border:1px solid var(--border);
                        border-radius:10px;padding:16px;min-height:60px;font-size:14px;
                        line-height:2;color:#e2e8f0;display:none">
                    </div>
                    <div id="fm-preview-empty"
                        style="background:var(--bg);border:1px dashed var(--border);
                        border-radius:10px;padding:16px;text-align:center;
                        font-size:13px;color:var(--muted)">
                        Preview akan muncul saat kamu menulis teks di atas
                    </div>
                </div>

                {{-- Jawaban otomatis (hidden, diekstrak dari tag) ─ --}}
                <div style="background:rgba(0,0,0,.2);border-radius:8px;padding:12px 14px;
                    margin-bottom:14px;font-size:12.5px">
                    <strong style="color:var(--muted)">📋 Jawaban yang akan disimpan:</strong>
                    <div id="fm-answers"
                        style="color:#34d399;font-family:monospace;margin-top:5px;font-size:13px">
                        —
                    </div>
                </div>

                @include('admin.passage.partials.form-pembahasan')
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal Fill Missing Letters
                </button>
            </form>

        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
            <div>
                <div style="font-size:18px;font-weight:800;margin-bottom:6px">
                    {{ $passage->judul }}
                </div>
                <div style="font-size:13px;color:var(--muted);line-height:1.6;max-width:700px">
                    {{ mb_strimwidth(strip_tags($passage->teks), 0, 200, '...') }}
                </div>
                <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
                    <span style="background:rgba(26,86,219,.15);color:var(--accent);
                        padding:3px 10px;border-radius:6px;font-size:12px;font-weight:600">
                        {{ strtoupper($passage->tipe_paket) }}
                    </span>
                    <span style="background:rgba(22,163,74,.12);color:var(--green);
                        padding:3px 10px;border-radius:6px;font-size:12px">
                        {{ $passage->soal->count() }} soal
                    </span>
                </div>
            </div>
            <div style="display:flex;gap:8px">
                <a href="{{ route('admin.passage.edit', $passage->id) }}"
                    class="btn btn-outline btn-sm">
                    <i class="fas fa-edit"></i> Edit Teks
                </a>
                <a href="{{ route('admin.passage.index') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 360px;gap:18px;align-items:start">

    {{-- ── KIRI: Form tambah soal ── --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-plus-circle" style="color:var(--green);margin-right:8px"></i>
                Tambah Soal Baru
            </h3>
            <span style="font-size:12px;color:var(--muted)">No. {{ $nomorBerikut }}</span>
        </div>
        <div class="card-body" style="padding:20px">

            {{-- Tab pilih tipe soal --}}
            <div style="margin-bottom:16px">
                <label class="form-label">Pilih Tipe Soal</label>
                <div class="tipe-tab">
                    <button type="button" onclick="pilihTipe('multiple_choice')" class="active" id="tab-mc">
                        🔵 Multiple Choice
                    </button>
                    <button type="button" onclick="pilihTipe('vocabulary')" id="tab-vo">
                        🟡 Vocabulary
                    </button>
                    <button type="button" onclick="pilihTipe('insert_sentence')" id="tab-is">
                        🟢 Insert Sentence
                    </button>
                    <button type="button" onclick="pilihTipe('click_sentence')" id="tab-cs">
                        🟣 Click Sentence
                    </button>
                    <button type="button" onclick="pilihTipe('prose_summary')" id="tab-ps">
                        🔴 Prose Summary
                    </button>
                    <button type="button" onclick="pilihTipe('fill_missing_letters')" id="tab-fm">
                        🔤 Fill Missing Letters
                    </button>
                </div>
            </div>

            {{-- ════ FORM: Multiple Choice ════ --}}
            <form action="{{ route('admin.passage.storeSoal', $passage->id) }}" method="POST"
                class="form-soal active" id="form-multiple_choice">
                @csrf
                <input type="hidden" name="tipe_soal" value="multiple_choice">
                @include('admin.passage.partials.form-common', ['tipe'=>'mc'])
                @include('admin.passage.partials.form-pilihan-abcd')
                <div class="form-group">
                    <label class="form-label">Jawaban Benar <span style="color:var(--red)">*</span></label>
                    <select name="jawaban_benar" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="a">A</option><option value="b">B</option>
                        <option value="c">C</option><option value="d">D</option>
                    </select>
                </div>
                @include('admin.passage.partials.form-pembahasan')
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal
                </button>
            </form>

            {{-- ════ FORM: Vocabulary in Context ════ --}}
            <form action="{{ route('admin.passage.storeSoal', $passage->id) }}" method="POST"
                class="form-soal" id="form-vocabulary">
                @csrf
                <input type="hidden" name="tipe_soal" value="vocabulary">
                @include('admin.passage.partials.form-common', ['tipe'=>'vo'])

                <div style="background:rgba(217,119,6,.08);border:1px solid rgba(217,119,6,.25);
                    border-radius:8px;padding:12px;margin-bottom:14px;font-size:13px;line-height:1.7">
                    <strong style="color:#fbbf24">📌 Cara Input Vocabulary:</strong><br>
                    Masukkan kata yang di-highlight di teks (contoh: <em>pervasive</em>).
                    Pertanyaan otomatis: "The word '[kata]' is closest in meaning to..."
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label class="form-label">Kata yang Di-highlight <span style="color:var(--red)">*</span></label>
                        <input type="text" name="highlight_kata" class="form-control"
                            placeholder="cth: pervasive" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Di Paragraf ke- <span style="color:var(--red)">*</span></label>
                        <input type="number" name="highlight_paragraf" class="form-control"
                            min="1" placeholder="1" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pertanyaan <small style="color:var(--muted)">(opsional, auto-generate jika kosong)</small></label>
                    <input type="text" name="pertanyaan" class="form-control"
                        placeholder='The word "[highlight_kata]" in the passage is closest in meaning to'>
                </div>

                @include('admin.passage.partials.form-pilihan-abcd', ['labelA'=>'Pilihan A (jawaban yang mirip maknanya)'])
                <div class="form-group">
                    <label class="form-label">Jawaban Benar <span style="color:var(--red)">*</span></label>
                    <select name="jawaban_benar" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="a">A</option><option value="b">B</option>
                        <option value="c">C</option><option value="d">D</option>
                    </select>
                </div>
                @include('admin.passage.partials.form-pembahasan')
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal
                </button>
            </form>

            {{-- ════ FORM: Insert a Sentence ════ --}}
            <form action="{{ route('admin.passage.storeSoal', $passage->id) }}" method="POST"
                class="form-soal" id="form-insert_sentence">
                @csrf
                <input type="hidden" name="tipe_soal" value="insert_sentence">
                @include('admin.passage.partials.form-common', ['tipe'=>'is'])

                <div style="background:rgba(22,163,74,.08);border:1px solid rgba(22,163,74,.2);
                    border-radius:8px;padding:12px;margin-bottom:14px;font-size:13px;line-height:1.7">
                    <strong style="color:#4ade80">📌 Cara Input Insert Sentence:</strong><br>
                    Tulis kalimat yang perlu disisipkan. Di tampilan mahasiswa, teks passage akan
                    menampilkan <strong>tanda ■</strong> di 4 posisi (A/B/C/D) untuk dipilih.
                </div>

                <div class="form-group">
                    <label class="form-label">Kalimat yang Disisipkan <span style="color:var(--red)">*</span></label>
                    <textarea name="insert_sentence_teks" class="form-control" rows="3" required
                        placeholder="Look at the four squares [■] that indicate where the following sentence could be added to the passage.

Tulis kalimat yang akan disisipkan di sini..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Pertanyaan <span style="color:var(--red)">*</span></label>
                    <input type="text" name="pertanyaan" class="form-control" required
                        value="Where would the sentence best fit?">
                </div>

                <div style="background:rgba(0,0,0,.2);border-radius:8px;padding:12px;margin-bottom:14px;font-size:13px">
                    <strong>Posisi di teks:</strong> Tandai 4 posisi di teks passage (edit teks passage)
                    dengan <code>[■A]</code>, <code>[■B]</code>, <code>[■C]</code>, <code>[■D]</code>
                    agar mahasiswa tahu di mana posisi pilihan.
                </div>

                <div class="form-group">
                    <label class="form-label">Jawaban Benar (posisi yang tepat)</label>
                    <select name="jawaban_benar" class="form-control" required>
                        <option value="a">A — Posisi pertama</option>
                        <option value="b">B — Posisi kedua</option>
                        <option value="c">C — Posisi ketiga</option>
                        <option value="d">D — Posisi keempat</option>
                    </select>
                </div>
                @include('admin.passage.partials.form-pembahasan')
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal
                </button>
            </form>

            {{-- ════ FORM: Click on a Sentence ════ --}}
            <form action="{{ route('admin.passage.storeSoal', $passage->id) }}" method="POST"
                class="form-soal" id="form-click_sentence">
                @csrf
                <input type="hidden" name="tipe_soal" value="click_sentence">
                @include('admin.passage.partials.form-common', ['tipe'=>'cs'])

                <div style="background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.2);
                    border-radius:8px;padding:12px;margin-bottom:14px;font-size:13px;line-height:1.7">
                    <strong style="color:#a78bfa">📌 Cara Input Click Sentence:</strong><br>
                    Mahasiswa akan klik langsung kalimat di teks passage.
                    Tulis kalimat yang benar persis sama dengan yang ada di teks.
                </div>

                <div class="form-group">
                    <label class="form-label">Pertanyaan <span style="color:var(--red)">*</span></label>
                    <textarea name="pertanyaan" class="form-control" rows="2" required
                        placeholder="cth: Click on the sentence in paragraph 2 that explains how animals produce bioluminescence with the help of other living organisms."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Kalimat yang Benar (harus persis sama dengan di teks) <span style="color:var(--red)">*</span></label>
                    <textarea name="jawaban_benar" class="form-control" rows="3" required
                        placeholder="Salin kalimat yang benar dari teks passage di atas..."></textarea>
                    <small style="color:var(--muted)">Sistem akan match kalimat ini dengan yang diklik mahasiswa</small>
                </div>

                {{-- Tidak perlu pilihan A/B/C/D untuk click_sentence --}}
                <input type="hidden" name="pilihan_a" value="-">
                <input type="hidden" name="pilihan_b" value="-">
                <input type="hidden" name="pilihan_c" value="-">
                <input type="hidden" name="pilihan_d" value="-">

                @include('admin.passage.partials.form-pembahasan')
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal
                </button>
            </form>

            {{-- ════ FORM: Prose Summary ════ --}}
            <form action="{{ route('admin.passage.storeSoal', $passage->id) }}" method="POST"
                class="form-soal" id="form-prose_summary">
                @csrf
                <input type="hidden" name="tipe_soal" value="prose_summary">
                @include('admin.passage.partials.form-common', ['tipe'=>'ps'])

                <div style="background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.2);
                    border-radius:8px;padding:12px;margin-bottom:14px;font-size:13px;line-height:1.7">
                    <strong style="color:#f87171">📌 Cara Input Prose Summary:</strong><br>
                    Ada 6 pilihan (A–F). Mahasiswa pilih <strong>3 yang benar</strong>.
                    Biasanya soal terakhir per passage.
                </div>

                <div class="form-group">
                    <label class="form-label">Pertanyaan / Intro kalimat <span style="color:var(--red)">*</span></label>
                    <textarea name="pertanyaan" class="form-control" rows="2" required
                        placeholder="An introductory sentence for a brief summary of the passage is provided below. Complete the summary by selecting the THREE answer choices that express the most important ideas in the passage..."></textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                    @foreach(['a','b','c','d','e','f'] as $opt)
                    <div class="form-group">
                        <label class="form-label">Pilihan {{ strtoupper($opt) }} <span style="color:var(--red)">*</span></label>
                        <textarea name="pilihan_{{ $opt }}" class="form-control" rows="2"
                            placeholder="Pernyataan {{ strtoupper($opt) }}..." required></textarea>
                    </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <label class="form-label">Jawaban Benar (3 huruf, dipisah koma) <span style="color:var(--red)">*</span></label>
                    <input type="text" name="jawaban_benar_multiple" class="form-control"
                        placeholder="cth: a,c,e" required pattern="[a-f],[a-f],[a-f]">
                    <input type="hidden" name="jawaban_benar" value="multiple">
                    <small style="color:var(--muted)">Format: a,c,e (3 huruf dipisah koma tanpa spasi)</small>
                </div>

                @include('admin.passage.partials.form-pembahasan')
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal
                </button>
            </form>

        </div>
    </div>

    {{-- ── KANAN: Daftar soal yang sudah ada ── --}}
    <div style="position:sticky;top:20px">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list" style="color:var(--accent);margin-right:8px"></i>
                    Soal dalam Passage
                </h3>
                <span style="font-size:12.5px;color:var(--muted)">
                    {{ $passage->soal->count() }} / 10 soal
                </span>
            </div>

            @forelse($passage->soal as $s)
            @php
                $tipeMeta = [
                    'multiple_choice' => ['#dbeafe','#1d4ed8','MC','Multiple Choice'],
                    'vocabulary'      => ['#fef3c7','#d97706','VO','Vocabulary'],
                    'insert_sentence' => ['#dcfce7','#16a34a','IS','Insert Sentence'],
                    'click_sentence'  => ['#f3e8ff','#7c3aed','CS','Click Sentence'],
                    'prose_summary'   => ['#fee2e2','#dc2626','PS','Prose Summary'],
                ][$s->tipe_soal ?? 'multiple_choice'];
            @endphp
            <div class="soal-row">
                <div style="min-width:0;flex:1">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px">
                        <span style="font-weight:800;color:var(--accent);font-size:14px;min-width:24px">
                            {{ $s->nomor_soal }}.
                        </span>
                        <span class="badge-tipe"
                            style="background:{{ $tipeMeta[0] }};color:{{ $tipeMeta[1] }}">
                            {{ $tipeMeta[2] }}
                        </span>
                        <span style="font-size:11px;color:var(--muted)">
                            {{ ucfirst($s->tingkat_kesulitan) }}
                        </span>
                    </div>
                    <div style="font-size:12.5px;color:rgba(255,255,255,.65);
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                        max-width:220px;padding-left:32px">
                        @if($s->tipe_soal === 'vocabulary')
                            Kata: <em style="color:#fbbf24">{{ $s->highlight_kata }}</em>
                        @elseif($s->tipe_soal === 'click_sentence')
                            Klik kalimat di paragraf
                        @else
                            {{ mb_strimwidth($s->pertanyaan, 0, 60, '...') }}
                        @endif
                    </div>
                </div>
                <div style="display:flex;gap:5px;flex-shrink:0">
                    <form action="{{ route('admin.passage.destroySoal', $s->id) }}"
                        method="POST" onsubmit="return confirm('Hapus soal No.{{ $s->nomor_soal }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            style="padding:4px 8px;font-size:11px">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div style="padding:24px;text-align:center;color:var(--muted);font-size:13px">
                <i class="fas fa-plus-circle" style="display:block;font-size:24px;margin-bottom:8px"></i>
                Belum ada soal. Tambah soal pertama di form sebelah kiri.
            </div>
            @endforelse

            @if($passage->soal->count() >= 10)
            <div style="padding:12px 16px;background:rgba(22,163,74,.08);
                border-top:1px solid var(--border);font-size:13px;color:var(--green);text-align:center">
                <i class="fas fa-check-circle"></i> Passage lengkap (10 soal)
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

            
@push('scripts')
<script>
function pilihTipe(tipe) {
    // Sembunyikan semua form
    document.querySelectorAll('.form-soal').forEach(f => f.classList.remove('active'));
    // Nonaktifkan semua tab
    document.querySelectorAll('.tipe-tab button').forEach(b => b.classList.remove('active'));
    // Tampilkan form yang dipilih
    const form = document.getElementById('form-' + tipe);
    const tab  = document.getElementById('tab-' + {
        'multiple_choice':'mc','vocabulary':'vo',
        'insert_sentence':'is','click_sentence':'cs',
        'prose_summary':'ps','fill_missing_letters':'fm'
    }[tipe]);
    if (form) form.classList.add('active');
    if (tab)  tab.classList.add('active');
}

// ── Fill Missing Letters Preview ──────────────────────────────
function previewFM(rawVal) {
    const preview   = document.getElementById('fm-preview');
    const emptyEl   = document.getElementById('fm-preview-empty');
    const countEl   = document.getElementById('fm-blank-count');
    const answersEl = document.getElementById('fm-answers');
    if (!preview) return;

    const val = rawVal || '';

    if (!val.trim()) {
        preview.style.display = 'none';
        emptyEl.style.display = 'block';
        countEl.textContent   = '0 blank';
        answersEl.textContent = '—';
        return;
    }

    // Ekstrak semua jawaban dari [...] — harus dilakukan SEBELUM escape
    const answers = [];
    const ansRegex = /\[([^\]]+)\]/g;
    let m;
    while ((m = ansRegex.exec(val)) !== null) answers.push(m[1]);

    // Escape HTML pada teks biasa (bukan pada [...])
    // Pisah per token: teks biasa vs [blank]
    const parts = val.split(/(\[[^\]]+\])/);
    let html = '';
    parts.forEach(part => {
        if (/^\[([^\]]+)\]$/.test(part)) {
            // Ini blank
            const ans  = part.slice(1, -1);
            const len  = ans.length;
            const w    = Math.max(28, len * 11);
            html += `<input type="text"
                style="display:inline-block;width:${w}px;border:none;
                border-bottom:2px solid #34d399;background:rgba(52,211,153,.1);
                border-radius:3px 3px 0 0;padding:1px 4px;text-align:center;
                font-size:13px;color:#34d399;font-weight:600;outline:none;
                vertical-align:baseline;" placeholder="${'_'.repeat(len)}" disabled>`;
        } else {
            // Teks biasa — escape HTML
            html += part
                .replace(/&/g,'&amp;')
                .replace(/</g,'&lt;')
                .replace(/>/g,'&gt;')
                .replace(/\n/g,'<br>');
        }
    });

    preview.innerHTML     = html;
    preview.style.display = 'block';
    emptyEl.style.display = 'none';
    countEl.textContent   = answers.length + ' blank';
    answersEl.innerHTML   = answers.length
        ? answers.map((a,i) =>
            `<span style="background:rgba(52,211,153,.12);color:#34d399;
            padding:2px 8px;border-radius:4px;font-family:monospace;font-size:12px;margin:2px">
            blank ${i+1}: "${a}"</span>`
          ).join(' ')
        : '—';
}
</script>
@endpush