@extends('layouts.user')
@section('title','Latihan '.ucfirst($kategori))
@section('page-title','Latihan '.ucfirst($kategori))
@section('breadcrumb','Home / Praktik / '.ucfirst($kategori))

@push('styles')
<style>
.soal-card{background:#fff;border:1.5px solid var(--border);border-radius:14px;padding:26px}
.passage-box{background:#f8fafc;border-left:4px solid var(--blue);padding:14px 16px;
  border-radius:0 10px 10px 0;margin-bottom:16px;font-size:13.5px;line-height:1.8;color:#374151}
.pilihan-lbl{display:flex;align-items:flex-start;gap:11px;padding:12px 15px;border-radius:9px;
  border:1.5px solid var(--border);cursor:pointer;transition:all .13s;margin-bottom:8px;background:#fff}
.pilihan-lbl:hover{border-color:var(--blue);background:var(--blue-light)}
.pilihan-lbl.selected{border-color:var(--blue);background:var(--blue-light)}
.opt-badge{width:27px;height:27px;border-radius:6px;flex-shrink:0;display:flex;align-items:center;
  justify-content:center;font-size:11.5px;font-weight:700;background:var(--bg);
  color:var(--muted);border:1px solid var(--border)}
.pilihan-lbl.selected .opt-badge{background:var(--blue);color:#fff;border-color:var(--blue)}
.prog-wrap{height:5px;background:var(--bg);border-radius:3px;margin-bottom:18px}
.prog-fill{height:5px;background:var(--blue);border-radius:3px;transition:width .3s}
.nav-pill{display:inline-flex;align-items:center;gap:7px;padding:10px 22px;border-radius:9px;
  font-size:14px;font-weight:600;cursor:pointer;border:none;font-family:inherit;transition:all .13s;text-decoration:none}
</style>
@endpush

@section('content')
<div style="max-width:760px;margin:0 auto">

  {{-- Header --}}
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px">
    <div style="display:flex;align-items:center;gap:10px">
      <a href="{{ route('user.latihan.index') }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Kategori
      </a>
      <span class="badge {{ $kategori=='listening'?'badge-orange':($kategori=='structure'?'badge-gold':'badge-blue') }}"
            style="font-size:12.5px;padding:5px 13px">
        <i class="fas fa-{{ $kategori=='listening'?'headphones-alt':($kategori=='structure'?'pen-nib':'book-open') }}"></i>
        {{ ucfirst($kategori) }}
      </span>
    </div>
    <div style="font-size:14px;font-weight:600;color:var(--navy)">
      Soal <span style="color:var(--blue)">{{ $nomorSoal }}</span> / {{ $total }}
    </div>
  </div>

  {{-- Progress --}}
  <div class="prog-wrap">
    <div class="prog-fill" style="width:{{ round(($nomorSoal-1)/$total*100) }}%"></div>
  </div>

  {{-- Soal card --}}
  <div class="soal-card" style="margin-bottom:14px">

    @if($soal->passage_teks)
    <div class="passage-box">{{ $soal->passage_teks }}</div>
    @endif

    @if($soal->audio_url)
    @php
        $audioUrl = \App\Services\AudioService::resolveUrl($soal->audio_url);
        $pid = 'lat-' . $soal->id;
    @endphp
    <div class="toefl-audio-wrap">
      <div class="tap-label">
        <i class="fas fa-headphones-alt"></i> Audio Listening
        <span style="font-size:10px;color:#10b981;font-style:normal;font-weight:600;margin-left:4px">● Dapat diputar ulang</span>
      </div>
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
        <audio id="aud-{{ $pid }}" preload="auto" src="{{ $audioUrl }}"
          data-mode="practice"
          oncanplay="tapOnCanPlay('{{ $pid }}')"
          ontimeupdate="tapOnTimeUpdate('{{ $pid }}')"
          onended="tapOnEnded('{{ $pid }}')">
        </audio>
      </div>
      <div class="tap-status" id="status-{{ $pid }}">Klik ▶ untuk memutar</div>
    </div>
    @endif

    <p style="font-size:15px;font-weight:600;color:var(--navy);line-height:1.65;margin-bottom:18px">
      {{ $soal->pertanyaan }}
    </p>

    <form action="{{ route('user.latihan.simpan', $kategori) }}" method="POST" id="soal-form">
      @csrf
      <input type="hidden" name="soal_id"    value="{{ $soal->id }}">
      <input type="hidden" name="nomor_soal" value="{{ $nomorSoal }}">

      @foreach(['a','b','c','d'] as $opt)
      <label class="pilihan-lbl {{ ($jawaban[$soal->id] ?? '') === $opt ? 'selected' : '' }}"
             id="lbl-{{ $opt }}">
        <input type="radio" name="jawaban" value="{{ $opt }}" style="display:none"
          {{ ($jawaban[$soal->id] ?? '') === $opt ? 'checked' : '' }}
          onchange="selectOpt('{{ $opt }}')">
        <div class="opt-badge" id="badge-{{ $opt }}">{{ strtoupper($opt) }}</div>
        <span style="font-size:14px;line-height:1.5">{{ $soal->{'pilihan_'.$opt} }}</span>
      </label>
      @endforeach

      {{-- Navigation --}}
      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:22px">
        @if($nomorSoal > 1)
        <a href="{{ route('user.latihan.kerjakan', ['kategori'=>$kategori,'no'=>$nomorSoal-1]) }}"
           class="nav-pill" style="background:var(--bg);color:var(--muted);border:1.5px solid var(--border)">
          <i class="fas fa-chevron-left"></i> Sebelumnya
        </a>
        @else
        <div></div>
        @endif

        @if($nomorSoal < $total)
        <button type="submit" class="nav-pill" style="background:var(--blue);color:#fff">
          Berikutnya <i class="fas fa-chevron-right"></i>
        </button>
        @else
        <button type="submit" name="selesai" value="1" class="nav-pill" style="background:#16a34a;color:#fff">
          <i class="fas fa-check-circle"></i> Selesaikan Latihan
        </button>
        @endif
      </div>
    </form>
  </div>

  {{-- Navigator mini --}}
  <div style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:14px 16px">
    <div style="font-size:10.5px;font-weight:700;color:var(--muted);text-transform:uppercase;
        letter-spacing:.8px;margin-bottom:10px">Navigator Soal</div>
    <div style="display:flex;gap:5px;flex-wrap:wrap">
      @php
        $soalIds = \App\Models\BankSoal::where('kategori',$kategori)->where('is_aktif',1)->orderBy('id')->pluck('id')->values();
      @endphp
      @for($i = 1; $i <= $total; $i++)
      @php $sid = $soalIds[$i-1] ?? null; $isDijawab = $sid && isset($jawaban[$sid]); @endphp
      <a href="{{ route('user.latihan.kerjakan', ['kategori'=>$kategori,'no'=>$i]) }}"
         style="width:30px;height:30px;border-radius:6px;display:flex;align-items:center;
         justify-content:center;font-size:11.5px;font-weight:600;text-decoration:none;transition:all .13s;
         {{ $i==$nomorSoal ? 'background:var(--blue);color:#fff;' : ($isDijawab ? 'background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;' : 'background:var(--bg);color:var(--muted);border:1px solid var(--border);') }}">
        {{ $i }}
      </a>
      @endfor
    </div>
    <div style="display:flex;gap:12px;margin-top:10px;font-size:11px;color:var(--muted)">
      <span><span style="display:inline-block;width:12px;height:12px;background:var(--blue);border-radius:3px;margin-right:4px;vertical-align:middle"></span>Aktif</span>
      <span><span style="display:inline-block;width:12px;height:12px;background:#dcfce7;border:1px solid #bbf7d0;border-radius:3px;margin-right:4px;vertical-align:middle"></span>Dijawab</span>
      <span><span style="display:inline-block;width:12px;height:12px;background:var(--bg);border:1px solid var(--border);border-radius:3px;margin-right:4px;vertical-align:middle"></span>Belum</span>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
function selectOpt(opt) {
  ['a','b','c','d'].forEach(o => {
    document.getElementById('lbl-'+o)?.classList.remove('selected');
    const b = document.getElementById('badge-'+o);
    if(b){ b.style.background=''; b.style.color=''; b.style.borderColor=''; }
  });
  document.getElementById('lbl-'+opt)?.classList.add('selected');
  document.querySelector(`input[value="${opt}"]`).checked = true;
}
</script>
@endpush
