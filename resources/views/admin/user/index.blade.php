@extends('layouts.admin')
@section('title','Data User')
@section('page-title','Data User')
@section('breadcrumb','Admin / Data User')

@section('content')

{{-- Stat --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-users"></i></div>
        <div><div class="stat-val">{{ $totalUser }}</div><div class="stat-label">Total User</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-user-check"></i></div>
        <div><div class="stat-val">{{ $totalAktif }}</div><div class="stat-label">Akun Aktif</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="fas fa-user-times"></i></div>
        <div><div class="stat-val">{{ $totalNonaktif }}</div><div class="stat-label">Akun Nonaktif</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="fas fa-user-clock"></i></div>
        <div><div class="stat-val">{{ $totalBaru }}</div><div class="stat-label">Daftar Bulan Ini</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users" style="color:var(--accent);margin-right:8px"></i>Daftar Pengguna</h3>
    </div>

    {{-- Filter --}}
    <div class="card-body" style="padding:14px 20px;border-bottom:1px solid var(--border)">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <div style="position:relative;flex:1;min-width:200px">
                <i class="fas fa-search" style="position:absolute;left:12px;top:50%;
                    transform:translateY(-50%);color:var(--muted);font-size:12px"></i>
                <input type="text" name="search" class="form-control" style="padding-left:36px"
                    placeholder="Cari nama / email..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-control" style="width:160px">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status')==='1'?'selected':'' }}>Aktif</option>
                <option value="0" {{ request('status')==='0'?'selected':'' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.user.index') }}" class="btn btn-outline btn-sm">Reset</a>
        </form>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th width="40">#</th>
                <th>Nama & Email</th>
                <th width="100">Terdaftar</th>
                <th width="110" style="text-align:center">Login Terakhir</th>
                <th width="80" style="text-align:center">Tes Full</th>
                <th width="80" style="text-align:center">Absen</th>
                <th width="100" style="text-align:center">Status Akun</th>
                <th width="130" style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($users as $i => $u)
        <tr>
            <td style="color:var(--muted);font-size:12px">{{ $users->firstItem() + $i }}</td>
            <td>
                <div style="font-weight:600;font-size:13.5px">{{ $u->name }}</div>
                <div style="font-size:11.5px;color:var(--muted)">{{ $u->email }}</div>
                @if($u->google_id)
                <span style="font-size:10px;color:#4285f4;margin-top:2px;display:inline-block">
                    <i class="fab fa-google" style="font-size:9px"></i> Google
                </span>
                @endif
            </td>
            <td style="font-size:12px;color:var(--muted)">
                {{ $u->created_at->format('d M Y') }}
            </td>
            <td style="text-align:center;font-size:12px;color:var(--muted)">
                {{ $u->last_login_at ? $u->last_login_at->diffForHumans() : '—' }}
            </td>
            <td style="text-align:center">
                @php $jmlTes = $u->percobaan->where('status','selesai')->count(); @endphp
                @if($jmlTes > 0)
                <span style="font-weight:700;color:var(--accent)">{{ $jmlTes }}×</span>
                @else
                <span style="color:var(--muted)">—</span>
                @endif
            </td>
            <td style="text-align:center">
                @if($u->jumlah_absen > 0)
                <span style="font-weight:700;color:{{ $u->jumlah_absen >= 3 ? 'var(--red)' : 'var(--gold)' }}">
                    {{ $u->jumlah_absen }}×
                </span>
                @else
                <span style="color:var(--muted)">—</span>
                @endif
            </td>
            <td style="text-align:center">
                @if((bool)$u->is_active)
                <span class="badge badge-green" style="font-size:11px">
                    <i class="fas fa-circle" style="font-size:7px"></i> Aktif
                </span>
                @else
                <span class="badge badge-red" style="font-size:11px">
                    <i class="fas fa-circle" style="font-size:7px"></i> Nonaktif
                </span>
                @endif
            </td>
            <td style="text-align:center">
                <div style="display:flex;gap:5px;justify-content:center">
                    <a href="{{ route('admin.user.show', $u->id) }}"
                        class="btn btn-outline btn-sm" title="Detail User">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form action="{{ route('admin.user.toggle', $u->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="btn btn-sm {{ (bool)$u->is_active ? 'btn-warning' : 'btn-primary' }}"
                            title="{{ (bool)$u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                            <i class="fas fa-{{ (bool)$u->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">
                <div class="empty-state"><i class="fas fa-users"></i><p>Tidak ada user ditemukan.</p></div>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

    <div style="padding:14px 20px;display:flex;align-items:center;justify-content:space-between;
        border-top:1px solid var(--border);font-size:13px">
        <div style="color:var(--muted)">{{ $users->total() }} user</div>
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
