@extends('layouts.admin')
@section('title','Tambah Soal Baru')
@section('page-title','Bank Soal — Tambah Soal')
@section('breadcrumb','Admin / Bank Soal / Tambah')

@push('styles')
<style>
/* ─ Step indicator ─ */
.steps{display:flex;margin-bottom:28px;gap:0}
.step{flex:1;padding:14px 8px;text-align:center;background:var(--navy-light);
    border:1px solid var(--border);border-right:none;transition:all .2s}
.step:first-child{border-radius:10px 0 0 10px}
.step:last-child{border-right:1px solid var(--border);border-radius:0 10px 10px 0}
.step.active{background:var(--blue);border-color:var(--blue)}
.step.done{background:rgba(22,163,74,.15);border-color:rgba(22,163,74,.3)}
.step-n{font-size:18px;font-weight:900;line-height:1}
.step-l{font-size:11px;opacity:.7;margin-top:2px}

/* ─ Section picker ─ */
.sec-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:6px}
.sec-card{border:2px solid var(--border);border-radius:14px;padding:26px 16px 20px;
    text-align:center;cursor:pointer;transition:all .2s;background:var(--bg)}
.sec-card:hover,.sec-card.on{border-color:var(--blue);background:rgba(26,86,219,.08)}
.sec-card.on{box-shadow:0 0 0 3px rgba(26,86,219,.2)}
.sec-ico{font-size:34px;margin-bottom:10px}
.sec-name{font-size:15px;font-weight:800;margin-bottom:5px}
.sec-desc{font-size:12px;color:var(--muted);line-height:1.6}

/* ─ Type picker ─ */
.type-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:6px}
.type-card{border:1.5px solid var(--border);border-radius:10px;padding:14px 16px;
    cursor:pointer;transition:all .15s;background:var(--bg);
    display:flex;align-items:flex-start;gap:12px}
.type-card:hover,.type-card.on{border-color:var(--green)}
.type-card.on{background:rgba(22,163,74,.06)}
.tc-ico{font-size:22px;flex-shrink:0;line-height:1;margin-top:1px}
.tc-name{font-size:13.5px;font-weight:700;margin-bottom:3px}
.tc-desc{font-size:12px;color:var(--muted);line-height:1.5}

/* ─ Form cards ─ */
.fc{background:var(--navy-light);border:1px solid var(--border);
    border-radius:12px;padding:20px 22px;margin-bottom:14px}
.fc-title{font-size:11.5px;font-weight:700;text-transform:uppercase;
    letter-spacing:1px;color:var(--muted);margin-bottom:16px;
    display:flex;align-items:center;gap:8px}
.fc-title i{color:var(--accent)}
.hint{background:rgba(26,86,219,.07);border:1px solid rgba(26,86,219,.18);
    border-radius:8px;padding:11px 14px;font-size:13px;line-height:1.7;margin-bottom:14px}
.hint strong{color:var(--accent)}

/* ─ Kunci jawaban visual ─ */
.ans-row{display:flex;gap:8px}
.ans-opt{flex:1}
.ans-opt input{display:none}
.ans-opt label{display:flex;align-items:center;justify-content:center;
    padding:10px;border-radius:8px;border:2px solid var(--border);
    cursor:pointer;font-weight:800;font-size:15px;transition:all .15s;width:100%}
.ans-opt input:checked+label{background:var(--green);border-color:var(--green);color:#fff}

/* ─ Halaman sections ─ */
.page{display:none}
.page.show{display:block}

/* ─ Written expression preview ─ */
#we-prev{background:var(--bg);border:1px solid var(--border);border-radius:8px;
    padding:12px;font-size:14px;min-height:36px;margin-top:8px;display:none;
    line-height:1.8}

/* ─ Arrange sentence chips ─ */
.word-chip{display:inline-flex;align-items:center;gap:6px;
    background:rgba(26,86,219,.12);border:1px solid rgba(26,86,219,.3);
    border-radius:6px;padding:4px 10px;margin:3px;font-size:13px;font-weight:600;cursor:grab}
.word-chip .rm{cursor:pointer;color:var(--muted);font-size:12px}
</style>
@endpush

@section('content')

{{-- ══ STEP INDICATOR ══ --}}
<div class="steps" id="steps">
    <div class="step active" id="s1"><div class="step-n">1</div><div class="step-l">Section</div></div>
    <div class="step" id="s2"><div class="step-n">2</div><div class="step-l">Tipe Soal</div></div>
    <div class="step" id="s3"><div class="step-n">3</div><div class="step-l">Input Soal</div></div>
</div>

{{-- ══ PAGE 1: Pilih Section ══ --}}
<div class="page show" id="p1">
    <div class="fc">
        <div class="fc-title"><i class="fas fa-layer-group"></i> Pilih Section</div>
        <div class="sec-grid">
            <div class="sec-card" onclick="pilihSection('reading')">
                <div class="sec-ico">📖</div>
                <div class="sec-name">Reading</div>
                <div class="sec-desc">
                    Academic Passage · Email Reading<br>
                    Fill Missing Letters<br>
                    <small style="color:#93c5fd">Fisher-Yates: urutan passage diacak</small>
                </div>
            </div>
            <div class="sec-card" onclick="pilihSection('listening')">
                <div class="sec-ico">🎧</div>
                <div class="sec-name">Listening</div>
                <div class="sec-desc">
                    1 audio utuh ±35 menit<br>
                    Soal muncul otomatis by timestamp<br>
                    <small style="color:#fb923c">Fisher-Yates: pilihan jawaban diacak</small>
                </div>
            </div>
            <div class="sec-card" onclick="pilihSection('structure')">
                <div class="sec-ico">✏️</div>
                <div class="sec-name">Structure</div>
                <div class="sec-desc">
                    Best Response (Dialogue)<br>
                    Arrange Sentence<br>
                    <small style="color:#fbbf24">Fisher-Yates: urutan soal diacak</small>
                </div>
            </div>
        </div>
        <input type="hidden" id="v-section">

        {{-- Catatan Reading ─ arahkan ke passage system ─ --}}
        <div id="reading-note" style="display:none;margin-top:14px">
            <div class="hint">
                <strong>📖 Reading</strong> dikelola via sistem <strong>Passage</strong>.
                Setiap soal terhubung ke 1 teks bacaan.<br>
                Gunakan menu <strong>Bank Soal → Reading Passages</strong> untuk input soal reading.
            </div>
            <div style="display:flex;gap:10px">
                <a href="{{ route('admin.passage.index') }}" class="btn btn-primary">
                    <i class="fas fa-book-open"></i> Ke Halaman Reading Passages
                </a>
                <a href="{{ route('admin.passage.create') }}" class="btn btn-outline">
                    <i class="fas fa-plus"></i> Buat Passage Baru
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ══ PAGE 2: Pilih Tipe Soal ══ --}}
<div class="page" id="p2">
    <div class="fc">
        <div class="fc-title"><i class="fas fa-shapes"></i>
            Pilih Tipe Soal — <span id="p2-sec-label" style="color:var(--accent)"></span>
        </div>
        <div class="type-grid" id="type-grid">
            {{-- diisi JS --}}
        </div>
        <input type="hidden" id="v-tipe">
    </div>
    <div style="display:flex;gap:10px">
        <button type="button" onclick="goPage(1)" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
    </div>
</div>

{{-- ══ PAGE 3: Form Input Soal (dynamic) ══ --}}
<div class="page" id="p3">

    {{-- Header breadcrumb section > tipe --}}
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px">
        <button onclick="goPage(2)" class="btn btn-outline btn-sm" style="padding:5px 10px">
            <i class="fas fa-arrow-left"></i>
        </button>
        <div>
            <div style="font-size:11px;color:var(--muted)">Input Soal</div>
            <div style="font-size:15px;font-weight:800" id="p3-breadcrumb">—</div>
        </div>
    </div>

    <form action="{{ route('admin.soal.store') }}" method="POST"
        enctype="multipart/form-data" id="main-form">
        @csrf
        <input type="hidden" name="kategori"  id="f-kategori">
        <input type="hidden" name="tipe_soal" id="f-tipe-soal">

        {{-- ── Metadata umum ── --}}
        <div class="fc">
            <div class="fc-title"><i class="fas fa-tag"></i> Metadata</div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
                <div class="form-group" style="margin:0">
                    <label class="form-label">Tipe Paket <span style="color:var(--red)">*</span></label>
                    <select name="tipe_paket" class="form-control" required>
                        @foreach($tipePaket as $v=>$l)
                        <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label">Kesulitan</label>
                    <select name="tingkat_kesulitan" class="form-control">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0" id="meta-nomor">
                    <label class="form-label">No. Soal</label>
                    <input type="number" name="nomor_soal" class="form-control"
                        min="1" placeholder="1">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label">Skill / Materi</label>
                    <input type="text" name="skill_materi" class="form-control"
                        placeholder="cth: Main Idea">
                </div>
            </div>
        </div>

        {{-- ────────────────────────────────────────────────────────
             FORM LISTENING: Best Response
             ──────────────────────────────────────────────────────── --}}
        <div id="form-listening-best_response" class="form-block" style="display:none">
            <div class="hint">
                🎧 <strong>Listening Best Response</strong> — Mahasiswa mendengar audio, lalu pilih respons terbaik.
                Soal ini terhubung ke 1 audio paket penuh. Timestamp menentukan kapan soal muncul.
            </div>
            <div class="fc">
                <div class="fc-title"><i class="fas fa-link"></i> Paket Audio</div>
                <div class="form-group" style="margin:0">
                    <label class="form-label">Pilih Audio Paket <span style="color:var(--red)">*</span></label>
                    <select name="audio_paket_id" class="form-control">
                        <option value="">-- Pilih paket audio --</option>
                        @foreach($audioPaketList as $ap)
                        <option value="{{ $ap->id }}">
                            {{ $ap->nama }} ({{ $ap->soalList->count() }} soal · {{ $ap->durasi_format }})
                        </option>
                        @endforeach
                    </select>
                    <small style="color:var(--muted)">Belum ada paket?
                        <a href="{{ route('admin.listening.create') }}" target="_blank">Upload audio dulu</a>
                    </small>
                </div>
            </div>
            <div class="fc">
                <div class="fc-title"><i class="fas fa-clock"></i> Timeline</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Order / Urutan <span style="color:var(--red)">*</span></label>
                        <input type="number" name="order_number" class="form-control"
                            min="1" max="50" placeholder="1-50">
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Start Second (detik) <span style="color:var(--red)">*</span></label>
                        <input type="number" name="start_second" class="form-control"
                            min="0" placeholder="cth: 80">
                        <small style="color:var(--muted)">Soal muncul di detik ke-?</small>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Part</label>
                        <select name="part" class="form-control">
                            <option value="A">A — Short Dialogues (1-15)</option>
                            <option value="B">B — Longer Conv. (16-30)</option>
                            <option value="C">C — Mini Talks (31-50)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="fc">
                <div class="fc-title"><i class="fas fa-question-circle"></i> Soal</div>
                <div class="form-group">
                    <label class="form-label">Pertanyaan <span style="color:var(--red)">*</span></label>
                    <textarea name="pertanyaan" class="form-control" rows="2" required
                        placeholder="cth: What does the woman suggest the man do?"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar (opsional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Script Audio <small style="color:var(--muted)">(hanya admin)</small></label>
                    <textarea name="audio_script" class="form-control" rows="2"
                        placeholder="Transkrip percakapan..."></textarea>
                </div>
            </div>
            @include('admin.soal.partials.form-pilihan-4', ['label'=>'Pilihan Respons A–D'])
            @include('admin.soal.partials.form-kunci-abcd')
            @include('admin.soal.partials.form-pembahasan')
        </div>

        {{-- ────────────────────────────────────────────────────────
             FORM STRUCTURE: Best Response (Dialogue)
             ──────────────────────────────────────────────────────── --}}
        <div id="form-structure-best_response" class="form-block" style="display:none">
            <div class="hint">
                💬 <strong>Structure Best Response</strong> — Tampilkan dialog singkat + gambar karakter.
                Mahasiswa pilih respons yang paling tepat (A/B/C/D).
            </div>
            <div class="fc">
                <div class="fc-title"><i class="fas fa-comments"></i> Dialog</div>
                <div class="form-group">
                    <label class="form-label">Prompt / Kalimat Dialog <span style="color:var(--red)">*</span></label>
                    <textarea name="pertanyaan" class="form-control" rows="2" required
                        placeholder="cth: What was the highlight of your trip?"></textarea>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Gambar Karakter (opsional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small style="color:var(--muted)">Gambar orang yang berbicara (seperti di gambar contoh)</small>
                </div>
            </div>
            @include('admin.soal.partials.form-pilihan-4', ['label'=>'Pilihan Respons A–D'])
            @include('admin.soal.partials.form-kunci-abcd')
            @include('admin.soal.partials.form-pembahasan')
        </div>

        {{-- ────────────────────────────────────────────────────────
             FORM STRUCTURE: Arrange Sentence
             ──────────────────────────────────────────────────────── --}}
        <div id="form-structure-arrange_sentence" class="form-block" style="display:none">
            <div class="hint">
                🔀 <strong>Arrange Sentence</strong> — Mahasiswa menyusun kata-kata menjadi kalimat yang benar.
                Tampilkan gambar karakter + blanks yang harus diisi.
            </div>
            <div class="fc">
                <div class="fc-title"><i class="fas fa-sort"></i> Konten Soal</div>
                <div class="form-group">
                    <label class="form-label">Konteks / Prompt Dialog <span style="color:var(--red)">*</span></label>
                    <textarea name="pertanyaan" class="form-control" rows="2" required
                        placeholder="cth: What was the highlight of your trip? The ___ ___ ___ ___ ___ fantastic."></textarea>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Gambar (opsional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="fc">
                <div class="fc-title"><i class="fas fa-puzzle-piece"></i> Kata-kata yang Disusun</div>
                <div class="hint">
                    Masukkan kata-kata yang tersedia satu per satu. Mahasiswa akan drag & drop atau klik untuk menyusunnya.
                </div>
                <div class="form-group">
                    <label class="form-label">Tambah Kata</label>
                    <div style="display:flex;gap:8px">
                        <input type="text" id="word-input" class="form-control"
                            placeholder="ketik kata, tekan Enter..."
                            onkeydown="if(event.key==='Enter'){event.preventDefault();addWord()}">
                        <button type="button" onclick="addWord()" class="btn btn-outline btn-sm">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div id="words-chips" style="min-height:40px;padding:6px;
                    background:var(--bg);border:1px solid var(--border);border-radius:8px"></div>
                <input type="hidden" name="arrange_words" id="arrange-words-val">
                <small style="color:var(--muted);font-size:11.5px">
                    Kata-kata akan diacak urutannya saat ditampilkan ke mahasiswa
                </small>
            </div>
            <div class="fc">
                <div class="fc-title"><i class="fas fa-key"></i> Kalimat Jawaban Benar</div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Urutan kata yang benar <span style="color:var(--red)">*</span></label>
                    <input type="text" name="jawaban_benar" class="form-control" required
                        placeholder="cth: tour guides who showed us around the old city were fantastic">
                    <small style="color:var(--muted)">Tulis kalimat lengkap yang benar</small>
                    <input type="hidden" name="pilihan_a" value="-">
                    <input type="hidden" name="pilihan_b" value="-">
                    <input type="hidden" name="pilihan_c" value="-">
                    <input type="hidden" name="pilihan_d" value="-">
                </div>
            </div>
            @include('admin.soal.partials.form-pembahasan')
        </div>

        {{-- ── Tombol simpan ── --}}
        <div style="display:flex;gap:10px;margin-top:6px" id="submit-wrap" style="display:none">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Soal
            </button>
            <button type="button" onclick="goPage(2)" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
// ── Data tipe soal dari PHP ──
const TIPE_PER_SECTION = @json($tipeSoal);

const SEC_META = {
    reading:   { label: '📖 Reading',   icon: '📖' },
    listening: { label: '🎧 Listening', icon: '🎧' },
    structure: { label: '✏️ Structure', icon: '✏️' },
};

let curSection = null;
let curTipe    = null;

// ── Navigasi halaman ──
function goPage(n) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('show'));
    document.getElementById('p' + n).classList.add('show');
    [1,2,3].forEach(i => {
        const s = document.getElementById('s' + i);
        s.className = 'step' + (i < n ? ' done' : i === n ? ' active' : '');
    });
    window.scrollTo({top:0, behavior:'smooth'});
}

// ── Step 1: Pilih section ──
function pilihSection(sec) {
    curSection = sec;
    document.getElementById('v-section').value  = sec;
    document.getElementById('f-kategori').value = sec;
    document.querySelectorAll('.sec-card').forEach(c => c.classList.remove('on'));
    event.currentTarget.classList.add('on');

    const note = document.getElementById('reading-note');

    if (sec === 'reading') {
        note.style.display = 'block';
        return; // Reading pakai sistem passage terpisah
    }
    note.style.display = 'none';

    // Render tipe soal untuk section ini
    renderTypeCards(sec);
    setTimeout(() => goPage(2), 200);
}

function renderTypeCards(sec) {
    const container = document.getElementById('type-grid');
    const label     = document.getElementById('p2-sec-label');
    label.textContent = SEC_META[sec]?.label || sec;
    container.innerHTML = '';

    const types = TIPE_PER_SECTION[sec] || {};
    Object.entries(types).forEach(([key, lbl]) => {
        const [ico, ...rest] = lbl.split(' ');
        const name = rest.join(' ');
        const desc = getTipeDesc(key);
        container.innerHTML += `
        <div class="type-card" id="tc-${key}" onclick="pilihTipe('${key}')">
            <div class="tc-ico">${ico}</div>
            <div>
                <div class="tc-name">${name}</div>
                <div class="tc-desc">${desc}</div>
            </div>
        </div>`;
    });
}

function getTipeDesc(key) {
    const d = {
        'best_response':     'Pilih respons terbaik dari 4 pilihan',
        'arrange_sentence':  'Susun kata-kata menjadi kalimat yang benar',
        'multiple_choice':   'Pilih 1 jawaban dari 4 pilihan',
        'vocabulary':        'Kata di-highlight → pilih sinonim/makna',
        'insert_sentence':   'Pilih posisi kalimat yang tepat di teks',
        'click_sentence':    'Klik kalimat yang paling tepat di teks',
        'prose_summary':     'Pilih 3 dari 6 pernyataan yang benar',
        'fill_missing_letters': 'Isi huruf/kata yang hilang di paragraf',
        'email_reading':     'Baca email → jawab pertanyaan',
        'academic_passage':  'Baca teks akademik → jawab pertanyaan',
    };
    return d[key] || '—';
}

// ── Step 2: Pilih tipe ──
function pilihTipe(tipe) {
    curTipe = tipe;
    document.getElementById('v-tipe').value      = tipe;
    document.getElementById('f-tipe-soal').value = tipe;
    document.querySelectorAll('.type-card').forEach(c => c.classList.remove('on'));
    document.getElementById('tc-' + tipe)?.classList.add('on');

    // Sembunyikan semua form block
    document.querySelectorAll('.form-block').forEach(f => f.style.display = 'none');

    // Tampilkan form sesuai section + tipe
    const formId = `form-${curSection}-${tipe}`;
    const formEl = document.getElementById(formId);
    if (formEl) {
        formEl.style.display = 'block';
    } else {
        // Fallback: generic form
        document.getElementById('form-generic')?.style.setProperty('display','block');
    }

    // Update breadcrumb
    const secLabel  = SEC_META[curSection]?.label || curSection;
    const tipeLabel = document.getElementById('tc-' + tipe)?.querySelector('.tc-name')?.textContent || tipe;
    document.getElementById('p3-breadcrumb').textContent = secLabel + ' → ' + tipeLabel;

    // Tampilkan submit button
    document.getElementById('submit-wrap').style.display = 'flex';

    setTimeout(() => goPage(3), 200);
}

// ── Arrange sentence: tambah kata ──
let wordsList = [];
function addWord() {
    const inp = document.getElementById('word-input');
    const w = inp.value.trim();
    if (!w) return;
    wordsList.push(w);
    inp.value = '';
    renderChips();
    inp.focus();
}
function removeWord(i) {
    wordsList.splice(i, 1);
    renderChips();
}
function renderChips() {
    const container = document.getElementById('words-chips');
    container.innerHTML = wordsList.map((w,i) =>
        `<span class="word-chip" draggable="true">
            ${w} <span class="rm" onclick="removeWord(${i})">×</span>
        </span>`
    ).join('');
    document.getElementById('arrange-words-val').value = JSON.stringify(wordsList);
}

// ── Written Expression preview (jika ada) ──
function previewWE(val) {
    const el = document.getElementById('we-prev');
    if (!el) return;
    if (!val.trim()) { el.style.display = 'none'; return; }
    let html = val.replace(/\[([^\]]+)\]\(([A-D])\)/g,
        '<u style="color:#93c5fd">$1</u>(<strong style="color:#fbbf24">$2</strong>)');
    el.innerHTML = html;
    el.style.display = 'block';
}
</script>
@endpush
