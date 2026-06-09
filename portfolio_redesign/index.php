<?php
require_once 'config/koneksi.php';
$pdo = getDB();

// Pull data from DB
$about  = $pdo->query("SELECT * FROM about_me LIMIT 1")->fetch();
$hobbies = [
    ['icon'=>'🎤','class'=>'ht-1','title'=>'Bernyanyi','desc'=>'Menikmati lagu pop, religi, dan daerah. Bernyanyi adalah cara mengekspresikan diri dan melepas penat.'],
    ['icon'=>'📺','class'=>'ht-2','title'=>'Menonton','desc'=>'Suka film, drama Korea, dan anime. Membantu memahami cerita dan karakter yang beragam.'],
    ['icon'=>'📚','class'=>'ht-3','title'=>'Membaca Komik','desc'=>'Manhwa, manga, dan komik lokal. Menikmati alur cerita dan ilustrasi yang keren.'],
];
$songs   = $pdo->query("SELECT * FROM favorite_music WHERE is_active=1 ORDER BY sort_order, id")->fetchAll();
$stats   = [
    'projects' => $pdo->query("SELECT COUNT(*) FROM projects WHERE is_active=1")->fetchColumn(),
    'skills'   => $pdo->query("SELECT COUNT(*) FROM skills WHERE is_active=1")->fetchColumn(),
];
$gpa = $about['gpa'] ?? '3.72';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Porto. – <?= e($about['full_name'] ?? 'Portfolio') ?></title>
  <meta name="description" content="Portfolio pribadi <?= e($about['full_name'] ?? '') ?> – <?= e($about['tagline'] ?? 'Mahasiswa Informatika') ?>.">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php require_once 'config/navbar.php'; ?>

<!-- HERO -->
<section class="hero">
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>
  <div class="hero-orb hero-orb-3"></div>
  <div class="container">
    <div class="hero-grid">
      <div class="hero-content fade-up">
        <div class="hero-greeting">Halo, saya</div>
        <h1 class="hero-name">
          <?php
            $parts = explode(' ', $about['full_name'] ?? 'Lisna Agustin', 2);
            echo e($parts[0]) . '<br><span class="grad-name">' . e($parts[1] ?? '') . '</span>';
          ?>
        </h1>
        <p class="hero-title">
          <span id="typingText"><?= e($about['tagline'] ?? 'Mahasiswa Informatika') ?></span><span class="cursor">|</span>
        </p>
        <p class="hero-desc"><?= e($about['description'] ?? '') ?></p>
        <div class="hero-actions">
          <a href="portfolio.php" class="btn btn-primary">Lihat Portfolio →</a>
          <a href="contact.php" class="btn btn-outline">Hubungi Saya</a>
        </div>
        <div class="hero-stats">
          <div class="stat-item">
            <div class="stat-number" data-target="<?= $stats['projects'] ?>" data-suffix="+"><?= $stats['projects'] ?>+</div>
            <div class="stat-label">Proyek</div>
          </div>
          <div class="stat-item">
            <div class="stat-number" data-target="<?= $stats['skills'] ?>" data-suffix=""><?= $stats['skills'] ?></div>
            <div class="stat-label">Teknologi</div>
          </div>
          <div class="stat-item">
            <div class="stat-number" data-target="<?= number_format($gpa,2) ?>" data-suffix=""><?= $gpa ?></div>
            <div class="stat-label">IPK</div>
          </div>
        </div>
      </div>
      <div class="hero-image fade-up">
        <div class="hero-image-wrapper">
          <div class="hero-photo-ring">
            <?php
              $photo = $about['photo'] ?? 'images/foto_lisna.jpeg';
              $photoSrc = file_exists($photo) ? $photo : 'images/foto_lisna.jpeg';
            ?>
            <img src="<?= e($photoSrc) ?>" alt="<?= e($about['full_name'] ?? '') ?>" class="about-photo">
          </div>
          <div class="hero-badge">
            <div class="hero-badge-icon">💻</div>
            <div class="hero-badge-text">
              <strong>Open to Learn</strong>
              <span>Aktif & semangat belajar</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STRIP -->
<section class="strip-section">
  <div class="container">
    <div class="strip-grid stagger">
      <div class="strip-item fade-up"><div class="strip-icon">🎓</div><div class="strip-title">Mahasiswa Aktif</div><div class="strip-desc"><?= e($about['major'] ?? 'Teknik Informatika') ?> – <?= e($about['university'] ?? 'Universitas Samudra') ?></div></div>
      <div class="strip-item fade-up"><div class="strip-icon">💡</div><div class="strip-title">Belajar Coding</div><div class="strip-desc">Menyelesaikan masalah lewat kode</div></div>
      <div class="strip-item fade-up"><div class="strip-icon">🤝</div><div class="strip-title">Kerja Tim</div><div class="strip-desc">Aktif berdiskusi dan berkolaborasi</div></div>
      <div class="strip-item fade-up"><div class="strip-icon">🌱</div><div class="strip-title">Terus Berkembang</div><div class="strip-desc">Selalu ingin belajar hal-hal baru</div></div>
    </div>
  </div>
</section>

<!-- HOBBIES -->
<section class="section">
  <div class="container">
    <div class="section-tag">Hobi & Minat</div>
    <h2 class="section-title">Kegiatan yang Saya Sukai</h2>
    <p class="section-subtitle">Di luar dunia coding, inilah yang mengisi hari-hari saya.</p>
    <div class="hobby-grid">
      <?php foreach ($hobbies as $h): ?>
      <div class="hobby-card fade-up">
        <div class="hobby-thumb <?= $h['class'] ?>"><span style="position:relative;z-index:1;"><?= $h['icon'] ?></span></div>
        <div class="hobby-body">
          <h3 class="hobby-title"><?= e($h['title']) ?></h3>
          <p class="hobby-desc"><?= e($h['desc']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:48px;">
      <a href="portfolio.php" class="btn btn-glass">Lihat Portfolio Proyek →</a>
    </div>
  </div>
</section>

<!-- MUSIC SECTION – Spotify Embed + Foto Penyanyi -->
<?php if (!empty($songs)): ?>
<section class="music-section" id="music">
  <div class="container">

    <!-- Header -->
    <div class="music-section-header fade-up">
      <div class="section-tag">🎧 Musik Favorit</div>
      <h2 class="section-title">Lagu yang Saya <span class="grad">Suka</span></h2>
      <p style="color:var(--text-muted);font-size:0.95rem;">Lagu-lagu yang menemani hari-hari belajar dan bersantai saya.</p>
    </div>

    <!-- Music Cards Grid - PAKAI GRID BIASA -->
    <div style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; max-width: 1200px; margin: 0 auto;">
      <?php foreach ($songs as $idx => $song):
        // Extract Spotify track ID dari audio_url
        $trackId = '';
        if (!empty($song['audio_url'])) {
            if (preg_match('/track\/([a-zA-Z0-9]+)/', $song['audio_url'], $matches)) {
                $trackId = $matches[1];
            }
        }
        $spotifyEmbed = $trackId ? 'https://open.spotify.com/embed/track/' . $trackId . '?utm_source=generator' : '';
        
        // Warna gradient
        $gradients = [
            'linear-gradient(135deg,#ec4899 0%,#8b5cf6 100%)',
            'linear-gradient(135deg,#06b6d4 0%,#6366f1 100%)',
        ];
        $bgColor = !empty($song['bg_color']) ? $song['bg_color'] : $gradients[$idx % count($gradients)];
      ?>
      <!-- CARD dengan LEBAR TETAP 340px -->
      <div class="fade-up" style="width: 340px; flex-shrink: 0; background: var(--card-bg, #1e1e2e); border-radius: 28px; overflow: hidden; border: 1px solid var(--border, #2a2a3a); box-shadow: 0 20px 35px -12px rgba(0,0,0,0.1);">

        <!-- Card Header: Foto + Info -->
        <div style="display: flex; gap: 16px; padding: 20px;">
          <!-- Cover Art -->
          <div style="width: 80px; height: 80px; border-radius: 20px; flex-shrink: 0; overflow: hidden; background: <?= htmlspecialchars($bgColor) ?>; display: flex; align-items: center; justify-content: center;">
            <?php if (!empty($song['thumbnail']) && file_exists($song['thumbnail'])): ?>
              <img src="/portfolio_redesign/<?= htmlspecialchars($song['thumbnail']) ?>"
                   alt="<?= htmlspecialchars($song['title']) ?>"
                   style="width:100%;height:100%;object-fit:cover;">
            <?php else: ?>
              <span style="font-size: 2.5rem;"><?= htmlspecialchars($song['thumb_emoji'] ?? '🎵') ?></span>
            <?php endif; ?>
          </div>
          
          <!-- Info Lagu -->
          <div style="flex:1; min-width:0;">
            <div style="margin-bottom: 6px;">
              <span style="font-size:0.65rem;opacity:0.7;">Lagu Favorit</span>
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($song['title']) ?></h3>
            <p style="font-size: 0.8rem; color: #a0a0b0; margin: 0 0 8px 0;"><?= htmlspecialchars($song['artist']) ?></p>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size:0.7rem;opacity:0.5;">Pratinju</span>
              <span style="opacity: 0.6; font-size: 0.75rem;">❤️</span>
            </div>
          </div>
        </div>

        <!-- Spotify Embed Player -->
        <?php if ($spotifyEmbed): ?>
        <div style="padding: 0 20px 20px 20px;">
          <iframe
            style="border-radius:12px"
            src="<?= $spotifyEmbed ?>"
            width="100%"
            height="80"
            frameBorder="0"
            allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
            loading="lazy">
          </iframe>
          <p style="font-size: 0.7rem; color: #6b6b7a; text-align: center; margin-top: 10px;">
            🎧 Klik tombol ▶️ untuk memutar | Login Spotify untuk full lagu
          </p>
        </div>
        <?php endif; ?>

      </div>
      <?php endforeach; ?>
    </div>

    <!-- Info Cara Putar -->
    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 48px; padding: 20px 24px; background: var(--bg-card, #1a1a2a); border: 1px solid var(--border, #2a2a3a); border-radius: 24px; max-width: 1200px; margin-left: auto; margin-right: auto;">
      <span style="font-size:1.5rem;">💿</span>
      <div>
        <p style="font-weight:600;margin-bottom:4px;">Cara Memutar Musik</p>
        <p style="color:var(--text-muted);font-size:0.8rem;">
          Klik tombol <strong>▶️ Play</strong> pada pemutar Spotify di atas. 
          Login ke akun Spotify untuk mendengar full lagu (gratis).
        </p>
      </div>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="cta-section">
  <div class="container" style="position:relative;z-index:1;">
    <div class="section-tag" style="margin:0 auto 20px;display:table;">✉️ Terhubung</div>
    <h2 class="fade-up">Terima kasih sudah mengunjungi!</h2>
    <p class="fade-up">Silakan hubungi saya jika ingin berkenalan atau berdiskusi.</p>
    <a href="contact.php" class="btn btn-primary fade-up">Hubungi Saya 📩</a>
  </div>
</section>

<?php require_once 'config/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
