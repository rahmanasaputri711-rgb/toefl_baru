@extends('layouts.user')
@section('title','Status Pendaftaran')
@section('page-title','Status Pendaftaran')
@section('breadcrumb','Home / Pendaftaran')

@push('styles')
<style>
.countdown-box{display:flex;gap:8px;margin-top:12px}
.cd-unit{text-align:center;background:rgba(26,86,219,.12);border:1px solid rgba(26,86,219,.2);
    border-radius:10px;padding:8px 14px;min-width:54px}
.cd-num{font-size:26px;font-weight:800;color:var(--blue);line-height:1;font-variant-numeric:tabular-nums}
.cd-lbl{font-size:10px;color:var(--muted);margin-top:2px;text-transform:uppercase;letter-spacing:.5px}
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 13px;
    border-radius:20px;font-size:12.5px;font-weight:700}
.timeline-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;margin-top:4px}
</style>
@endpush

@section('content')

{{-- ── Cooldown / Blokir Alert ── --}}
@if($user->dalamCooldown())
<div class="alert alert-warning" style="margin-bottom:18px">
    <i class="fas fa-hourglass-half"></i>
    <div>
        <strong>Masa Cooldown Aktif</strong> — Kamu baru bisa mendaftar tes full lagi pada
        <strong>{{ $user->cooldown_sampai->translatedFormat('d F Y') }}</strong>.
        Gunakan waktu ini untuk latihan dan simulasi.
        <a href="{{ route('user.tes.simulasi') }}" style="color:var(--gold);font-weight:700;margin-left:8px">
            Latihan Simulasi →
        </a>
    </div>
</div>
@endif

@if($user->diblokir())
<div class="alert alert-danger" style="margin-bottom:18px">
    <i class="fas fa-ban"></i>
    <div>
        <strong>Akun Dibekukan Sementara</strong> —
        Tercatat {{ $user->jumlah_absen }}x tidak hadir tanpa keterangan.
        Hubungi UPA Bahasa untuk klarifikasi dan pemulihan akun.
    </div>
</div>
@endif

{{-- ── Pendaftaran Aktif ── --}}
@if($aktif)
<div class="card" style="margin-bottom:18px;border-top:3px solid
    {{ $aktif->status_pendaftaran==='dikonfirmasi' ? 'var(--green)' : 'var(--gold)' }}">
    <div class="card-header">
        <h3>
            <i class="fas fa-clipboard-check" style="color:
                {{ $aktif->status_pendaftaran==='dikonfirmasi' ? 'var(--green)' : 'var(--gold)' }};
                margin-right:8px"></i>
            Pendaftaran Aktif
        </h3>
        @if($aktif->status_pendaftaran==='dikonfirmasi')
        <span class="status-badge" style="background:rgba(22,163,74,.12);color:var(--green)">
            <i class="fas fa-check-circle" style="font-size:11px"></i> Dikonfirmasi
        </span>
        @else
        <span class="status-badge" style="background:rgba(217,119,6,.12);color:var(--gold)">
            <i class="fas fa-clock" style="font-size:11px"></i> Menunggu Verifikasi
        </span>
        @endif
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
            <div>
                <div style="font-size:18px;font-weight:800;margin-bottom:6px">
                    {{ $aktif->sesiTes?->judul ?? 'Sesi Tes' }}
                </div>

                <div style="display:flex;flex-direction:column;gap:6px;font-size:13px;color:var(--muted)">
                    <div><i class="fas fa-calendar-alt" style="width:16px"></i>
                        {{ $aktif->sesiTes?->waktu_mulai?->format('l, d F Y') ?? '—' }}
                    </div>
                    <div><i class="fas fa-clock" style="width:16px"></i>
                        Pukul {{ $aktif->sesiTes?->waktu_mulai?->format('H:i') ?? '—' }} WIB
                    </div>
                    <div><i class="fas fa-map-marker-alt" style="width:16px"></i>
                        Ruang UPA Bahasa, Gedung Utama Polman
                    </div>
                    @if($aktif->nomor_pendaftaran)
                    <div>
                        <i class="fas fa-id-card" style="width:16px"></i>
                        Nomor Peserta:
                        <span style="font-family:'JetBrains Mono',monospace;font-size:14px;
                            font-weight:700;color:var(--blue)">{{ $aktif->nomor_pendaftaran }}</span>
                    </div>
                    @endif
                </div>

                {{-- Countdown jika sudah dikonfirmasi --}}
                @if($aktif->status_pendaftaran==='dikonfirmasi' && $aktif->sesiTes?->waktu_mulai?->isFuture())
                <div style="margin-top:14px">
                    <div style="font-size:11.5px;color:var(--muted);margin-bottom:6px;font-weight:600">
                        <i class="fas fa-hourglass-start" style="color:var(--blue)"></i>
                        Menuju hari tes:
                    </div>
                    <div class="countdown-box" id="countdown-wrap">
                        <div class="cd-unit"><div class="cd-num" id="cd-hari">--</div><div class="cd-lbl">Hari</div></div>
                        <div class="cd-unit"><div class="cd-num" id="cd-jam">--</div><div class="cd-lbl">Jam</div></div>
                        <div class="cd-unit"><div class="cd-num" id="cd-menit">--</div><div class="cd-lbl">Menit</div></div>
                        <div class="cd-unit"><div class="cd-num" id="cd-detik">--</div><div class="cd-lbl">Detik</div></div>
                    </div>
                </div>
                @endif

                {{-- Tombol Batal --}}
                @if($aktif->bisaDibatalkanUser())
                <div style="margin-top:16px">
                    <button onclick="document.getElementById('modal-batal').style.display='flex'"
                        class="btn btn-outline btn-sm" style="color:var(--red);border-color:var(--red)">
                        <i class="fas fa-times-circle"></i> Batalkan Pendaftaran
                    </button>
                    <div style="font-size:11px;color:var(--muted);margin-top:5px">
                        <i class="fas fa-info-circle"></i>
                        Bisa dibatalkan hingga H-2 sebelum tes. Kuota akan dikembalikan.
                    </div>
                </div>
                @elseif($aktif->status_pendaftaran==='menunggu')
                <div style="margin-top:12px;font-size:12px;color:var(--muted)">
                    <i class="fas fa-info-circle"></i>
                    Pendaftaran tidak bisa dibatalkan saat mendekati hari tes (< H-2).
                </div>
                @endif
            </div>

            {{-- Sisi kanan: Progress langkah --}}
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--muted);
                    text-transform:uppercase;letter-spacing:.8px;margin-bottom:14px">
                    Progres Pendaftaran
                </div>
                @php
                $steps = [
                    ['label'=>'Pendaftaran Dikirim','done'=>true,'icon'=>'paper-plane'],
                    ['label'=>'Verifikasi KTM Admin','done'=>$aktif->status_pendaftaran==='dikonfirmasi','icon'=>'user-check'],
                    ['label'=>'Siap Ikut Tes','done'=>$aktif->status_pendaftaran==='dikonfirmasi' && $aktif->sesiTes?->is_aktif,'icon'=>'play-circle'],
                    ['label'=>'Tes Selesai','done'=>false,'icon'=>'flag-checkered'],
                ];
                @endphp
                <div style="display:flex;flex-direction:column;gap:10px">
                    @foreach($steps as $step)
                    <div style="display:flex;align-items:flex-start;gap:12px">
                        <div style="width:28px;height:28px;border-radius:50%;flex-shrink:0;
                            background:{{ $step['done'] ? 'rgba(22,163,74,.15)' : 'rgba(226,232,240,.5)' }};
                            border:2px solid {{ $step['done'] ? 'var(--green)' : 'var(--border)' }};
                            display:flex;align-items:center;justify-content:center;
                            color:{{ $step['done'] ? 'var(--green)' : 'var(--muted)' }};font-size:11px">
                            <i class="fas fa-{{ $step['done'] ? 'check' : $step['icon'] }}"></i>
                        </div>
                        <div style="font-size:13.5px;font-weight:{{ $step['done'] ? '600' : '400' }};
                            color:{{ $step['done'] ? 'var(--text)' : 'var(--muted)' }};padding-top:4px">
                            {{ $step['label'] }}
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($aktif->status_pendaftaran==='dikonfirmasi')
                <div style="margin-top:16px;padding:12px;background:rgba(22,163,74,.07);
                    border:1px solid rgba(22,163,74,.2);border-radius:10px;font-size:13px">
                    <i class="fas fa-lightbulb" style="color:var(--green)"></i>
                    <strong>Persiapan:</strong> Hadir 15 menit sebelum tes. Bawa kartu identitas (KTM/KTP).
                    Tidak diperkenankan membawa catatan.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Batal --}}
<div id="modal-batal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
    z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;padding:28px;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2)">
        <h3 style="margin-bottom:8px;color:var(--red)">
            <i class="fas fa-exclamation-triangle"></i> Batalkan Pendaftaran?
        </h3>
        <p style="font-size:13.5px;color:var(--muted);margin-bottom:18px">
            Pembatalan tidak bisa dibatalkan kembali. Kuota akan otomatis dikembalikan ke jadwal.
        </p>
        <form action="{{ route('user.pendaftaran.batal', $aktif->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Alasan Pembatalan</label>
                <textarea name="alasan" class="form-control" rows="2"
                    placeholder="Tulis alasan pembatalan..." required></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:16px">
                <button type="button"
                    onclick="document.getElementById('modal-batal').style.display='none'"
                    class="btn btn-outline" style="flex:1">Batal</button>
                <button type="submit" class="btn" style="flex:1;background:var(--red);color:#fff">
                    <i class="fas fa-times-circle"></i> Ya, Batalkan
                </button>
            </div>
        </form>
    </div>
</div>

@else
<div class="card" style="margin-bottom:18px">
    <div class="card-body" style="text-align:center;padding:40px">
        <i class="fas fa-clipboard" style="font-size:40px;color:var(--muted);margin-bottom:14px;display:block"></i>
        <div style="font-size:16px;font-weight:700;margin-bottom:8px">Belum Ada Pendaftaran Aktif</div>
        <p style="color:var(--muted);font-size:13.5px;margin-bottom:18px">
            Daftarkan diri kamu ke jadwal tes TOEFL ITP yang tersedia.
        </p>
        @if(!$user->dalamCooldown() && !$user->diblokir())
        <a href="{{ route('user.tes.full') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Daftar Tes Sekarang
        </a>
        @endif
    </div>
</div>
@endif

{{-- ── Riwayat Pendaftaran ── --}}
@if($riwayat->count())
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-history" style="color:var(--muted);margin-right:8px"></i>Riwayat Pendaftaran</h3>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Sesi Tes</th>
                <th width="110">Tanggal</th>
                <th width="130">Nomor Peserta</th>
                <th width="110">Status</th>
                <th width="90">Kehadiran</th>
                <th width="80">Hasil</th>
            </tr>
        </thead>
        <tbody>
        @foreach($riwayat as $r)
        @php
            $stColors = ['dikonfirmasi'=>'green','ditolak'=>'red','dibatalkan'=>'gray','absen'=>'red'];
            $stColor  = $stColors[$r->status_pendaftaran] ?? 'gray';
        @endphp
        <tr>
            <td>
                <div style="font-weight:600;font-size:13.5px">{{ $r->sesiTes?->judul ?? '—' }}</div>
                <div style="font-size:11.5px;color:var(--muted)">
                    {{ $r->sesiTes?->waktu_mulai?->format('d M Y') ?? '—' }}
                </div>
            </td>
            <td style="font-size:12.5px;color:var(--muted)">
                {{ $r->created_at->format('d M Y') }}
            </td>
            <td>
                @if($r->nomor_pendaftaran)
                <span style="font-family:'JetBrains Mono',monospace;font-size:12.5px;
                    color:var(--blue);font-weight:600">{{ $r->nomor_pendaftaran }}</span>
                @else
                <span style="color:var(--muted)">—</span>
                @endif
            </td>
            <td>
                <span class="badge badge-{{ $stColor }}" style="font-size:11.5px">
                    {{ ucfirst(str_replace('_',' ',$r->status_pendaftaran)) }}
                </span>
            </td>
            <td style="text-align:center">
                @if($r->is_hadir === true)
                    <i class="fas fa-check-circle" style="color:var(--green)" title="Hadir"></i>
                @elseif($r->is_hadir === false)
                    <i class="fas fa-times-circle" style="color:var(--red)" title="Absen"></i>
                @else
                    <span style="color:var(--muted)">—</span>
                @endif
            </td>
            <td style="text-align:center">
                @php
                    $hasilTes = \App\Models\PercobaanTes::where('user_id',auth()->id())
                        ->where('sesi_id',$r->sesi_id)->where('status','selesai')->first();
                @endphp
                @if($hasilTes)
                <a href="{{ route('user.hasil.detail',$hasilTes->id) }}"
                    style="font-weight:700;color:var(--blue);font-size:14px">
                    {{ $hasilTes->skor_total }}
                </a>
                @else
                <span style="color:var(--muted)">—</span>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection

@push('scripts')
@if($aktif && $aktif->status_pendaftaran==='dikonfirmasi' && $aktif->sesiTes?->waktu_mulai?->isFuture())
<script>
(function() {
    const target = new Date("{{ $aktif->sesiTes->waktu_mulai->toIso8601String() }}").getTime();
    function tick() {
        const diff = target - Date.now();
        if (diff <= 0) {
            document.getElementById('countdown-wrap').innerHTML =
                '<span style="color:var(--green);font-weight:700"><i class="fas fa-bell"></i> Tes Sedang Berlangsung!</span>';
            return;
        }
        const d = Math.floor(diff/86400000);
        const h = Math.floor((diff%86400000)/3600000);
        const m = Math.floor((diff%3600000)/60000);
        const s = Math.floor((diff%60000)/1000);
        document.getElementById('cd-hari').textContent  = String(d).padStart(2,'0');
        document.getElementById('cd-jam').textContent   = String(h).padStart(2,'0');
        document.getElementById('cd-menit').textContent = String(m).padStart(2,'0');
        document.getElementById('cd-detik').textContent = String(s).padStart(2,'0');
    }
    tick(); setInterval(tick,1000);
})();
</script>
@endif
@endpush
