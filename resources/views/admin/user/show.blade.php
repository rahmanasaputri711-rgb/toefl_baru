@extends('layouts.admin')
@section('title','Detail User')
@section('page-title','Detail User')
@section('breadcrumb','Admin / User / Detail')
@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start">
    <div class="card">
        <div class="card-body" style="text-align:center;padding:32px 20px">
            <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-dim));
                display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;
                color:#fff;margin:0 auto 16px">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div style="font-size:18px;font-weight:700">{{ $user->name }}</div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:4px">{{ $user->email }}</div>
            <div style="margin-top:14px">
                @if($user->is_active)
                    <span class="badge badge-green">Akun Aktif</span>
                @else
                    <span class="badge badge-red">Akun Nonaktif</span>
                @endif
            </div>
            <div style="margin-top:20px">
                <form action="{{ route('admin.user.toggle',$user->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm {{ $user->is_active ? 'btn-danger':'btn-success' }}" style="width:100%">
                        <i class="fas fa-{{ $user->is_active ? 'ban':'check' }}"></i>
                        {{ $user->is_active ? 'Nonaktifkan Akun':'Aktifkan Akun' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Riwayat Pendaftaran Tes</h3></div>
        <table class="tbl">
            <thead><tr><th>No. Daftar</th><th>Sesi</th><th>Tanggal Daftar</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($pendaftaran as $p)
            <tr>
                <td style="font-family:'JetBrains Mono',monospace;font-size:12px">
                    {{ $p->nomor_pendaftaran ?? '-' }}
                </td>
                <td>{{ $p->sesiTes->judul ?? '-' }}</td>
                <td style="font-size:12px;color:var(--text-muted)">{{ $p->created_at?->format('d M Y') ?? '-' }}</td>
                <td>
                    @if($p->status_pendaftaran=='dikonfirmasi') <span class="badge badge-green">Konfirmasi</span>
                    @elseif($p->status_pendaftaran=='menunggu')  <span class="badge badge-gold">Menunggu</span>
                    @else <span class="badge badge-red">Ditolak</span> @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:30px;color:var(--text-muted)">Belum ada pendaftaran</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
