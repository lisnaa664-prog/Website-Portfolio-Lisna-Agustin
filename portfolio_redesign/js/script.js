/**
 * PORTFOLIO - script.js
 * Public-facing website JavaScript with real audio support
 */

/* ============================================================
   1. NAVBAR
   ============================================================ */
(function initNavbar() {
  const navbar   = document.querySelector('.navbar');
  const toggle   = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  const navCta   = document.querySelector('.nav-cta');
  if (!navbar) return;

  let ticking = false;
  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        navbar.classList.toggle('scrolled', window.scrollY > 20);
        ticking = false;
      });
      ticking = true;
    }
  });

  if (toggle && navLinks) {
    toggle.addEventListener('click', () => {
      const isOpen = navLinks.classList.toggle('open');
      if (navCta) navCta.classList.toggle('open');
      const spans = toggle.querySelectorAll('span');
      spans[0] && (spans[0].style.transform = isOpen ? 'translateY(7px) rotate(45deg)' : '');
      spans[1] && (spans[1].style.opacity   = isOpen ? '0' : '1');
      spans[2] && (spans[2].style.transform = isOpen ? 'translateY(-7px) rotate(-45deg)' : '');
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });
  }

  document.querySelectorAll('.nav-links a, .nav-cta a').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768 && navLinks?.classList.contains('open')) {
        navLinks.classList.remove('open');
        navCta?.classList.remove('open');
        document.body.style.overflow = '';
        const spans = toggle?.querySelectorAll('span');
        if (spans) { spans[0].style.transform=''; spans[1].style.opacity=''; spans[2].style.transform=''; }
      }
    });
  });

  // Active link
  const cur = window.location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.nav-links a').forEach(link => {
    const href = link.getAttribute('href');
    if (href && (href === cur || href.endsWith('/' + cur))) link.classList.add('active');
  });
})();

/* ============================================================
   2. SCROLL ANIMATIONS
   ============================================================ */
(function initScrollAnim() {
  const els = document.querySelectorAll('.fade-up');
  if (!els.length) return;
  const obs = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) {
        setTimeout(() => e.target.classList.add('visible'), i * 80);
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
  els.forEach(el => obs.observe(el));
})();

/* ============================================================
   3. SKILL BAR ANIMATION
   ============================================================ */
(function initSkillBars() {
  const bars = document.querySelectorAll('.skill-fill');
  if (!bars.length) return;
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const bar = e.target;
        setTimeout(() => { bar.style.width = (bar.dataset.width || 0) + '%'; }, 150);
        obs.unobserve(bar);
      }
    });
  }, { threshold: 0.3 });
  bars.forEach(bar => obs.observe(bar));
})();

/* ============================================================
   4. TYPING ANIMATION
   ============================================================ */
(function initTyping() {
  const el = document.getElementById('typingText');
  if (!el) return;
  const base  = el.textContent.trim();
  const words = [base, 'Web Developer', 'UI/UX Enthusiast', 'Problem Solver', 'Mahasiswa Informatika'];
  let wi = 0, ci = words[0].length, del = false;

  function type() {
    const cur = words[wi];
    el.textContent = cur.substring(0, ci);
    const speed = del ? 55 : 115;
    if (!del && ci === cur.length) { setTimeout(() => { del = true; type(); }, 2200); return; }
    if (del && ci === 0)           { del = false; wi = (wi + 1) % words.length; setTimeout(type, 350); return; }
    ci += del ? -1 : 1;
    setTimeout(type, speed);
  }
  // start after short delay
  setTimeout(() => { del = true; type(); }, 1500);
})();

/* ============================================================
   5. COUNT-UP ANIMATION
   ============================================================ */
(function initCountUp() {
  const nums = document.querySelectorAll('.stat-number[data-target]');
  if (!nums.length) return;
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const el = e.target;
      const target = parseFloat(el.dataset.target);
      const suffix = el.dataset.suffix || '';
      const isDecimal = String(target).includes('.');
      let count = 0, steps = 60, inc = target / steps;
      const id = setInterval(() => {
        count = Math.min(count + inc, target);
        el.textContent = (isDecimal ? count.toFixed(2) : Math.floor(count)) + suffix;
        if (count >= target) clearInterval(id);
      }, 25);
      obs.unobserve(el);
    });
  }, { threshold: 0.5 });
  nums.forEach(el => obs.observe(el));
})();

/* ============================================================
   6. CONTACT FORM VALIDATION
   ============================================================ */
(function initContactForm() {
  const form = document.getElementById('contactForm');
  if (!form) return;
  const rules = {
    nama:  { required: true, message: 'Nama tidak boleh kosong.' },
    email: { required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Format email tidak valid.' },
    pesan: { required: true, minLength: 10, message: 'Pesan minimal 10 karakter.' },
  };

  const showErr = (input, msg) => {
    const err = input.closest('.form-group')?.querySelector('.form-error');
    if (err) { err.textContent = msg; err.style.display = 'block'; }
    input.classList.add('is-invalid');
  };
  const hideErr = (input) => {
    const err = input.closest('.form-group')?.querySelector('.form-error');
    if (err) err.style.display = 'none';
    input.classList.remove('is-invalid');
  };
  const validate = (input) => {
    const r = rules[input.id]; if (!r) return true;
    const v = input.value.trim();
    if (r.required && !v) { showErr(input, r.message); return false; }
    if (r.pattern && v && !r.pattern.test(v)) { showErr(input, r.message); return false; }
    if (r.minLength && v && v.length < r.minLength) { showErr(input, r.message); return false; }
    hideErr(input); return true;
  };
  const debounce = (fn, d = 300) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), d); }; };

  form.querySelectorAll('input,textarea').forEach(inp => {
    inp.addEventListener('blur', () => validate(inp));
    inp.addEventListener('input', debounce(() => { if (inp.classList.contains('is-invalid')) validate(inp); }));
  });

  form.addEventListener('submit', (e) => {
    let ok = true;
    form.querySelectorAll('input,textarea').forEach(inp => { if (!validate(inp)) ok = false; });
    if (!ok) {
      e.preventDefault();
      form.querySelector('.is-invalid')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
      const btn = form.querySelector('[type="submit"]');
      if (btn) { btn.disabled = true; btn.textContent = 'Mengirim...'; }
    }
  });
})();

/* ============================================================
   7. AUTH FORMS
   ============================================================ */
(function initAuthForms() {
  // Login
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      let ok = true;
      ['loginEmail','loginPassword'].forEach(id => {
        const el = document.getElementById(id);
        if (el && !el.value.trim()) { el.classList.add('is-invalid'); ok = false; }
        else if (el) el.classList.remove('is-invalid');
      });
      if (!ok) e.preventDefault();
    });
  }

  // Register
  const regForm = document.getElementById('registerForm');
  if (!regForm) return;
  const pass = document.getElementById('regPassword');
  const conf = document.getElementById('regConfirm');

  if (pass) {
    pass.addEventListener('input', function() {
      const v = this.value;
      const segs = document.querySelectorAll('.strength-segment');
      const text = document.querySelector('.strength-text');
      let score = 0;
      if (v.length >= 8) score++;
      if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
      if (/[0-9]/.test(v)) score++;
      if (/[^A-Za-z0-9]/.test(v)) score++;
      const levels = [{cls:'weak',label:'Lemah'},{cls:'medium',label:'Sedang'},{cls:'strong',label:'Kuat'},{cls:'strong',label:'Sangat Kuat'}];
      const lvl = levels[Math.min(score,4)-1] || levels[0];
      segs.forEach((s,i) => { s.className = 'strength-segment'; if (i < score) s.classList.add('active', lvl.cls); });
      if (text) text.textContent = v ? `Kekuatan: ${lvl.label}` : '';
    });
  }

  regForm.addEventListener('submit', function(e) {
    let ok = true;
    ['regName','regEmail','regPassword','regConfirm'].forEach(id => {
      const el = document.getElementById(id);
      if (el && !el.value.trim()) { el.classList.add('is-invalid'); ok = false; }
      else if (el) el.classList.remove('is-invalid');
    });
    if (pass && conf && pass.value !== conf.value) { conf.classList.add('is-invalid'); ok = false; }
    if (!ok) e.preventDefault();
  });
})();

/* ============================================================
   8. PASSWORD TOGGLE
   ============================================================ */
document.querySelectorAll('.toggle-password').forEach(btn => {
  btn.addEventListener('click', function() {
    const target = document.getElementById(this.dataset.target);
    if (!target) return;
    const isText = target.type === 'text';
    target.type = isText ? 'password' : 'text';
    this.textContent = isText ? '👁️' : '🙈';
  });
});

/* ============================================================
   9. BACK TO TOP
   ============================================================ */
(function() {
  const btn = document.getElementById('backToTop');
  if (!btn) return;
  window.addEventListener('scroll', () => {
    const show = window.scrollY > 400;
    btn.style.opacity    = show ? '1' : '0';
    btn.style.transform  = show ? 'translateY(0)' : 'translateY(16px)';
    btn.style.visibility = show ? 'visible' : 'hidden';
  });
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();

/* ============================================================
   10. ALERTS AUTO-DISMISS
   ============================================================ */
document.querySelectorAll('.alert').forEach(alert => {
  setTimeout(() => {
    alert.style.transition = 'opacity 0.4s, transform 0.3s';
    alert.style.opacity    = '0';
    alert.style.transform  = 'translateY(-8px)';
    setTimeout(() => alert.remove(), 400);
  }, 4500);
});

/* ============================================================
   11. MUSIC PLAYER (supports real audio from DB)
   ============================================================ */
(function initMusicPlayer() {
  const cards     = document.querySelectorAll('.music-card');
  const miniPlayer = document.getElementById('miniPlayer');
  if (!cards.length) return;

  let currentAudio = null;  // HTMLAudioElement
  let currentCardId = null;
  let isPlaying = false;

  /* ---- Helper: toast ---- */
  function toast(msg) {
    let t = document.getElementById('musicToast');
    if (!t) {
      t = document.createElement('div');
      t.id = 'musicToast';
      Object.assign(t.style, {
        position:'fixed', bottom:'140px', right:'24px', zIndex:'9999',
        background:'rgba(15,15,26,0.95)', border:'1px solid rgba(168,85,247,0.3)',
        color:'#f1f5f9', padding:'12px 20px', borderRadius:'12px',
        fontSize:'0.82rem', fontFamily:'DM Sans,sans-serif',
        backdropFilter:'blur(16px)', maxWidth:'280px',
        boxShadow:'0 8px 32px rgba(0,0,0,0.5)',
        transform:'translateY(8px)', opacity:'0',
        transition:'all 0.3s cubic-bezier(0.34,1.56,0.64,1)',
        pointerEvents:'none',
      });
      document.body.appendChild(t);
    }
    t.textContent = msg;
    requestAnimationFrame(() => { t.style.opacity='1'; t.style.transform='translateY(0)'; });
    clearTimeout(t._timeout);
    t._timeout = setTimeout(() => { t.style.opacity='0'; t.style.transform='translateY(8px)'; }, 3500);
  }

  /* ---- Helper: set visual state ---- */
  function setCardState(card, playing) {
    const playBtn = card.querySelector('.ctrl-btn-play');
    const eq      = card.querySelector('.now-playing-bar');
    if (playing) {
      card.classList.add('playing');
      if (playBtn) playBtn.innerHTML = '⏸';
      if (eq) eq.classList.remove('paused');
    } else {
      card.classList.remove('playing');
      if (playBtn) playBtn.innerHTML = '▶';
      if (eq) eq.classList.add('paused');
    }
  }

  /* ---- Helper: format seconds to mm:ss ---- */
  function fmt(s) {
    s = Math.floor(s || 0);
    return `${Math.floor(s/60)}:${String(s%60).padStart(2,'0')}`;
  }

  /* ---- Update mini player ---- */
  function syncMini(card) {
    if (!miniPlayer) return;
    const title  = card.querySelector('.music-title')?.textContent  || '—';
    const artist = card.querySelector('.music-artist')?.textContent || '—';
    const emoji  = card.dataset.emoji || '🎵';
    miniPlayer.querySelector('.mini-art').textContent   = emoji;
    miniPlayer.querySelector('.mini-title').textContent  = title;
    miniPlayer.querySelector('.mini-artist').textContent = artist;
    miniPlayer.classList.add('visible');
    const ctrl = miniPlayer.querySelector('.mini-ctrl');
    if (ctrl) ctrl.textContent = isPlaying ? '⏸' : '▶';
  }

  /* ---- Bind audio events to a card ---- */
  function bindAudio(audio, card) {
    const fill    = card.querySelector('.progress-bar-fill');
    const timeCur = card.querySelector('.time-current');

    audio.addEventListener('timeupdate', () => {
      if (!audio.duration) return;
      const pct = (audio.currentTime / audio.duration) * 100;
      if (fill) fill.style.width = pct + '%';
      if (timeCur) timeCur.textContent = fmt(audio.currentTime);
    });

    audio.addEventListener('ended', () => {
      isPlaying = false;
      setCardState(card, false);
      if (fill) fill.style.width = '0%';
      if (timeCur) timeCur.textContent = '0:00';
      miniPlayer?.classList.remove('visible');
    });

    // Progress bar click scrub
    card.querySelector('.progress-bar-track')?.addEventListener('click', (e) => {
      if (!audio.duration) return;
      const rect = e.currentTarget.getBoundingClientRect();
      audio.currentTime = ((e.clientX - rect.left) / rect.width) * audio.duration;
    });
  }

  /* ---- Simulate progress (no real audio) ---- */
  function simulateProgress(card) {
    const fill    = card.querySelector('.progress-bar-fill');
    const timeCur = card.querySelector('.time-current');
    let prog = 0;
    const tick = () => {
      if (!card.classList.contains('playing')) return;
      prog = (prog + 0.15) % 100;
      if (fill) fill.style.width = prog + '%';
      if (timeCur) {
        const secs = Math.floor(prog / 100 * 210);
        timeCur.textContent = fmt(secs);
      }
      setTimeout(tick, 300);
    };
    tick();
  }

  /* ---- Main play handler ---- */
  function handlePlay(card) {
    const cardId   = card.dataset.track;
    const audioUrl  = card.dataset.audioUrl  || '';
    const audioFile = card.dataset.audioFile || '';
    const src = audioFile || audioUrl;

    const titleEl  = card.querySelector('.music-title');
    const artistEl = card.querySelector('.music-artist');
    const title    = titleEl?.textContent  || 'Lagu';
    const artist   = artistEl?.textContent || '—';

    // Same card toggle
    if (currentCardId === cardId) {
      if (isPlaying) {
        isPlaying = false;
        setCardState(card, false);
        if (currentAudio) currentAudio.pause();
        if (miniPlayer) { miniPlayer.querySelector('.mini-ctrl').textContent = '▶'; }
        toast(`⏸ ${title} dijeda`);
      } else {
        isPlaying = true;
        setCardState(card, true);
        if (currentAudio) currentAudio.play().catch(() => {});
        if (miniPlayer) { miniPlayer.querySelector('.mini-ctrl').textContent = '⏸'; }
        toast(`▶ Memutar ${title}`);
      }
      return;
    }

    // Stop previous
    cards.forEach(c => setCardState(c, false));
    if (currentAudio) { currentAudio.pause(); currentAudio = null; }

    currentCardId = cardId;
    isPlaying = true;
    setCardState(card, true);
    card.dataset.emoji = card.querySelector('.music-art-bg span')?.textContent || '🎵';
    syncMini(card);
    toast(`▶ Memutar ${title} – ${artist}`);

    if (src) {
      // Real audio
      const audio = new Audio(src);
      audio.preload = 'metadata';
      currentAudio  = audio;
      bindAudio(audio, card);
      audio.play().catch(err => {
        console.warn('Audio play failed:', err);
        toast('⚠️ Tidak dapat memutar audio. Coba format lain.');
        isPlaying = false;
        setCardState(card, false);
        miniPlayer?.classList.remove('visible');
      });
    } else {
      // Simulation
      simulateProgress(card);
    }
  }

  // Bind play buttons on each card
  cards.forEach(card => {
    card.querySelector('.ctrl-btn-play')?.addEventListener('click', (e) => {
      e.stopPropagation();
      handlePlay(card);
    });
  });

  // Mini player control
  miniPlayer?.querySelector('.mini-ctrl')?.addEventListener('click', () => {
    const card = document.querySelector(`.music-card[data-track="${currentCardId}"]`);
    if (card) handlePlay(card);
  });

  // Prev / Next
  cards.forEach((card, idx) => {
    card.querySelectorAll('.ctrl-btn').forEach(btn => {
      if (btn.classList.contains('ctrl-btn-play')) return;
      btn.addEventListener('click', () => {
        const isPrev = btn.title === 'Sebelumnya';
        const target = isPrev
          ? cards[(idx - 1 + cards.length) % cards.length]
          : cards[(idx + 1) % cards.length];
        if (target) handlePlay(target);
      });
    });
  });
})();

/* ============================================================
   12. SMOOTH SCROLL
   ============================================================ */
document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(a => {
  a.addEventListener('click', function(e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
  });
});

/* ============================================================
   LOCAL AUDIO PLAYER (untuk file upload lokal)
   ============================================================ */
(function initLocalPlayers() {
  const players = document.querySelectorAll('.local-player');
  players.forEach(player => {
    const src  = player.dataset.src;
    if (!src) return;
    const audio = new Audio(src);
    audio.preload = 'metadata';

    const btn  = player.querySelector('.local-play-btn');
    const fill = player.querySelector('.local-progress-fill');
    const cur  = player.querySelector('.local-cur');
    const track = player.querySelector('.local-progress-track');

    const fmt = (s) => {
      s = Math.floor(s || 0);
      return `${Math.floor(s/60)}:${String(s%60).padStart(2,'0')}`;
    };

    audio.addEventListener('timeupdate', () => {
      if (!audio.duration) return;
      const pct = (audio.currentTime / audio.duration) * 100;
      if (fill) fill.style.width = pct + '%';
      if (cur)  cur.textContent = fmt(audio.currentTime);
    });

    audio.addEventListener('ended', () => {
      if (btn) btn.textContent = '▶';
      if (fill) fill.style.width = '0%';
      if (cur)  cur.textContent = '0:00';
    });

    if (track) {
      track.addEventListener('click', (e) => {
        if (!audio.duration) return;
        const rect = track.getBoundingClientRect();
        audio.currentTime = ((e.clientX - rect.left) / rect.width) * audio.duration;
      });
    }

    // Expose toggle function
    player._audio = audio;
  });
})();

window.toggleLocalAudio = function(btn) {
  const player = btn.closest('.local-player');
  const audio  = player?._audio;
  if (!audio) return;

  // Pause semua audio lain
  document.querySelectorAll('.local-player').forEach(p => {
    if (p !== player && p._audio && !p._audio.paused) {
      p._audio.pause();
      const b = p.querySelector('.local-play-btn');
      if (b) b.textContent = '▶';
    }
  });

  if (audio.paused) {
    audio.play().catch(e => console.warn('Audio play failed:', e));
    btn.textContent = '⏸';
  } else {
    audio.pause();
    btn.textContent = '▶';
  }
};

window.scrubAudio = function(track, event) {
  const player = track.closest('.local-player');
  const audio  = player?._audio;
  if (!audio || !audio.duration) return;
  const rect = track.getBoundingClientRect();
  audio.currentTime = ((event.clientX - rect.left) / rect.width) * audio.duration;
};
