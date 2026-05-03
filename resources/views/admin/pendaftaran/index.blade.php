@extends('layouts.admin')
@section('title','Pendaftaran Tes')
@section('page-title','Pendaftaran Tes Full')
@section('breadcrumb','Admin / Pendaftaran')

@push('styles')
<style>
.pf-overlay{display:none;position:fixed;inset:0;z-index:900;background:rgba(0,0,0,.55);
    backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px}
.pf-overlay.open{display:flex}
.pf-modal{background:var(--navy-light);border:1px solid var(--border);border-radius:14px;
    padding:24px;width:100%;max-width:440px;overflow-y:auto;
    box-shadow:0 20px 60px rgba(0,0,0,.4);animation:modalIn .2s ease}
@keyframes modalIn{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
</style>
@endpush

@section('content')

{{-- Stat --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="fas fa-hourglass-half"></i></div>
        <div><div class="stat-val">{{ $statMenunggu }}</div><div class="stat-label">Menunggu ACC</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
        <div><div class="stat-val">{{ $statDikonfirmasi }}</div><div class="stat-label">Sudah di-ACC</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="fas fa-times-circle"></i></div>
        <div><div class="stat-val">{{ $statDitolak }}</div><div class="stat-label">Ditolak</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-ban"></i></div>
        <div><div class="stat-val">{{ $statDibatalkan }}</div><div class="stat-label">Dibatalkan</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clipboard-list" style="color:var(--accent);margin-right:8px"></i>
            Request ACC Tes Full
        </h3>
        {{-- Tab filter status --}}
        <div style="display:flex;gap:4px">
            @foreach([''=>'Semua','menunggu'=>'Menunggu','dikonfirmasi'=>'Di-ACC','ditolak'=>'Ditolak','dibatalkan'=>'Batal'] as $val=>$lbl)
            <a href="{{ route('admin.pendaftaran.index', array_merge(request()->query(), ['status'=>$val])) }}"
                class="btn btn-sm {{ request('status')===$val ? 'btn-primary' : 'btn-outline' }}"
                style="font-size:11.5px;padding:4px 12px">
                {{ $lbl }}
                @if($val==='menunggu' && $statMenunggu>0)
                <span style="background:var(--red);color:#fff;border-radius:10px;
                    padding:0 5px;font-size:10px;margin-left:3px">{{ $statMenunggu }}</span>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    {{-- Filter --}}
    <div class="card-body" style="padding:14px 20px;border-bottom:1px solid var(--border)">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div style="position:relative;flex:1;min-width:200px">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;
                    transform:translateY(-50%);color:var(--muted);font-size:12px"></i>
                <input type="text" name="search" class="form-control" style="padding-left:36px"
                    placeholder="Cari nama / email / nomor..." value="{{ request('search') }}">
            </div>
            <select name="sesi_id" class="form-control" style="width:200px">
                <option value="">Semua Sesi</option>
                @foreach($sesiList as $s)
                <option value="{{ $s->id }}" {{ request('sesi_id')==$s->id?'selected':'' }}>
                    {{ $s->judul }} — {{ $s->waktu_mulai?->format('d M Y') }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th>No. Pendaftaran</th>
                <th>Pemohon</th>
                <th width="160">Sesi yang Diminta</th>
                <th width="80">NIM/NIP</th>
                <th width="80" style="text-align:center">KTM</th>
                <th width="110" style="text-align:center">Status</th>
                <th width="170" style="text-align:center">Aksi ACC / Tolak</th>
            </tr>
        </thead>
        <tbody>
        @forelse($pendaftaran as $p)
        <tr style="{{ $p->status_pendaftaran==='menunggu' ? 'background:rgba(245,158,11,.04)' : '' }}">

            {{-- No. Pendaftaran --}}
            <td>
                @if($p->nomor_pendaftaran)
                <a href="{{ route('admin.pendaftaran.show', $p->id) }}"
                    style="font-family:monospace;font-weight:700;color:var(--accent);font-size:13.5px">
                    {{ $p->nomor_pendaftaran }}
                </a>
                @else
                <span style="color:var(--muted);font-size:12px;font-style:italic">Belum ACC</span>
                @endif
                <div style="font-size:11px;color:var(--muted)">{{ $p->created_at->format('d M Y, H:i') }}</div>
            </td>

            {{-- Pemohon --}}
            <td>
                <div style="font-weight:600;font-size:13.5px">{{ $p->user?->name ?? '—' }}</div>
                <div style="font-size:11.5px;color:var(--muted)">{{ $p->user?->email }}</div>
                <div style="font-size:11px;color:var(--muted)">
                    {{ ucfirst($p->status_polman) }} · {{ $p->program_studi }}
                </div>
            </td>

            {{-- Sesi --}}
            <td>
                <div style="font-size:13px;font-weight:600">{{ $p->sesiTes?->judul ?? '—' }}</div>
                <div style="font-size:11.5px;color:var(--muted)">
                    {{ $p->sesiTes?->waktu_mulai?->format('d M Y, H:i') }}
                </div>
            </td>

            {{-- NIM --}}
            <td style="font-family:monospace;font-size:13px">{{ $p->nim_nip }}</td>

            {{-- KTM --}}
            <td style="text-align:center">
                @if($p->berkas_identitas_url)
                <a href="{{ asset('storage/'.$p->berkas_identitas_url) }}"
                    target="_blank" class="btn btn-outline btn-sm"
                    style="font-size:11px;padding:4px 9px">
                    <i class="fas fa-id-card"></i> Lihat
                </a>
                @else
                <span style="color:var(--muted)">—</span>
                @endif
            </td>

            {{-- Status --}}
            <td style="text-align:center">
                @php $badges=['menunggu'=>['gold','hourglass-half','Menunggu ACC'],
                    'dikonfirmasi'=>['green','check-circle','Di-ACC'],
                    'ditolak'=>['red','times-circle','Ditolak'],
                    'dibatalkan'=>['gray','ban','Dibatalkan']];
                $b=$badges[$p->status_pendaftaran]??['gray','question','—']; @endphp
                <span class="badge badge-{{ $b[0] }}" style="font-size:11.5px">
                    <i class="fas fa-{{ $b[1] }}" style="font-size:9px"></i> {{ $b[2] }}
                </span>
                @if($p->status_pendaftaran==='dikonfirmasi' && !(bool)($p->user?->is_active))
                <div style="margin-top:4px">
                    <form action="{{ route('admin.user.aktifkan', $p->user->id) }}" method="POST">
                        @csrf
                        <button type="submit" style="font-size:10px;padding:2px 8px;
                            background:rgba(22,163,74,.15);color:var(--green);
                            border:1px solid rgba(22,163,74,.3);border-radius:5px;cursor:pointer">
                            <i class="fas fa-bolt" style="font-size:9px"></i> Aktifkan Akun
                        </button>
                    </form>
                </div>
                @endif
            </td>

            {{-- Aksi --}}
            <td style="text-align:center">
                <div style="display:flex;gap:5px;justify-content:center;flex-wrap:wrap">
                    @if($p->status_pendaftaran === 'menunggu')
                    <form action="{{ route('admin.pendaftaran.konfirmasi', $p->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm"
                            onclick="return confirm('ACC pendaftaran {{ $p->user?->name }}?')"
                            title="Konfirmasi / ACC">
                            <i class="fas fa-check"></i> ACC
                        </button>
                    </form>
                    <button onclick="bukaModal('tolak-{{ $p->id }}')"
                        class="btn btn-danger btn-sm" title="Tolak Pendaftaran">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                    @endif
                    <a href="{{ route('admin.pendaftaran.show', $p->id) }}"
                        class="btn btn-outline btn-sm" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </td>
        </tr>

        {{-- Modal Tolak --}}
        <div class="pf-overlay" id="tolak-{{ $p->id }}">
            <div class="pf-modal">
                <h3 style="margin-bottom:12px;color:var(--red)">
                    <i class="fas fa-times-circle"></i> Tolak Pendaftaran
                </h3>
                <p style="font-size:13.5px;color:var(--muted);margin-bottom:14px">
                    Tolak pendaftaran <strong>{{ $p->user?->name }}</strong>
                    untuk sesi <strong>{{ $p->sesiTes?->judul }}</strong>?
                </p>
                <form action="{{ route('admin.pendaftaran.tolak', $p->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Alasan Penolakan <span style="color:var(--red)">*</span></label>
                        <textarea name="catatan_admin" class="form-control" rows="3"
                            required placeholder="Berikan alasan yang jelas..."></textarea>
                    </div>
                    <div style="display:flex;gap:10px;margin-top:14px">
                        <button type="submit" class="btn btn-danger" style="flex:1">
                            <i class="fas fa-times"></i> Ya, Tolak
                        </button>
                        <button type="button" onclick="tutupModal('tolak-{{ $p->id }}')"
                            class="btn btn-outline">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        @empty
        <tr>
            <td colspan="7">
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Tidak ada pendaftaran
                        {{ request('status') ? 'dengan status "'.request('status').'"' : '' }}.
                    </p>
                </div>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;
        border-top:1px solid var(--border);font-size:13px">
        <div style="color:var(--muted)">{{ $pendaftaran->total() }} pendaftaran</div>
        {{ $pendaftaran->withQueryString()->links() }}
    </div>
</div>

@push('scripts')
<script>
function bukaModal(id)  { document.getElementById(id).classList.add('open'); }
function tutupModal(id) { document.getElementById(id).classList.remove('open'); }
document.addEventListener('keydown', e => {
    if (e.key==='Escape')
        document.querySelectorAll('.pf-overlay.open').forEach(m=>m.classList.remove('open'));
});
</script>
@endpush
@endsection
