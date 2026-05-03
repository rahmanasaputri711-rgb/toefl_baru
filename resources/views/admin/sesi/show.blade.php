@extends('layouts.admin')
@section('title','Detail Sesi — Absensi')
@section('page-title','Detail Sesi & Absensi')
@section('breadcrumb','Admin / Sesi Tes / Detail')

@section('content')

<div style="display:flex;gap:14px;align-items:center;margin-bottom:20px;flex-wrap:wrap">
    <a href="{{ route('admin.sesi.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <div>
        <h2 style="margin:0;font-size:20px;font-weight:800">{{ $sesi->judul }}</h2>
        <div style="font-size:13px;color:var(--muted);margin-top:3px">
            <i class="fas fa-calendar"></i>
            {{ $sesi->waktu_mulai?->translatedFormat('l, d F Y') }}
            &nbsp;·&nbsp;
            {{ $sesi->waktu_mulai?->format('H:i') }} — {{ $sesi->waktu_selesai?->format('H:i') }} WIB
            &nbsp;·&nbsp;
            {{ $sesi->durasi_menit }} menit
        </div>
    </div>

    {{-- Toggle aktif/nonaktif sesi --}}
    <div style="margin-left:auto;display:flex;gap:8px">
        <form action="{{ route('admin.sesi.toggle', $sesi->id) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit"
                class="btn {{ $sesi->is_aktif ? 'btn-warning' : 'btn-primary' }}"
                onclick="return confirm('{{ $sesi->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }} sesi ini?')">
                <i class="fas fa-{{ $sesi->is_aktif ? 'stop-circle' : 'play-circle' }}"></i>
                {{ $sesi->is_aktif ? 'Nonaktifkan Sesi' : 'Aktifkan Sesi' }}
            </button>
        </form>
        <a href="{{ route('admin.sesi.edit', $sesi->id) }}" class="btn btn-outline btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

{{-- Stat sesi ini --}}
<div class="stat-grid" style="grid-template-columns:repeat(5,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-users"></i></div>
        <div><div class="stat-val">{{ $kuota }}</div><div class="stat-label">Kuota</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-user-check"></i></div>
        <div><div class="stat-val">{{ $statHadir }}</div><div class="stat-label">Hadir</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="fas fa-user-times"></i></div>
        <div><div class="stat-val">{{ $statAbsen }}</div><div class="stat-label">Absen</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="fas fa-question-circle"></i></div>
        <div><div class="stat-val">{{ $statBelumAbsen }}</div><div class="stat-label">Belum Ditandai</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon {{ $sesi->is_aktif ? 'si-green' : 'si-gray' }}">
            <i class="fas fa-{{ $sesi->is_aktif ? 'circle' : 'stop-circle' }}"></i>
        </div>
        <div>
            <div class="stat-val">{{ $sesi->is_aktif ? 'Aktif' : 'Nonaktif' }}</div>
            <div class="stat-label">Status Sesi</div>
        </div>
    </div>
</div>

{{-- ── DAFTAR PESERTA + ABSENSI ── --}}
<div class="card">
    <div class="card-header">
        <h3>
            <i class="fas fa-clipboard-check" style="color:var(--accent);margin-right:8px"></i>
            Daftar Peserta & Absensi
        </h3>
        <div style="font-size:12.5px;color:var(--muted)">
            {{ $peserta->total() }} peserta terdaftar
        </div>
    </div>

    {{-- Info cara absen --}}
    @if($sesi->is_aktif)
    <div style="background:rgba(26,86,219,.07);border-bottom:1px solid rgba(26,86,219,.15);
        padding:12px 20px;font-size:13px;color:rgba(147,197,253,.9);
        display:flex;align-items:center;gap:10px">
        <i class="fas fa-info-circle" style="flex-shrink:0;color:#60a5fa"></i>
        Sesi sedang <strong>aktif</strong>. Tandai kehadiran peserta di bawah saat hari tes berlangsung.
        User yang absen tanpa keterangan akan dicatat dan bisa kena sanksi.
    </div>
    @endif

    <table class="tbl">
        <thead>
            <tr>
                <th width="40">#</th>
                <th>Peserta</th>
                <th width="80">NIM/NIP</th>
                <th width="110">Program Studi</th>
                <th width="130">No. Pendaftaran</th>
                <th width="130" style="text-align:center">Status ACC</th>
                <th width="120" style="text-align:center">Kehadiran</th>
                <th width="140" style="text-align:center">Aksi Absensi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($peserta as $i => $p)
        <tr style="
            @if($p->is_hadir === true) background:rgba(22,163,74,.04);
            @elseif($p->is_hadir === false) background:rgba(239,68,68,.04);
            @endif
        ">
            <td style="color:var(--muted);font-size:12px">{{ $peserta->firstItem() + $i }}</td>

            {{-- Peserta --}}
            <td>
                <div style="font-weight:600;font-size:13.5px">{{ $p->user?->name ?? '—' }}</div>
                <div style="font-size:11.5px;color:var(--muted)">{{ $p->user?->email }}</div>
            </td>

            <td style="font-family:monospace;font-size:13px">{{ $p->nim_nip }}</td>

            <td style="font-size:12.5px;color:var(--muted)">{{ $p->program_studi }}</td>

            {{-- No. Pendaftaran --}}
            <td>
                @if($p->nomor_pendaftaran)
                <span style="font-family:monospace;font-weight:700;color:var(--accent);font-size:12.5px">
                    {{ $p->nomor_pendaftaran }}
                </span>
                @else
                <span style="color:var(--muted);font-size:12px">—</span>
                @endif
            </td>

            {{-- Status ACC --}}
            <td style="text-align:center">
                @if($p->status_pendaftaran === 'dikonfirmasi')
                <span class="badge badge-green" style="font-size:11px">
                    <i class="fas fa-check" style="font-size:9px"></i> Di-ACC
                </span>
                @else
                <span class="badge badge-gold" style="font-size:11px">
                    {{ ucfirst($p->status_pendaftaran) }}
                </span>
                @endif
            </td>

            {{-- Kehadiran --}}
            <td style="text-align:center">
                @if($p->is_hadir === true)
                <span class="badge badge-green" style="font-size:12px;padding:5px 12px">
                    <i class="fas fa-check-circle" style="font-size:10px"></i> Hadir
                </span>
                @elseif($p->is_hadir === false)
                <span class="badge badge-red" style="font-size:12px;padding:5px 12px">
                    <i class="fas fa-times-circle" style="font-size:10px"></i> Absen
                </span>
                @if($p->ditandai_absen_at)
                <div style="font-size:10.5px;color:var(--muted);margin-top:3px">
                    {{ $p->ditandai_absen_at->format('H:i') }}
                </div>
                @endif
                @else
                <span style="color:var(--muted);font-size:12px">
                    <i class="fas fa-question-circle"></i> Belum
                </span>
                @endif
            </td>

            {{-- Aksi Absensi --}}
            <td style="text-align:center">
                @if($p->status_pendaftaran === 'dikonfirmasi')
                <div style="display:flex;gap:5px;justify-content:center">
                    @if($p->is_hadir !== true)
                    <form action="{{ route('admin.sesi.tandai-hadir', $p->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm"
                            style="font-size:11.5px"
                            title="Tandai Hadir">
                            <i class="fas fa-check"></i> Hadir
                        </button>
                    </form>
                    @endif

                    @if($p->is_hadir !== false)
                    <form action="{{ route('admin.pendaftaran.absen', $p->id) }}" method="POST"
                        onsubmit="return confirm('Tandai {{ $p->user?->name }} sebagai ABSEN? Ini akan menambah catatan absen user.')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"
                            style="font-size:11.5px"
                            title="Tandai Absen">
                            <i class="fas fa-times"></i> Absen
                        </button>
                    </form>
                    @endif

                    @if($p->is_hadir !== null)
                    {{-- Reset kehadiran --}}
                    <form action="{{ route('admin.sesi.reset-hadir', $p->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm"
                            style="font-size:11px;padding:4px 8px" title="Reset Kehadiran">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>
                    @endif
                </div>
                @else
                <span style="font-size:11.5px;color:var(--muted);font-style:italic">
                    Belum di-ACC
                </span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Belum ada peserta yang mendaftar untuk sesi ini.</p>
                </div>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;
        border-top:1px solid var(--border);font-size:13px">
        <div style="color:var(--muted)">{{ $peserta->total() }} peserta</div>
        {{ $peserta->links() }}
    </div>
</div>
@endsection
