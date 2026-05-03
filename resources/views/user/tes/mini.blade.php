@extends('layouts.user')
@section('title','Tes Mini')
@section('page-title','Tes Mini')
@section('breadcrumb','Home / Tes Mini')
@section('content')
<div style="max-width:640px">
    <div class="card">
        <div style="background:linear-gradient(135deg,rgba(59,130,246,.15),rgba(139,92,246,.1));
            padding:28px;border-bottom:1px solid var(--border)">
            <div style="display:flex;align-items:center;gap:16px">
                <div style="width:56px;height:56px;border-radius:14px;
                    background:rgba(59,130,246,.2);color:var(--accent);
                    display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                    <i class="fas fa-bolt"></i>
                </div>
                <div>
                    <h2 style="font-size:20px;font-weight:800;margin-bottom:4px">Tes Mini</h2>
                    <p style="font-size:13px;color:var(--muted)">Uji kemampuan cepat — tidak perlu mendaftar, langsung mulai.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="grid-3" style="margin-bottom:20px">
                @foreach([
                    ['fas fa-headphones','var(--orange)',$jumlahSoal['listening'].' soal','Listening'],
                    ['fas fa-pen-nib','var(--gold)',$jumlahSoal['structure'].' soal','Structure'],
                    ['fas fa-book-reader','var(--accent)',$jumlahSoal['reading'].' soal','Reading'],
                ] as [$icon,$color,$val,$lbl])
                <div style="background:var(--surface2);border-radius:10px;padding:14px;text-align:center">
                    <i class="{{ $icon }}" style="color:{{ $color }};font-size:20px;margin-bottom:8px;display:block"></i>
                    <div style="font-size:16px;font-weight:800">{{ $val }}</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px">{{ $lbl }}</div>
                </div>
                @endforeach
            </div>

            <div style="background:var(--surface2);border-radius:10px;padding:16px;margin-bottom:20px">
                <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px">Ketentuan</div>
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13.5px">
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-check-circle" style="color:var(--green);font-size:13px"></i> Tidak perlu daftar, langsung mulai</div>
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-check-circle" style="color:var(--green);font-size:13px"></i> Total {{ $totalSoal }} soal dalam 1 halaman</div>
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-clock" style="color:var(--gold);font-size:13px"></i> Timer 30 menit</div>
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-chart-bar" style="color:var(--accent);font-size:13px"></i> Hasil: persentase benar per kategori</div>
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-times-circle" style="color:var(--muted);font-size:13px"></i> Tidak menggunakan pengacakan soal</div>
                </div>
            </div>

            @if(!$bisaMulai)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Bank soal belum mencukupi untuk semua kategori. Hubungi admin.
                @foreach($cukupSoal as $kat => $ok)
                    @if(!$ok)<div style="margin-top:4px;font-size:12px">• {{ ucfirst($kat) }}: kurang dari 10 soal aktif</div>@endif
                @endforeach
            </div>
            @else
            <form action="{{ route('user.tes.mini.mulai') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-block" style="padding:13px;font-size:15px">
                    <i class="fas fa-bolt"></i> Mulai Tes Mini Sekarang
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
