@extends('layouts.admin')
@section('title','Sesi Tes')
@section('page-title','Manajemen Sesi Tes')
@section('breadcrumb','Admin / Sesi Tes')

@section('content')

{{-- Info: hanya Tes Full yang perlu sesi admin --}}
<div class="alert alert-info" style="margin-bottom:20px">
    <i class="fas fa-info-circle"></i>
    <div>
        <strong>Catatan Penting:</strong>
        Sesi di sini <strong>hanya untuk Tes Full</strong>.
        Tes Mini dan Tes Simulasi tidak memerlukan sesi — langsung bisa diakses user kapan saja.
    </div>
</div>

{{-- Stat singkat --}}
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="fas fa-calendar-alt"></i></div>
        <div><div class="stat-val">{{ $sesi->total() }}</div><div class="stat-label">Total Sesi Full</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="fas fa-circle"></i></div>
        <div><div class="stat-val">{{ $sesi->filter(fn($s) => $s->is_aktif)->count() }}</div><div class="stat-label">Sesi Aktif</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-gold"><i class="fas fa-users"></i></div>
        <div><div class="stat-val">{{ $sesi->sum('peserta_terdaftar') }}</div><div class="stat-label">Total Pendaftar</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-orange"><i class="fas fa-calendar-check"></i></div>
        <div><div class="stat-val">{{ $sesi->filter(fn($s) => \Carbon\Carbon::parse($s->waktu_mulai)->isFuture())->count() }}</div><div class="stat-label">Sesi Mendatang</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-calendar-alt" style="color:var(--accent);margin-right:8px"></i>Daftar Sesi Tes Full</h3>
        <a href="{{ route('admin.sesi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Sesi Baru
        </a>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th width="40">#</th>
                <th>Judul Sesi</th>
                <th width="130">Waktu Mulai</th>
                <th width="130">Waktu Selesai</th>
                <th width="90">Durasi</th>
                <th width="120">Kuota</th>
                <th width="80">Status</th>
                <th width="200">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($sesi as $i => $s)
        <tr>
            <td style="color:var(--text-muted);font-size:12px">{{ $sesi->firstItem() + $i }}</td>
            <td>
                <div style="font-weight:700;font-size:13.5px">{{ $s->judul }}</div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:3px;display:flex;gap:8px">
                    <span><i class="fas fa-headphones" style="font-size:10px"></i> {{ $s->jumlah_soal_listening }}</span>
                    <span><i class="fas fa-pen-nib" style="font-size:10px"></i> {{ $s->jumlah_soal_structure }}</span>
                    <span><i class="fas fa-book-reader" style="font-size:10px"></i> {{ $s->jumlah_soal_reading }}</span>
                    <span style="color:var(--muted)">soal</span>
                </div>
            </td>
            <td>
                <div style="font-size:13px;font-weight:600">{{ \Carbon\Carbon::parse($s->waktu_mulai)->format('d M Y') }}</div>
                <div style="font-size:11px;color:var(--text-muted)">{{ \Carbon\Carbon::parse($s->waktu_mulai)->format('H:i') }} WIB</div>
            </td>
            <td>
                <div style="font-size:13px;font-weight:600">{{ \Carbon\Carbon::parse($s->waktu_selesai)->format('d M Y') }}</div>
                <div style="font-size:11px;color:var(--text-muted)">{{ \Carbon\Carbon::parse($s->waktu_selesai)->format('H:i') }} WIB</div>
            </td>
            <td>
                <span style="font-size:13px">{{ $s->durasi_menit }} mnt</span>
            </td>
            <td>
                @php $pct = $s->kuota_peserta > 0 ? min(100, ($s->peserta_terdaftar / $s->kuota_peserta) * 100) : 0; @endphp
                <div style="font-size:13px;font-weight:600;margin-bottom:5px">
                    {{ $s->peserta_terdaftar }}
                    <span style="color:var(--text-muted);font-weight:400">/ {{ $s->kuota_peserta }}</span>
                </div>
                <div style="height:5px;background:var(--border);border-radius:3px;width:80px">
                    <div style="height:5px;background:{{ $pct >= 90 ? 'var(--red)' : ($pct >= 70 ? 'var(--gold)' : 'var(--green)') }};
                         border-radius:3px;width:{{ $pct }}%"></div>
                </div>
            </td>
            <td>
                @if($s->is_aktif)
                    <span class="badge badge-green" style="font-size:11px">
                        <i class="fas fa-circle" style="font-size:6px"></i> Aktif
                    </span>
                @else
                    <span class="badge badge-gray" style="font-size:11px">Nonaktif</span>
                @endif
            </td>
            <td>
                <div style="display:flex;gap:5px;flex-wrap:wrap">
                    {{-- Toggle Aktif/Nonaktif --}}
                    <form action="{{ route('admin.sesi.toggle', $s->id) }}" method="POST">
                        @csrf @method('PATCH')
                        @if($s->is_aktif)
                        <button class="btn btn-danger btn-sm" title="Nonaktifkan sesi (tes berhenti)">
                            <i class="fas fa-stop-circle"></i> Stop
                        </button>
                        @else
                        <button class="btn btn-success btn-sm" title="Aktifkan sesi (buka akses tes)">
                            <i class="fas fa-play-circle"></i> Aktifkan
                        </button>
                        @endif
                    </form>

                    <a href="{{ route('admin.sesi.show', $s->id) }}" class="btn btn-outline btn-sm" title="Detail &amp; Absensi"><i class="fas fa-clipboard-check"></i> Absensi</a>
                    <a href="{{ route('admin.sesi.edit', $s->id) }}" class="btn btn-warning btn-sm" title="Edit jadwal">
                        <i class="fas fa-pen"></i>
                    </a>

                    <a href="{{ route('admin.pendaftaran.index', ['sesi_id' => $s->id]) }}" class="btn btn-outline btn-sm" title="Lihat pendaftar">
                        <i class="fas fa-users"></i>
                    </a>

                    <form action="{{ route('admin.sesi.destroy', $s->id) }}" method="POST"
                        onsubmit="return confirm('Hapus sesi ini? Semua pendaftaran terkait juga akan terhapus.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline btn-sm" style="border-color:rgba(239,68,68,.3);color:var(--red)" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>Belum ada sesi tes yang dibuat</p>
                    <a href="{{ route('admin.sesi.create') }}" class="btn btn-primary btn-sm" style="margin-top:12px">
                        <i class="fas fa-plus"></i> Buat Sesi Pertama
                    </a>
                </div>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">
        <div class="pg-info">{{ $sesi->total() }} sesi tes full</div>
        {{ $sesi->links() }}
    </div>
</div>

{{-- Panduan aktivasi --}}
<div class="card" style="margin-top:20px">
    <div class="card-header">
        <h3><i class="fas fa-question-circle" style="color:var(--gold);margin-right:8px"></i>Panduan Aktivasi Sesi</h3>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px">
            @foreach([
                ['1','fas fa-plus','var(--accent)','Buat Sesi','Klik "Buat Sesi Baru", isi jadwal, kuota, dan konfigurasi soal. Sistem akan otomatis acak soal dengan Fisher-Yates.'],
                ['2','fas fa-clipboard-check','var(--gold)','Konfirmasi Pendaftar','Tinjau KTM pendaftar di menu Pendaftaran. ACC jika valid warga Polman → akun user diaktifkan otomatis.'],
                ['3','fas fa-play-circle','var(--green)','Aktifkan Saat Tes','Tepat sebelum tes dimulai, klik tombol "Aktifkan" di tabel ini. Tombol Mulai Tes akan muncul di halaman user.'],
                ['4','fas fa-stop-circle','var(--red)','Nonaktifkan Setelah Selesai','Setelah tes selesai, klik "Stop" untuk menutup sesi. Hasil skor sudah otomatis tersimpan.'],
            ] as [$no,$icon,$color,$judul,$desc])
            <div style="text-align:center;padding:16px;background:var(--navy-light);border-radius:10px">
                <div style="width:40px;height:40px;border-radius:50%;background:rgba(59,130,246,.1);
                    color:{{ $color }};display:flex;align-items:center;justify-content:center;
                    font-size:18px;margin:0 auto 12px">
                    <i class="{{ $icon }}"></i>
                </div>
                <div style="font-size:12px;font-weight:700;margin-bottom:6px">{{ $judul }}</div>
                <div style="font-size:11.5px;color:var(--text-muted);line-height:1.6">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
