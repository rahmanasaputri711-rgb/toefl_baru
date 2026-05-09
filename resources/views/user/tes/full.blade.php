@extends('layouts.user')
@section('title','Tes Full TOEFL ITP')
@section('page-title','Tes Full TOEFL ITP')
@section('breadcrumb','Home / Tes Full')

@push('styles')
<style>
.pf-overlay{display:none;position:fixed;inset:0;z-index:900;background:rgba(0,0,0,.5);
    backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px}
.pf-overlay.open{display:flex}
.pf-modal{background:#fff;border-radius:16px;padding:28px;width:100%;max-width:520px;
    max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.25);
    animation:modalIn .2s ease}
@keyframes modalIn{from{opacity:0;transform:translateY(16px) scale(.97)}to{opacity:1;transform:none}}
</style>
@endpush

@section('content')

{{-- ── KONDISI 1: Diblokir karena absen ── --}}
@if(isset($blokirAbsen) && $blokirAbsen)
<div class="card" style="border-left:4px solid var(--red)">
    <div class="card-body" style="padding:32px;text-align:center">
        <i class="fas fa-ban" style="font-size:40px;color:var(--red);display:block;margin-bottom:14px"></i>
        <div style="font-size:18px;font-weight:800;margin-bottom:8px">Akun Dibekukan Sementara</div>
        <div style="font-size:14px;color:var(--muted);max-width:400px;margin:0 auto 16px">
            Kamu tercatat <strong>{{ auth()->user()->jumlah_absen }}×</strong> tidak hadir tes tanpa keterangan.
            Hubungi UPA Bahasa untuk klarifikasi dan pemulihan akun.
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline">
            <i class="fas fa-home"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

{{-- ── KONDISI 2: Sudah Lulus ── --}}
@elseif(isset($sudahLulus) && $sudahLulus)
<div class="card" style="border-left:4px solid var(--green)">
    <div class="card-body" style="padding:32px;text-align:center">
        <i class="fas fa-trophy" style="font-size:44px;color:#f59e0b;display:block;margin-bottom:14px"></i>
        <div style="font-size:20px;font-weight:800;margin-bottom:8px;color:var(--green)">
            Selamat! Kamu Sudah Lulus 🎉
        </div>
        @if(isset($tesTerbaik) && $tesTerbaik)
        <div style="font-size:48px;font-weight:900;color:var(--green);line-height:1;margin:10px 0">
            {{ $tesTerbaik->skor_total }}
        </div>
        <div style="font-size:13px;color:var(--muted);margin-bottom:16px">
            Skor TOEFL ITP terbaik kamu — sudah memenuhi syarat kelulusan (≥ 500)
        </div>
        @endif
        <div style="font-size:14px;color:var(--muted);max-width:400px;margin:0 auto 20px">
            Kamu tidak perlu mengulang tes karena sudah lulus.
            Lihat detail hasil dan cetak sertifikat di halaman Hasil.
        </div>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
            <a href="{{ route('user.hasil.index') }}" class="btn btn-primary">
                <i class="fas fa-trophy"></i> Lihat Hasil & Sertifikat
            </a>
        </div>
    </div>
</div>

{{-- ── KONDISI 3: Sudah 3× gagal ── --}}
@elseif(isset($maxTercapai) && $maxTercapai)
<div class="card" style="border-left:4px solid var(--red)">
    <div class="card-body" style="padding:32px;text-align:center">
        <i class="fas fa-times-circle" style="font-size:40px;color:var(--red);display:block;margin-bottom:14px"></i>
        <div style="font-size:18px;font-weight:800;margin-bottom:8px">Batas Maksimal Tes Tercapai</div>
        <div style="font-size:14px;color:var(--muted);max-width:440px;margin:0 auto 16px;line-height:1.7">
            Kamu sudah mengikuti tes full sebanyak <strong>3 kali</strong> namun belum mencapai skor lulus (≥ 500).
            Hubungi UPA Bahasa untuk konsultasi dan kemungkinan pengecualian.
        </div>
        @if(isset($tesTerakhir) && $tesTerakhir)
        <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);
            border-radius:10px;padding:14px;display:inline-block;margin-bottom:16px">
            <div style="font-size:12px;color:var(--muted);margin-bottom:4px">Skor terakhir kamu</div>
            <div style="font-size:32px;font-weight:900;color:var(--red)">{{ $tesTerakhir->skor_total }}</div>
        </div>
        @endif
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
            <a href="{{ route('user.hasil.index') }}" class="btn btn-outline">
                <i class="fas fa-history"></i> Lihat Riwayat Tes
            </a>
        </div>
    </div>
</div>

{{-- ── KONDISI NORMAL: Bisa ikut tes ── --}}
@else

    {{-- Info percobaan tersisa --}}
    @php $jumlahTes = $jumlahTes ?? 0; $sisaCoba = $sisaCoba ?? 3; @endphp
    @if($jumlahTes > 0)
    <div style="background:rgba(26,86,219,.07);border:1px solid rgba(26,86,219,.18);
        border-radius:10px;padding:12px 18px;margin-bottom:16px;
        display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
        <div style="font-size:13.5px">
            <i class="fas fa-redo" style="color:var(--accent)"></i>
            Kamu sudah ikut tes full <strong>{{ $jumlahTes }}×</strong>.
            @if($sisaCoba > 0)
                Tersisa <strong style="color:{{ $sisaCoba===1?'var(--red)':'var(--accent)' }}">
                    {{ $sisaCoba }} kesempatan</strong> lagi.
            @endif
        </div>
        <a href="{{ route('user.hasil.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-history"></i> Lihat Riwayat
        </a>
    </div>
    @endif

    {{-- Card pendaftaran aktif --}}
    @if($pendaftaran)
    <div class="card" style="margin-bottom:18px;border-left:4px solid
        {{ $pendaftaran->status_pendaftaran==='dikonfirmasi' ? 'var(--green)' : 'var(--gold)' }}">
        <div class="card-body" style="padding:18px 20px">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div>
                    <div style="font-size:12px;color:var(--muted);margin-bottom:4px">
                        <i class="fas fa-clipboard-check"></i> Pendaftaran Aktif
                    </div>
                    <div style="font-size:15px;font-weight:700">
                        {{ $pendaftaran->sesiTes?->judul ?? 'Sesi Tes' }}
                    </div>
                    <div style="font-size:12.5px;color:var(--muted);margin-top:2px">
                        {{ $pendaftaran->sesiTes?->waktu_mulai?->format('d M Y, H:i') }} WIB
                        @if($pendaftaran->nomor_pendaftaran)
                        &nbsp;·&nbsp;
                        <span style="font-family:monospace;color:var(--accent);font-weight:700">
                            {{ $pendaftaran->nomor_pendaftaran }}
                        </span>
                        @endif
                    </div>
                </div>
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                    @if($pendaftaran->status_pendaftaran === 'dikonfirmasi')
                        <span class="badge badge-green">
                            <i class="fas fa-check-circle" style="font-size:9px"></i> Dikonfirmasi
                        </span>
                        @if($sesiAktif)
                        @php $sesiIniSudahSelesai = in_array($sesiAktif->id, $sesiSudahSelesai ?? []); @endphp
                        @if(!$sesiIniSudahSelesai)
                        <form action="{{ route('user.tes.mulai') }}" method="POST">
                            @csrf
                            <input type="hidden" name="sesi_id" value="{{ $sesiAktif->id }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-play-circle"></i> Mulai Tes Sekarang
                            </button>
                        </form>
                        @else
                        <span style="font-size:13px;color:var(--muted);font-style:italic">
                            <i class="fas fa-info-circle"></i>
                            Tes di sesi ini sudah selesai. Daftar ke sesi baru.
                        </span>
                        @endif
                        @else
                        <span style="font-size:13px;color:var(--muted);font-style:italic">
                            <i class="fas fa-clock"></i> Menunggu admin aktifkan sesi
                        </span>
                        @endif
                    @else
                        <span class="badge badge-gold">
                            <i class="fas fa-hourglass-half" style="font-size:9px"></i> Menunggu Verifikasi
                        </span>
                        <div style="font-size:12px;color:var(--muted)">
                            Admin sedang memeriksa berkas identitas kamu
                        </div>
                    @endif
                    <a href="{{ route('user.pendaftaran.status') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-info-circle"></i> Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Daftar jadwal tersedia --}}
    <div class="card">
        <div class="card-header">
            <h3>
                <i class="fas fa-calendar-alt" style="color:var(--accent);margin-right:8px"></i>
                Jadwal Tes Full Tersedia
            </h3>
            @if($sisaCoba > 0)
            <span style="font-size:12.5px;color:var(--muted)">
                Sisa kesempatan: <strong style="color:{{ $sisaCoba===1?'var(--red)':'var(--text)' }}">
                    {{ $sisaCoba }}×</strong>
            </span>
            @endif
        </div>
        <div class="card-body">
            @php $sesiSudahSelesai = $sesiSudahSelesai ?? []; $sesiSudahDaftar = $sesiSudahDaftar ?? []; @endphp
            @forelse($sesiList as $sesi)
            @php
                $sisa         = $sesi->kuota_peserta - $sesi->peserta_terdaftar;
                $penuh        = $sisa <= 0;
                $pct          = $sesi->kuota_peserta > 0
                    ? min(100, ($sesi->peserta_terdaftar / $sesi->kuota_peserta) * 100) : 0;
                $sudahDaftar  = in_array($sesi->id, $sesiSudahDaftar  ?? []);
                $sudahSelesai = in_array($sesi->id, $sesiSudahSelesai ?? []);
            @endphp
            <div style="background:var(--bg);border-radius:12px;padding:18px 20px;
                margin-bottom:12px;
                border:1px solid {{ $penuh ? 'rgba(239,68,68,.25)' : 'var(--border)' }}">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;
                    gap:14px;flex-wrap:wrap">
                    <div style="flex:1;min-width:0">
                        <div style="font-size:15px;font-weight:700;margin-bottom:5px">
                            {{ $sesi->judul }}
                        </div>
                        <div style="font-size:12.5px;color:var(--muted);
                            display:flex;gap:14px;flex-wrap:wrap">
                            <span>
                                <i class="fas fa-calendar" style="font-size:10px"></i>
                                {{ $sesi->waktu_mulai->translatedFormat('l, d F Y') }}
                            </span>
                            <span>
                                <i class="fas fa-clock" style="font-size:10px"></i>
                                {{ $sesi->waktu_mulai->format('H:i') }}
                                — {{ $sesi->waktu_selesai?->format('H:i') }} WIB
                            </span>
                            <span>
                                <i class="fas fa-stopwatch" style="font-size:10px"></i>
                                {{ $sesi->durasi_menit }} menit
                            </span>
                        </div>
                        <div style="margin-top:10px">
                            <div style="display:flex;justify-content:space-between;
                                font-size:12px;margin-bottom:4px">
                                <span style="color:var(--muted)">
                                    <i class="fas fa-users" style="font-size:10px"></i>
                                    {{ $sesi->peserta_terdaftar }} / {{ $sesi->kuota_peserta }} peserta
                                </span>
                                <span style="font-weight:700;
                                    color:{{ $penuh ? 'var(--red)' : ($sisa<=5 ? 'var(--gold)' : 'var(--green)') }}">
                                    @if($penuh) Penuh
                                    @elseif($sisa<=5) Sisa {{ $sisa }} tempat!
                                    @else Sisa {{ $sisa }} tempat
                                    @endif
                                </span>
                            </div>
                            <div style="height:6px;background:var(--border);border-radius:3px">
                                <div style="height:6px;border-radius:3px;width:{{ $pct }}%;
                                    background:{{ $penuh?'var(--red)':($pct>=80?'var(--gold)':'var(--green)') }};
                                    transition:width .3s"></div>
                            </div>
                        </div>
                    </div>

                    <div style="flex-shrink:0;display:flex;align-items:center;padding-top:4px">
                        @if($sudahSelesai)
                            {{-- Sesi ini sudah pernah diikuti tesnya — tidak bisa masuk lagi --}}
                            <span style="font-size:12.5px;color:var(--muted);
                                display:flex;align-items:center;gap:6px">
                                <i class="fas fa-check-circle" style="color:var(--green)"></i>
                                Sudah Pernah Ikut Tes
                            </span>
                        @elseif($sudahDaftar)
                            {{-- Punya pendaftaran aktif di sesi ini --}}
                            <a href="{{ route('user.pendaftaran.status') }}" class="btn btn-outline">
                                <i class="fas fa-clipboard-check"></i> Lihat Pendaftaran Saya
                            </a>
                        @elseif($penuh)
                            <button disabled class="btn btn-outline"
                                style="opacity:.45;cursor:not-allowed">
                                <i class="fas fa-times-circle"></i> Penuh
                            </button>
                        @elseif($pendaftaran)
                            {{-- Punya pendaftaran aktif di sesi LAIN --}}
                            <button disabled class="btn btn-outline"
                                style="opacity:.45;cursor:not-allowed"
                                title="Selesaikan pendaftaran aktif dulu sebelum daftar sesi lain">
                                <i class="fas fa-lock"></i> Sudah Ada Pendaftaran
                            </button>
                        @else
                            <button
                                onclick="bukaDaftar({{ $sesi->id }}, '{{ addslashes($sesi->judul) }}')"
                                class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Daftar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Belum ada jadwal tes yang dibuka saat ini.<br>
                Pantau pengumuman dari UPA Bahasa.</p>
            </div>
            @endforelse
        </div>
    </div>

@endif

{{-- Modal Form Pendaftaran --}}
<div class="pf-overlay" id="modal-daftar">
    <div class="pf-modal">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <div>
                <h3 style="margin:0;font-size:17px">
                    <i class="fas fa-user-plus" style="color:var(--blue)"></i> Daftar Tes Full
                </h3>
                <p style="font-size:13px;color:var(--muted);margin:4px 0 0" id="modal-sesi-nama"></p>
            </div>
            <button onclick="tutupModal()" style="background:none;border:none;cursor:pointer;
                font-size:20px;color:var(--muted);padding:4px 8px">&times;</button>
        </div>
        <form action="{{ route('user.pendaftaran.daftar') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="sesi_id" id="input-sesi-id">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">NIM / NIP <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nim_nip" class="form-control"
                        required placeholder="cth: 2021001001" value="{{ old('nim_nip') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:var(--red)">*</span></label>
                    <select name="status_polman" class="form-control" required>
                        <option value="mahasiswa" {{ old('status_polman')=='mahasiswa'?'selected':'' }}>Mahasiswa</option>
                        <option value="dosen"     {{ old('status_polman')=='dosen'    ?'selected':'' }}>Dosen</option>
                        <option value="staf"      {{ old('status_polman')=='staf'     ?'selected':'' }}>Staf</option>
                        <option value="alumni"    {{ old('status_polman')=='alumni'   ?'selected':'' }}>Alumni</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Program Studi <span style="color:var(--red)">*</span></label>
                    <input type="text" name="program_studi" class="form-control"
                        required placeholder="cth: D4 Teknik Mesin" value="{{ old('program_studi') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">No. Telepon <span style="color:var(--red)">*</span></label>
                    <input type="text" name="no_telepon" class="form-control"
                        required placeholder="08xxxxxxxxxx" value="{{ old('no_telepon') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Upload KTM / KTP <span style="color:var(--red)">*</span></label>
                    <input type="file" name="berkas_identitas" class="form-control"
                        accept=".jpg,.jpeg,.png,.pdf" required>
                    <div style="font-size:11px;color:var(--muted);margin-top:4px">
                        JPG/PNG/PDF, maks 2MB
                    </div>
                </div>
            </div>
            <div style="margin-top:14px;padding:11px 14px;background:#f8faff;
                border:1px solid #dbeafe;border-radius:8px;font-size:12.5px;color:#1e40af">
                <i class="fas fa-info-circle"></i>
                Pendaftaran akan diverifikasi admin. Kamu akan dapat notifikasi setelah diverifikasi.
            </div>
            <div style="display:flex;gap:10px;margin-top:14px">
                <button type="submit" class="btn btn-primary" style="flex:1">
                    <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                </button>
                <button type="button" onclick="tutupModal()" class="btn btn-outline">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function bukaDaftar(sesiId, sesiNama) {
    document.getElementById('input-sesi-id').value = sesiId;
    document.getElementById('modal-sesi-nama').textContent = 'Jadwal: ' + sesiNama;
    document.getElementById('modal-daftar').classList.add('open');
}
function tutupModal() {
    document.getElementById('modal-daftar').classList.remove('open');
}
document.getElementById('modal-daftar').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});
document.addEventListener('keydown', e => { if (e.key==='Escape') tutupModal(); });
</script>
@endpush