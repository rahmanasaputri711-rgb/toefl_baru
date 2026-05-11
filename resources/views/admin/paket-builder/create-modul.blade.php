@extends('layouts.admin')
@section('title','Tambah Modul')
@section('page-title','Tambah Modul — Group '.ucfirst($grup->kategori))
@section('breadcrumb','Admin / Paket Builder / Tambah Modul')

@push('styles')
<style>
.tipe-card{border:2px solid var(--border);border-radius:12px;padding:16px 12px;
    text-align:center;cursor:pointer;transition:all .15s}
.tipe-card:hover{border-color:var(--accent)}
.tipe-card.on{border-color:var(--blue);background:rgba(26,86,219,.08)}
.tipe-info{display:none;border-radius:8px;padding:12px;font-size:13px;
    line-height:1.7;margin-bottom:12px}
</style>
@endpush

@section('content')
<div style="max-width:640px">
<div class="card">
    <div class="card-header">
        <h3>Tambah Modul —
            <span style="color:var(--accent)">Group {{ ucfirst($grup->kategori) }}</span>
        </h3>
        <a href="{{ route('admin.paket-builder.paket', $paket->id) }}"
            class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body" style="padding:24px">

        @if(session('error'))
        <div style="background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.3);
            border-radius:8px;padding:11px 14px;color:var(--red);margin-bottom:16px">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Nomor sudah dipakai --}}
        @if($terpakai->count())
        <div style="background:rgba(26,86,219,.07);border:1px solid rgba(26,86,219,.18);
            border-radius:9px;padding:11px 14px;margin-bottom:18px;font-size:13px">
            <strong style="color:var(--accent)">Nomor sudah dipakai:</strong>
            <div style="margin-top:6px;display:flex;gap:6px;flex-wrap:wrap">
                @foreach($terpakai as $t)
                <span style="background:rgba(26,86,219,.15);color:var(--accent);
                    padding:2px 10px;border-radius:6px;font-size:12px;font-weight:700">
                    {{ $t->nomor_soal_mulai }}–{{ $t->nomor_soal_selesai }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('admin.paket-builder.modul.store', [$paket->id, $grup->id]) }}"
            method="POST">
            @csrf

            {{-- Pilih tipe modul — difilter per kategori grup --}}
            @php
            $tipeList = collect(\App\Models\ModulSoal::TIPE)
                ->filter(fn($m) => $m['grup'] === $grup->kategori)
                ->toArray();
            @endphp

            <div class="form-group">
                <label class="form-label">Tipe Modul <span style="color:var(--red)">*</span></label>
                <div style="display:grid;grid-template-columns:repeat({{ count($tipeList) <= 2 ? 2 : (count($tipeList) <= 4 ? 2 : 3) }},1fr);gap:10px">
                    @foreach($tipeList as $key => $meta)
                    <label class="tipe-card" id="card-{{ $key }}"
                        onclick="pilihTipe('{{ $key }}')">
                        <input type="radio" name="tipe_modul" value="{{ $key }}"
                            id="tipe-{{ $key }}" style="display:none"
                            {{ old('tipe_modul')===$key?'checked':'' }}>
                        <div style="font-size:24px;margin-bottom:8px">
                            {{ ['passage'=>'📄','missing_letters'=>'🔤','image_email'=>'📧',
                                'conversation'=>'💬','lecture'=>'🎓','discussion'=>'🗣',
                                'short_talk'=>'⚡'][$key] }}
                        </div>
                        <div style="font-size:12.5px;font-weight:700;line-height:1.4">
                            {{ str_replace(['📄 ','🔤 ','📧 ','💬 ','🎓 ','🗣 ','⚡ '],'',$meta['label']) }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Audio full — hanya untuk listening --}}
            @if($grup->kategori === 'listening')
            <div class="form-group" id="audio-field">
                <label class="form-label">
                    Audio Full Listening
                    <small style="color:var(--muted)">— 1 audio dipakai untuk semua modul dalam group ini</small>
                </label>
                @php $audioPaketList = \App\Models\ListeningAudioPaket::where('is_aktif',true)->get(); @endphp
                <div style="display:flex;gap:10px">
                    <select name="audio_paket_id" class="form-control">
                        <option value="">-- Pilih audio (bisa diset nanti) --</option>
                        @foreach($audioPaketList as $ap)
                        <option value="{{ $ap->id }}">
                            {{ $ap->nama }} ({{ $ap->durasi_format }})
                        </option>
                        @endforeach
                    </select>
                    <a href="{{ route('admin.listening.create') }}" target="_blank"
                        class="btn btn-outline btn-sm" style="flex-shrink:0;white-space:nowrap">
                        <i class="fas fa-upload"></i> Upload
                    </a>
                </div>
                <div style="font-size:12px;color:var(--muted);margin-top:6px">
                    <i class="fas fa-info-circle"></i>
                    Bisa dikosongkan dan dipilih nanti saat input soal.
                </div>
            </div>
            @endif

            {{-- Label modul --}}
            <div class="form-group">
                <label class="form-label">
                    Label Modul <small style="color:var(--muted)">(opsional)</small>
                </label>
                <input type="text" name="judul" class="form-control"
                    value="{{ old('judul') }}"
                    placeholder="{{ $grup->kategori === 'listening'
                        ? 'cth: Conversation — Library'
                        : 'cth: Passage 1 — The Ocean Living Lights' }}">
            </div>

            {{-- Rentang nomor soal --}}
            <div class="form-group">
                <label class="form-label">Rentang Nomor Soal <span style="color:var(--red)">*</span></label>
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="flex:1">
                        <div style="font-size:11.5px;color:var(--muted);margin-bottom:4px">Dari No.</div>
                        <input type="number" name="nomor_soal_mulai" class="form-control"
                            min="1" required value="{{ old('nomor_soal_mulai', $nomorBerikut) }}"
                            oninput="updateInfo()">
                    </div>
                    <div style="padding-top:18px;color:var(--muted);font-size:18px">—</div>
                    <div style="flex:1">
                        <div style="font-size:11.5px;color:var(--muted);margin-bottom:4px">Sampai No.</div>
                        <input type="number" name="nomor_soal_selesai" class="form-control"
                            min="1" required value="{{ old('nomor_soal_selesai', $nomorBerikut) }}"
                            oninput="updateInfo()">
                    </div>
                </div>
                <div id="info-jumlah" style="font-size:12.5px;color:var(--muted);margin-top:8px"></div>
            </div>

            {{-- Info per tipe --}}
            @if($grup->kategori === 'listening')
            <div style="background:rgba(234,88,12,.08);border:1px solid rgba(234,88,12,.2);
                border-radius:8px;padding:12px;font-size:13px;line-height:1.7;margin-bottom:14px">
                🎧 <strong>Listening:</strong> Modul hanya untuk pengelompokan admin.
                Semua modul dalam Group Listening pakai <strong>1 audio full yang sama</strong>.
                Setiap soal punya <code>start_second</code> — detik kapan soal muncul saat audio diputar.
            </div>
            @endif

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Buat & Input Soal
                </button>
                <a href="{{ route('admin.paket-builder.paket', $paket->id) }}"
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
        c.classList.remove('on');
    });
    document.getElementById('card-'+key)?.classList.add('on');
    document.getElementById('tipe-'+key).checked = true;
    updateInfo();
}
function updateInfo() {
    const a = parseInt(document.querySelector('[name=nomor_soal_mulai]')?.value) || 0;
    const b = parseInt(document.querySelector('[name=nomor_soal_selesai]')?.value) || 0;
    const n = b >= a ? b - a + 1 : 0;
    const el = document.getElementById('info-jumlah');
    if (el) el.textContent = n > 0 ? `Modul ini menampung ${n} soal (No.${a}–${b})` : '';
}
const oldTipe = '{{ old("tipe_modul") }}';
if (oldTipe) pilihTipe(oldTipe);
else updateInfo();
</script>
@endpush
