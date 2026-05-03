@extends('layouts.user')
@section('title','Tes Simulasi')
@section('page-title','Tes Simulasi')
@section('breadcrumb','Home / Tes Simulasi')
@section('content')
<div style="max-width:680px">
    <div class="card">
        <div style="background:linear-gradient(135deg,rgba(139,92,246,.15),rgba(59,130,246,.1));
            padding:28px;border-bottom:1px solid var(--border)">
            <div style="display:flex;align-items:center;gap:16px">
                <div style="width:56px;height:56px;border-radius:14px;
                    background:rgba(139,92,246,.2);color:var(--purple);
                    display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
                    <i class="fas fa-flask"></i>
                </div>
                <div>
                    <h2 style="font-size:20px;font-weight:800;margin-bottom:4px">Tes Simulasi</h2>
                    <p style="font-size:13px;color:var(--muted)">Simulasi TOEFL ITP 3 section dengan timer — estimasi skor resmi.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="grid-3" style="margin-bottom:20px">
                @foreach([
                    ['fas fa-headphones','var(--orange)','listening',20,'~20 mnt'],
                    ['fas fa-pen-nib','var(--gold)','structure',20,'~15 mnt'],
                    ['fas fa-book-reader','var(--accent)','reading',20,'~30 mnt'],
                ] as [$icon,$color,$kat,$jml,$waktu])
                <div style="background:var(--surface2);border-radius:10px;padding:16px;text-align:center;
                    border:1px solid {{ ($cukupSoal[$kat] ?? false) ? 'rgba(16,185,129,.2)':'rgba(239,68,68,.2)' }}">
                    <i class="{{ $icon }}" style="color:{{ $color }};font-size:20px;margin-bottom:8px;display:block"></i>
                    <div style="font-size:16px;font-weight:800">{{ $jumlahSoal[$kat] ?? $jml }} soal</div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px">{{ ucfirst($kat) }}</div>
                    <div style="font-size:11px;color:var(--muted)">{{ $waktu }}</div>
                    @if(!($cukupSoal[$kat] ?? true))
                    <div style="font-size:10px;color:var(--red);margin-top:4px"><i class="fas fa-exclamation-circle"></i> Soal kurang</div>
                    @endif
                </div>
                @endforeach
            </div>

            <div style="background:var(--surface2);border-radius:10px;padding:16px;margin-bottom:18px">
                <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:12px">Ketentuan</div>
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13.5px">
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-check-circle" style="color:var(--green);font-size:13px"></i> Tidak perlu daftar, langsung mulai</div>
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-check-circle" style="color:var(--green);font-size:13px"></i> 3 section terpisah dengan timer masing-masing</div>
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-check-circle" style="color:var(--green);font-size:13px"></i> Section terkunci setelah waktu habis atau lanjut</div>
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-star" style="color:var(--gold);font-size:13px"></i> Hasil: <strong>estimasi skor TOEFL ITP</strong> (310–677)</div>
                    <div style="display:flex;align-items:center;gap:10px"><i class="fas fa-times-circle" style="color:var(--muted);font-size:13px"></i> Tidak menggunakan pengacakan soal</div>
                </div>
            </div>

            <div style="background:rgba(139,92,246,.06);border:1px solid rgba(139,92,246,.2);border-radius:8px;padding:12px 14px;margin-bottom:18px;font-size:13px;color:#c4b5fd">
                <i class="fas fa-info-circle"></i>
                Simulasi dapat diakses kapan saja tanpa persetujuan admin, berbeda dengan <strong>Tes Full</strong> yang memerlukan pendaftaran resmi.
            </div>

            @if(in_array(false, $cukupSoal, true))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                Bank soal belum mencukupi. Hubungi admin untuk menambah soal.
            </div>
            @else
            <form action="{{ route('user.tes.simulasi.mulai') }}" method="POST">
                @csrf
                <input type="hidden" name="section" value="listening">
                <button type="submit" class="btn btn-primary btn-block" style="padding:13px;font-size:15px"
                    onclick="this.disabled=true;this.innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Menyiapkan...';this.form.submit()">
                    <i class="fas fa-play-circle"></i> Mulai Simulasi Sekarang
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
