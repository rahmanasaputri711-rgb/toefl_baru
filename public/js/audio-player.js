/**
 * TOEFL Audio Player — Global Engine
 * Mendukung 3 mode:
 *   'full'     — Tes Full: 1x play, setelah play semua kontrol di-disable
 *   'practice' — Latihan/Mini/Simulasi: replay bebas, toggle play/pause
 *   'admin'    — Admin preview: replay bebas, full controls termasuk seek
 *
 * Public API:
 *   tapToggle(id)          — play/pause toggle
 *   tapSeek(event, id)     — seek ke posisi klik
 *   tapToggleMute(id)      — mute/unmute
 *   tapTriggerAutoPlay(id) — trigger autoplay (untuk Listening tes full)
 *   tapOnCanPlay(id)       — callback saat audio siap
 *   tapOnTimeUpdate(id)    — callback update progress
 *   tapOnEnded(id)         — callback saat audio selesai
 */

(function(window) {
  'use strict';

  // State per player: {id: {played, canPlay}}
  const _state = {};

  function _get(id) {
    if (!_state[id]) _state[id] = { played: false, canPlay: false };
    return _state[id];
  }

  function _el(id, prefix) {
    return document.getElementById(prefix + '-' + id);
  }

  function _fmtTime(s) {
    if (!isFinite(s) || s < 0) s = 0;
    const m = Math.floor(s / 60);
    const sec = Math.floor(s % 60);
    return String(m).padStart(2,'0') + ':' + String(sec).padStart(2,'0');
  }

  function _setStatus(id, text, cls) {
    const el = _el(id, 'status');
    if (!el) return;
    el.textContent = text;
    el.className = 'tap-status ' + (cls || '');
  }

  function _setPlayIcon(id, isPlaying) {
    const btn = _el(id, 'btn');
    if (!btn) return;
    if (isPlaying) btn.classList.add('playing');
    else           btn.classList.remove('playing');
  }

  function _updateProgress(id, pct) {
    const fill  = _el(id, 'fill');
    const thumb = _el(id, 'thumb');
    if (fill)  fill.style.width = pct + '%';
    if (thumb) thumb.style.left = pct + '%';
  }

  // ── Public: canplay callback ────────────────────────────────────
  window.tapOnCanPlay = function(id) {
    _get(id).canPlay = true;
    const aud  = _el(id, 'aud');
    const time = _el(id, 'time');
    if (aud && time && aud.duration)
      time.textContent = _fmtTime(aud.duration);
  };

  // ── Public: timeupdate callback ─────────────────────────────────
  window.tapOnTimeUpdate = function(id) {
    const aud = _el(id, 'aud');
    if (!aud || !aud.duration) return;
    const pct = (aud.currentTime / aud.duration) * 100;
    _updateProgress(id, pct);
    const time = _el(id, 'time');
    if (time) time.textContent = _fmtTime(aud.currentTime);
  };

  // ── Public: ended callback ──────────────────────────────────────
  window.tapOnEnded = function(id) {
    _setPlayIcon(id, false);
    const aud  = _el(id, 'aud');
    const mode = aud ? aud.dataset.mode : 'practice';

    if (mode === 'full') {
      _setStatus(id, '✓ Audio selesai — tidak dapat diputar ulang', 'done');
      // Disable semua kontrol
      const btn = _el(id, 'btn');
      if (btn) btn.disabled = true;
      const bar = document.getElementById('wrap-' + id)?.querySelector('.tap-bar');
      if (bar) bar.classList.add('no-interact');
    } else {
      _setStatus(id, '✓ Selesai — klik ▶ untuk putar ulang');
      _updateProgress(id, 100);
    }

    // Dispatch custom event sehingga halaman lain bisa listen
    document.dispatchEvent(new CustomEvent('tapAudioEnded', { detail: { playerId: id } }));
  };

  // ── Public: toggle play/pause ───────────────────────────────────
  window.tapToggle = function(id) {
    const aud  = _el(id, 'aud');
    const btn  = _el(id, 'btn');
    if (!aud) return;

    const mode  = aud.dataset.mode || 'practice';
    const state = _get(id);

    // Mode full: setelah pertama kali play, tombol disabled
    if (mode === 'full' && state.played && aud.ended) return;

    if (aud.paused) {
      // PLAY
      aud.load();
      const p = aud.play();
      if (p !== undefined) {
        p.then(() => {
          if (mode === 'full') {
            state.played = true;
            // Disable seek setelah play dimulai
            const track = _el(id, 'track');
            if (track) track.onclick = null;
          }
          _setPlayIcon(id, true);
          _setStatus(id, 'Sedang diputar...', 'playing');
        }).catch(() => {
          _setStatus(id, 'Gagal memutar. Klik lagi.', 'error');
        });
      }
    } else {
      // PAUSE (hanya di mode practice/admin)
      if (mode === 'full') return; // full: tidak bisa pause
      aud.pause();
      _setPlayIcon(id, false);
      _setStatus(id, 'Dijeda — klik ▶ untuk lanjutkan');
    }
  };

  // ── Public: seek ────────────────────────────────────────────────
  window.tapSeek = function(event, id) {
    const aud  = _el(id, 'aud');
    const track = _el(id, 'track');
    if (!aud || !track) return;

    const mode  = aud.dataset.mode || 'practice';
    const state = _get(id);

    // Mode full: disable seek setelah audio dimainkan
    if (mode === 'full' && state.played) return;

    if (!aud.duration) return;
    const rect = track.getBoundingClientRect();
    const pct  = Math.max(0, Math.min(1, (event.clientX - rect.left) / rect.width));
    aud.currentTime = pct * aud.duration;
    if (aud.paused && mode !== 'full') {
      _setStatus(id, 'Dijeda — klik ▶ untuk lanjutkan');
    }
  };

  // ── Public: mute toggle ─────────────────────────────────────────
  window.tapToggleMute = function(id) {
    const aud  = _el(id, 'aud');
    const icon = _el(id, 'volicon');
    if (!aud) return;
    aud.muted = !aud.muted;
    if (icon) {
      icon.className = aud.muted
        ? 'fas fa-volume-mute tap-vol-icon'
        : 'fas fa-volume-up tap-vol-icon';
    }
  };

  // ── Public: trigger autoplay (Listening section) ────────────────
  window.tapTriggerAutoPlay = function(id) {
    const aud   = _el(id, 'aud');
    const state = _get(id);
    if (!aud || state.played) return;

    aud.load();
    const p = aud.play();
    if (p !== undefined) {
      p.then(() => {
        state.played = true;
        _setPlayIcon(id, true);
        _setStatus(id, 'Sedang diputar...', 'playing');
        // Disable play button (mode full)
        const btn = _el(id, 'btn');
        if (btn && aud.dataset.mode === 'full') {
          btn.disabled = true;
          const track = _el(id, 'track');
          if (track) track.onclick = null;
        }
      }).catch(() => {
        state.played = false;
        _setStatus(id, '⚠ Klik ▶ untuk memutar audio', 'error');
      });
    }
  };

  // ── Public: check if played (for full mode guard) ───────────────
  window.tapIsPlayed = function(id) {
    return _get(id).played;
  };

  // ── Public: get state (for external checks) ──────────────────────
  window._tapState = function(id) {
    return _state[id] || null;
  };

  // ── Public: force-load audio ─────────────────────────────────────
  window.tapLoad = function(id) {
    const aud = _el(id, 'aud');
    if (aud) aud.load();
  };

})(window);
