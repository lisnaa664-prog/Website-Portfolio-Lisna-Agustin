<?php
require_once 'config/koneksi.php';
$pdo   = getDB();
$about = $pdo->query("SELECT * FROM about_me LIMIT 1")->fetch();
$activeCV = $pdo->query("SELECT * FROM cv_files WHERE is_active=1 LIMIT 1")->fetch();
$skills   = $pdo->query("SELECT * FROM skills WHERE is_active=1 ORDER BY category, sort_order")->fetchAll();
$photoSrc = (!empty($about['photo']) && file_exists($about['photo'])) ? $about['photo'] : 'images/foto_lisna.jpeg';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Porto. – CV</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require_once 'config/navbar.php'; ?>

<div class="page-header">
  <div class="container">
    <div class="section-tag">Curriculum Vitae</div>
    <h1 class="section-title">Resume / CV Saya</h1>
    <p class="section-subtitle">Riwayat pendidikan, pengalaman, dan keahlian.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="cv-layout">

      <!-- SIDEBAR -->
      <div class="cv-sidebar fade-up">
        <img src="<?= e($photoSrc) ?>" alt="<?= e($about['full_name'] ?? '') ?>" class="cv-avatar">
        <h3><?= e($about['full_name'] ?? 'Lisna Agustin') ?></h3>
        <p class="sub"><?= e($about['tagline'] ?? 'Mahasiswa Teknik Informatika') ?></p>

        <?php if ($about['email']): ?><div class="cv-contact-item"><div class="cv-contact-icon">📧</div><span><?= e($about['email']) ?></span></div><?php endif; ?>
        <?php if ($about['phone']): ?><div class="cv-contact-item"><div class="cv-contact-icon">📱</div><span><?= e($about['phone']) ?></span></div><?php endif; ?>
        <?php if ($about['location']): ?><div class="cv-contact-item"><div class="cv-contact-icon">📍</div><span><?= e($about['location']) ?></span></div><?php endif; ?>

        <?php if ($activeCV): ?>
        <a href="/portfolio_redesign/uploads/cv/<?= e($activeCV['filename']) ?>" class="cv-download-btn" download="<?= e($activeCV['original_name']) ?>">
          📥 Download CV (PDF)
        </a>
        <?php else: ?>
        <div style="margin-top:20px;padding:12px;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:100px;text-align:center;font-size:0.8rem;color:var(--text-dim);">CV belum tersedia</div>
        <?php endif; ?>

        <!-- Bahasa -->
        <div style="margin-top:28px;width:100%;">
          <h4 style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-dim);margin-bottom:16px;">Bahasa</h4>
          <div style="display:flex;flex-direction:column;gap:12px;">
            <div>
              <div style="display:flex;justify-content:space-between;font-size:0.8rem;margin-bottom:6px;"><span>Bahasa Indonesia</span><span style="color:var(--primary);font-size:0.72rem;">Aktif</span></div>
              <div class="skill-bar"><div class="skill-fill" data-width="100"></div></div>
            </div>
            <div>
              <div style="display:flex;justify-content:space-between;font-size:0.8rem;margin-bottom:6px;"><span>English</span><span style="color:var(--primary);font-size:0.72rem;">Dasar</span></div>
              <div class="skill-bar"><div class="skill-fill" data-width="40"></div></div>
            </div>
          </div>
        </div>
      </div>

      <!-- MAIN CV -->
      <div class="fade-up">

        <div class="cv-main-section">
          <h2>👤 Ringkasan</h2>
          <p style="color:var(--text-muted);line-height:1.8;font-size:0.9rem;"><?= nl2br(e($about['description'] ?? '')) ?></p>
        </div>

        <div class="cv-main-section">
          <h2>🎓 Pendidikan</h2>
          <div class="cv-item">
            <div class="cv-item-date">2024 – Sekarang</div>
            <h4><?= e($about['major'] ?? 'S1 Teknik Informatika') ?></h4>
            <p><?= e($about['university'] ?? 'Universitas Samudra, Kota Langsa') ?><?= $about['gpa'] ? ' · IPK ' . number_format($about['gpa'],2) : '' ?></p>
          </div>
          <div class="cv-item"><div class="cv-item-date">2021 – 2024</div><h4>SMA Negeri 1 Kota Langsa</h4><p>Jurusan IPA</p></div>
          <div class="cv-item"><div class="cv-item-date">2018 – 2021</div><h4>SMP Negeri 3 Kota Langsa</h4><p>Nilai UN rata-rata 9.55</p></div>
        </div>

        <div class="cv-main-section">
          <h2>💼 Pengalaman</h2>
          <div class="cv-item"><div class="cv-item-date">2024 – Sekarang</div><h4>Belajar Pengembangan Web</h4><p>Pribadi · Mempelajari HTML, CSS, PHP, dan pengembangan website dinamis.</p></div>
          <div class="cv-item"><div class="cv-item-date">2024 – Sekarang</div><h4>Tugas Kuliah Pemrograman</h4><p><?= e($about['university'] ?? 'Universitas Samudra') ?> · Mengerjakan tugas pemrograman dan memahami dasar logika coding.</p></div>
        </div>

        <div class="cv-main-section">
          <h2>🏛️ Organisasi</h2>
          <div class="cv-item"><div class="cv-item-date">2024 – Sekarang</div><h4>Anggota HIMATIF</h4><p>Bidang Kewirausahaan – <?= e($about['university'] ?? 'Universitas Samudra') ?></p></div>
          <div class="cv-item"><div class="cv-item-date">2021 – 2023</div><h4>Anggota PASKIBRA</h4><p>SMA Negeri 1 Kota Langsa · Kedisiplinan dan upacara kenegaraan.</p></div>
        </div>

        <?php if (!empty($skills)): ?>
        <div class="cv-main-section">
          <h2>⚡ Keahlian</h2>
          <div class="tools-grid">
            <?php foreach ($skills as $s): ?>
              <span class="tool-badge"><?= e($s['icon']??'') ?> <?= e($s['name']) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- CTA -->
        <div style="text-align:center;padding:32px;background:linear-gradient(135deg,rgba(192,132,252,0.1),rgba(129,140,248,0.08));border:1px solid rgba(192,132,252,0.2);border-radius:var(--radius-lg);">
          <div style="font-size:1.5rem;margin-bottom:12px;">🙏</div>
          <h3 style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:8px;">Terima Kasih!</h3>
          <p style="color:var(--text-muted);font-size:0.875rem;margin-bottom:20px;">Silakan hubungi saya melalui email atau download CV untuk informasi lebih lanjut.</p>
          <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="contact.php" class="btn btn-primary">Hubungi Saya</a>
            <?php if ($activeCV): ?>
            <a href="/portfolio_redesign/uploads/cv/<?= e($activeCV['filename']) ?>" class="btn btn-outline" download>📥 Download CV</a>
            <?php endif; ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php require_once 'config/footer.php'; ?>
<script src="js/script.js"></script>
</body></html>
