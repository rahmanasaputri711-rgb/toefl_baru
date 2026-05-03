{{--
  Component: <x-audio-player>
  Props:
    $audioUrl   – resolved URL to audio file
    $playerId   – unique id prefix (e.g. 'soal-3', 'admin-1')
    $mode       – 'full' (1x, no replay) | 'practice' (replay allowed) | 'admin' (always replay)
    $autoplay   – bool, default false
    $label      – optional label text
--}}
@props([
    'audioUrl'  => null,
    'playerId'  => 'ap',
    'mode'      => 'practice',
    'autoplay'  => false,
    'label'     => 'Audio',
])

@if($audioUrl)
<div class="toefl-audio-wrap" id="wrap-{{ $playerId }}">

  @if($label)
  <div class="tap-label">
    <i class="fas fa-headphones-alt"></i> {{ $label }}
    @if($mode === 'full')
      <span class="tap-once-badge">1× saja</span>
    @endif
  </div>
  @endif

  {{-- Player bar — mirip gambar referensi: play ▶ | track biru | 00:00 | vol --}}
  <div class="tap-bar">

    {{-- Play / Pause button --}}
    <button type="button"
            class="tap-play-btn"
            id="btn-{{ $playerId }}"
            data-player="{{ $playerId }}"
            data-mode="{{ $mode }}"
            onclick="tapToggle('{{ $playerId }}')"
            aria-label="Play">
      <span class="tap-play-triangle" id="icon-{{ $playerId }}"></span>
    </button>

    {{-- Progress track --}}
    <div class="tap-track-outer" id="track-{{ $playerId }}"
         onclick="tapSeek(event,'{{ $playerId }}')">
      <div class="tap-track-inner">
        <div class="tap-track-fill" id="fill-{{ $playerId }}" style="width:0%"></div>
      </div>
      <div class="tap-thumb" id="thumb-{{ $playerId }}" style="left:0%"></div>
    </div>

    {{-- Time display --}}
    <span class="tap-time" id="time-{{ $playerId }}">00:00</span>

    {{-- Volume button --}}
    <button type="button" class="tap-vol-btn"
            id="volbtn-{{ $playerId }}"
            onclick="tapToggleMute('{{ $playerId }}')"
            aria-label="Volume">
      <i class="fas fa-volume-up tap-vol-icon" id="volicon-{{ $playerId }}"></i>
    </button>

    {{-- Hidden audio element --}}
    <audio id="aud-{{ $playerId }}"
           preload="auto"
           src="{{ $audioUrl }}"
           data-player="{{ $playerId }}"
           data-mode="{{ $mode }}"
           {{ $autoplay ? 'autoplay' : '' }}
           oncanplay="tapOnCanPlay('{{ $playerId }}')"
           ontimeupdate="tapOnTimeUpdate('{{ $playerId }}')"
           onended="tapOnEnded('{{ $playerId }}')">
    </audio>
  </div>

  <div class="tap-status" id="status-{{ $playerId }}">
    @if($mode === 'full')
      Klik ▶ untuk memutar — hanya 1 kali
    @elseif($autoplay)
      Memuat audio...
    @else
      Klik ▶ untuk memutar
    @endif
  </div>

</div>
@endif
