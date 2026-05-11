@extends('layouts.admin')
@section('title','Tambah Modul')
@section('page-title','Tambah Modul Reading')
@section('breadcrumb','Admin / Reading Builder / Tambah Modul')

@section('content')
<div style="max-width:640px">
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus" style="color:var(--accent);margin-right:8px"></i>
            Tambah Modul — {{ $paket->nama }}
        </h3>
        <a href="{{ route('admin.reading-builder.paket', $paket->id) }}"
            class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body" style="padding:24px">

        @if(session('error'))
        <div style="background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.3);
            border-radius:8px;padding:12px;color:var(--red);margin-bottom:16px">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Nomor yang sudah dipakai --}}
        @if($soalTerpakai->count())
        <div style="background:rgba(26,86,219,.07);border:1px solid rgba(26,86,219,.2);
            border-radius:8px;padding:12px 14px;margin-bottom:18px;font-size:13px">
            <strong style="color:var(--accent)">Nomor soal yang sudah dipakai:</strong>
            <div style="margin-top:6px;display:flex;gap:6px;flex-wrap:wrap">
                @foreach($soalTerpakai as $m)
                <span style="background:rgba(26,86,219,.15);color:var(--accent);
                    padding:2px 10px;border-radius:6px;font-size:12px;font-weight:700">
                    {{ $m->nomor_soal_mulai }}–{{ $m->nomor_soal_selesai }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('admin.reading-builder.modul.store', $paket->id) }}"
            method="POST">
            @csrf

            {{-- Pilih tipe modul ──────────────────── --}}
            <div class="form-group">
                <label class="form-label">Tipe Modul <span style="color:var(--red)">*</span></label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
                    @foreach(\App\Models\ModulSoal::TIPE as $key => $meta)
                    <label style="border:2px solid var(--border);border-radius:10px;
                        padding:14px 12px;text-align:center;cursor:pointer;
                        transition:all .15s" id="card-{{ $key }}"
                        onclick="pilihTipe('{{ $key }}')">
                        <input type="radio" name="tipe_modul" value="{{ $key }}"
                            style="display:none" id="tipe-{{ $key }}"
                            {{ old('tipe_modul')===$key ? 'checked' : '' }}>
                        <div style="font-size:26px;margin-bottom:8px">
                            {{ $key==='passage' ? '📄' : ($key==='missing_letters' ? '🔤' : '📧') }}
                        </div>
                        <div style="font-size:12.5px;font-weight:700;line-height:1.4">
                            {{ str_replace(['📄 ','🔤 ','📧 '], '', $meta['label']) }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Judul opsional --}}
            <div class="form-group">
                <label class="form-label">Judul Modul
                    <small style="color:var(--muted)">(opsional, untuk label admin)</small>
                </label>
                <input type="text" name="judul" class="form-control"
                    placeholder="cth: Passage 1 — Coral Reefs"
                    value="{{ old('judul') }}">
            </div>

            {{-- Rentang nomor soal --}}
            <div class="form-group">
                <label class="form-label">
                    Rentang Nomor Soal <span style="color:var(--red)">*</span>
                </label>
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="flex:1">
                        <label style="font-size:11.5px;color:var(--muted);margin-bottom:4px;display:block">Dari No.</label>
                        <input type="number" name="nomor_soal_mulai" class="form-control"
                            min="1" max="50" required
                            value="{{ old('nomor_soal_mulai', $nomorBerikut) }}"
                            oninput="updateInfo()">
                    </div>
                    <div style="padding-top:20px;color:var(--muted);font-size:18px">—</div>
                    <div style="flex:1">
                        <label style="font-size:11.5px;color:var(--muted);margin-bottom:4px;display:block">Sampai No.</label>
                        <input type="number" name="nomor_soal_selesai" class="form-control"
                            min="1" max="50" required
                            value="{{ old('nomor_soal_selesai', $nomorBerikut) }}"
                            oninput="updateInfo()">
                    </div>
                </div>
                <div id="rentang-info" style="font-size:12.5px;color:var(--muted);margin-top:8px"></div>
            </div>

            {{-- Info kontekstual berdasarkan tipe --}}
            <div id="info-passage" class="tipe-info" style="display:none;
                background:rgba(59,130,246,.08);border:1px solid rgba(59,130,246,.2);
                border-radius:8px;padding:12px;font-size:13px;line-height:1.7;margin-bottom:14px">
                📄 <strong>Academic Passage</strong> — Kamu akan input teks bacaan, lalu tambahkan soal
                satu per satu (soal no.mulai s/d no.selesai).
            </div>
            <div id="info-missing_letters" class="tipe-info" style="display:none;
                background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);
                border-radius:8px;padding:12px;font-size:13px;line-height:1.7;margin-bottom:14px">
                🔤 <strong>Missing Letters</strong> — Kamu akan input teks dengan format <code>[blank]</code>.
                Jumlah <code>[...]</code> harus sama dengan jumlah soal di rentang ini.
                <div id="info-blank-count" style="margin-top:4px;color:#34d399;font-weight:700"></div>
            </div>
            <div id="info-image_email" class="tipe-info" style="display:none;
                background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);
                border-radius:8px;padding:12px;font-size:13px;line-height:1.7;margin-bottom:14px">
                📧 <strong>Gambar / Email</strong> — Upload 1 gambar/screenshot, lalu tambahkan soal
                pilihan ganda satu per satu.
            </div>

            <div style="display:flex;gap:10px;margin-top:6px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Buat Modul & Input Soal
                </button>
                <a href="{{ route('admin.reading-builder.paket', $paket->id) }}"
                    class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function pilihTipe(key) {
    document.querySelectorAll('[id^="card-"]').forEach(c => {
        c.style.borderColor = 'var(--border)';
        c.style.background  = 'transparent';
    });
    const card = document.getElementById('card-' + key);
    card.style.borderColor = 'var(--blue)';
    card.style.background  = 'rgba(26,86,219,.08)';
    document.getElementById('tipe-' + key).checked = true;

    document.querySelectorAll('.tipe-info').forEach(el => el.style.display = 'none');
    const info = document.getElementById('info-' + key);
    if (info) info.style.display = 'block';
    updateInfo();
}

function updateInfo() {
    const mulai   = parseInt(document.querySelector('[name=nomor_soal_mulai]')?.value) || 0;
    const selesai = parseInt(document.querySelector('[name=nomor_soal_selesai]')?.value) || 0;
    const jumlah  = selesai >= mulai ? selesai - mulai + 1 : 0;
    const info    = document.getElementById('rentang-info');
    if (info) info.textContent = jumlah > 0
        ? `Modul ini akan menampung ${jumlah} soal (No.${mulai}–${selesai})`
        : '';

    const blankInfo = document.getElementById('info-blank-count');
    if (blankInfo && jumlah > 0)
        blankInfo.textContent = `→ Teks harus mengandung tepat ${jumlah} blank [...]`;
}

// Init jika ada old value
const oldTipe = '{{ old("tipe_modul") }}';
if (oldTipe) pilihTipe(oldTipe);
updateInfo();
</script>
@endpush
