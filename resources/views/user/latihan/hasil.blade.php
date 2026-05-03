@extends('layouts.user')
@section('title','Hasil Latihan')
@section('page-title','Hasil Latihan '.ucfirst($kategori))
@section('breadcrumb','Home / Praktik / '.ucfirst($kategori).' / Hasil')

@section('content')
<div style="max-width:750px;margin:0 auto">

  {{-- Skor card --}}
  <div class="card" style="margin-bottom:20px">
    <div class="card-body" style="padding:28px">
      <div class="grid-2" style="gap:24px;align-items:center">
        <div style="text-align:center">
          <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;
              letter-spacing:1px;margin-bottom:8px">Nilai {{ ucfirst($kategori) }}</div>
          <div style="font-size:60px;font-weight:900;line-height:1;
              color:{{ $persentase>=80?'#16a34a':($persentase>=60?'#d97706':'#dc2626') }}">
            {{ $persentase }}<span style="font-size:26px">%</span>
          </div>
          <div style="font-size:13.5px;color:var(--muted);margin-top:8px">
            <strong style="color:#16a34a">{{ $jumlahBenar }}</strong> benar /
            <strong style="color:#dc2626">{{ $jumlahSoal - $jumlahBenar }}</strong> salah
            dari {{ $jumlahSoal }} soal
          </div>
        </div>
        <div>
          @php
            $lvl = $persentase>=80 ? ['Sangat Baik!','Pertahankan dan tingkatkan!','#16a34a','#f0fdf4']
                 : ($persentase>=60 ? ['Cukup Baik','Terus latihan, Anda hampir sampai!','#d97706','#fffbeb']
                 : ['Perlu Belajar Lagi','Pelajari materi dan ulangi latihan.','#dc2626','#fff1f2']);
          @endphp
          <div style="background:{{ $lvl[3] }};border-radius:10px;padding:18px;margin-bottom:14px">
            <div style="font-size:16px;font-weight:700;color:{{ $lvl[2] }};margin-bottom:5px">{{ $lvl[0] }}</div>
            <div style="font-size:13px;color:{{ $lvl[2] }};opacity:.85">{{ $lvl[1] }}</div>
          </div>
          <div style="display:flex;gap:8px">
            <form action="{{ route('user.latihan.reset', $kategori) }}" method="POST" style="flex:1">
              @csrf
              <button type="submit" class="btn btn-primary btn-block" style="padding:10px">
                <i class="fas fa-redo"></i> Ulangi
              </button>
            </form>
            <a href="{{ route('user.latihan.index') }}" class="btn btn-outline" style="flex:1;justify-content:center;padding:10px">
              <i class="fas fa-th"></i> Kategori Lain
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Pembahasan --}}
  <div class="card">
    <div class="card-header">
      <h3>Pembahasan Soal</h3>
      <div style="display:flex;gap:7px">
        <span class="badge badge-green">{{ $jumlahBenar }} Benar</span>
        <span class="badge badge-red">{{ $jumlahSoal - $jumlahBenar }} Salah</span>
      </div>
    </div>

    @foreach($review as $i => $item)
    @php $soal = $item['soal']; @endphp
    <div style="padding:20px 22px;border-bottom:1px solid var(--border);
        background:{{ $item['is_benar']?'#fafff8':'#fff8f8' }}">

      <div style="display:flex;align-items:center;gap:9px;margin-bottom:12px">
        <div style="width:28px;height:28px;border-radius:7px;flex-shrink:0;
            background:{{ $item['is_benar']?'#dcfce7':'#fee2e2' }};
            color:{{ $item['is_benar']?'#15803d':'#dc2626' }};
            display:flex;align-items:center;justify-content:center;font-size:13px">
          <i class="fas fa-{{ $item['is_benar']?'check':'times' }}"></i>
        </div>
        <span style="font-size:13px;font-weight:700;color:var(--muted)">Soal {{ $i+1 }}</span>
        <span class="badge {{ $item['is_benar']?'badge-green':'badge-red' }}">
          {{ $item['is_benar']?'✓ Benar':'✗ Salah' }}
        </span>
      </div>

      {{-- Audio review --}}
      @if($soal->audio_url)
      @php
          $url = \App\Services\AudioService::resolveUrl($soal->audio_url);
          $pid = 'rev-' . $soal->id;
      @endphp
      <div class="toefl-audio-wrap" style="margin-bottom:12px">
        <div class="tap-label"><i class="fas fa-headphones-alt"></i> Audio</div>
        <div class="tap-bar">
          <button type="button" class="tap-play-btn" id="btn-{{ $pid }}" onclick="tapToggle('{{ $pid }}')" aria-label="Play">
            <span class="tap-play-triangle" id="icon-{{ $pid }}"></span>
          </button>
          <div class="tap-track-outer" id="track-{{ $pid }}" onclick="tapSeek(event,'{{ $pid }}')">
            <div class="tap-track-inner">
              <div class="tap-track-fill" id="fill-{{ $pid }}" style="width:0%"></div>
            </div>
            <div class="tap-thumb" id="thumb-{{ $pid }}" style="left:0%"></div>
          </div>
          <span class="tap-time" id="time-{{ $pid }}">00:00</span>
          <button type="button" class="tap-vol-btn" onclick="tapToggleMute('{{ $pid }}')">
            <i class="fas fa-volume-up tap-vol-icon" id="volicon-{{ $pid }}"></i>
          </button>
          <audio id="aud-{{ $pid }}" preload="auto" src="{{ $url }}"
            data-mode="practice"
            oncanplay="tapOnCanPlay('{{ $pid }}')"
            ontimeupdate="tapOnTimeUpdate('{{ $pid }}')"
            onended="tapOnEnded('{{ $pid }}')">
          </audio>
        </div>
        <div class="tap-status" id="status-{{ $pid }}">Klik ▶ untuk memutar</div>
      </div>
      @endif

      <p style="font-size:14.5px;font-weight:600;color:var(--navy);margin-bottom:14px;line-height:1.65">
        {{ $soal->pertanyaan }}
      </p>

      {{-- Pilihan dengan highlight --}}
      @foreach(['a','b','c','d'] as $opt)
      @php
        $isBenar   = $soal->jawaban_benar === $opt;
        $isDipilih = $item['jawaban_user'] === $opt;
        $bg     = $isBenar ? '#f0fdf4' : ($isDipilih && !$isBenar ? '#fff1f2' : '#f8fafc');
        $border = $isBenar ? '#bbf7d0' : ($isDipilih && !$isBenar ? '#fecdd3' : '#e2e8f0');
        $badgeBg= $isBenar ? '#16a34a' : ($isDipilih && !$isBenar ? '#dc2626' : '#94a3b8');
      @endphp
      <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 13px;border-radius:8px;
          background:{{ $bg }};border:1px solid {{ $border }};margin-bottom:6px">
        <div style="width:24px;height:24px;border-radius:5px;flex-shrink:0;
            background:{{ $badgeBg }};color:#fff;display:flex;align-items:center;
            justify-content:center;font-size:11px;font-weight:700">{{ strtoupper($opt) }}</div>
        <span style="font-size:13.5px;flex:1">{{ $soal->{'pilihan_'.$opt} }}</span>
        @if($isBenar)<i class="fas fa-check-circle" style="color:#16a34a;margin-top:2px"></i>@endif
        @if($isDipilih && !$isBenar)<i class="fas fa-times-circle" style="color:#dc2626;margin-top:2px"></i>@endif
      </div>
      @endforeach

      @if(!$item['is_benar'])
      <div style="margin-top:8px;font-size:12.5px;color:var(--muted)">
        Jawaban Anda: <strong style="color:#dc2626">{{ strtoupper($item['jawaban_user'] ?? '-') }}</strong>
        &nbsp;·&nbsp; Benar: <strong style="color:#16a34a">{{ strtoupper($item['jawaban_benar']) }}</strong>
      </div>
      @endif

      @if($soal->pembahasan)
      <div style="margin-top:10px;padding:12px 14px;background:#eff6ff;border-left:4px solid var(--blue);
          border-radius:0 8px 8px 0;font-size:13px;color:#1e40af;line-height:1.65">
        <strong><i class="fas fa-lightbulb"></i> Pembahasan:</strong> {{ $soal->pembahasan }}
      </div>
      @endif
    </div>
    @endforeach
  </div>

</div>
@endsection
