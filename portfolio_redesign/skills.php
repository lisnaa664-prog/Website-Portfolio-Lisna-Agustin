<?php
require_once 'config/koneksi.php';
$pdo    = getDB();
$skills = $pdo->query("SELECT * FROM skills WHERE is_active=1 ORDER BY category, sort_order, name")->fetchAll();
$grouped = ['hard'=>[],'soft'=>[],'tool'=>[]];
foreach ($skills as $s) $grouped[$s['category']][] = $s;
$activities = [
  ['icon'=>'🎓','year'=>'2024 – Sekarang','title'=>'Mahasiswa Teknik Informatika','sub'=>'Universitas Samudra, Kota Langsa'],
  ['icon'=>'🏛️','year'=>'2024 – Sekarang','title'=>'Anggota HIMATIF','sub'=>'Bidang Kewirausahaan'],
  ['icon'=>'🇮🇩','year'=>'2021 – 2023','title'=>'Anggota PASKIBRA','sub'=>'SMA Negeri 1 Kota Langsa'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Porto. – Skills</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require_once 'config/navbar.php'; ?>

<div class="page-header">
  <div class="container">
    <div class="section-tag">Kemampuan</div>
    <h1 class="section-title">Skills & Keahlian</h1>
    <p class="section-subtitle">Kemampuan yang saya miliki dan terus kembangkan setiap harinya.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="skills-grid">

      <!-- HARD SKILLS -->
      <div class="fade-up">
        <h2 class="skills-section-title">💻 Hard Skills</h2>
        <?php if (!empty($grouped['hard'])): ?>
          <?php foreach ($grouped['hard'] as $s): ?>
          <div class="skill-item">
            <div class="skill-meta">
              <span class="skill-name"><?= e($s['icon']??'') ?> <?= e($s['name']) ?></span>
              <span class="skill-pct"><?= $s['level'] ?>%</span>
            </div>
            <div class="skill-bar"><div class="skill-fill" data-width="<?= $s['level'] ?>"></div></div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:var(--text-dim);font-size:0.875rem;">Belum ada hard skill ditambahkan.</p>
        <?php endif; ?>

        <!-- TOOLS -->
        <?php if (!empty($grouped['tool'])): ?>
        <div style="margin-top:48px;">
          <h2 class="skills-section-title">🛠️ Tools & Teknologi</h2>
          <div class="tools-grid">
            <?php foreach ($grouped['tool'] as $t): ?>
              <span class="tool-badge"><?= e($t['icon']??'') ?> <?= e($t['name']) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- SOFT SKILLS -->
      <div class="fade-up">
        <h2 class="skills-section-title">🧠 Soft Skills</h2>
        <?php if (!empty($grouped['soft'])): ?>
        <div class="soft-skills-grid">
          <?php foreach ($grouped['soft'] as $s): ?>
          <div class="soft-skill-card">
            <div class="soft-skill-icon"><?= e($s['icon']??'⭐') ?></div>
            <div class="soft-skill-name"><?= e($s['name']) ?></div>
            <div class="soft-skill-desc">Level kemampuan: <?= $s['level'] ?>%</div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
          <p style="color:var(--text-dim);font-size:0.875rem;">Belum ada soft skill ditambahkan.</p>
        <?php endif; ?>
      </div>

    </div>

    <!-- AKTIVITAS -->
    <div style="margin-top:80px;">
      <div class="section-tag">Pengalaman</div>
      <h2 class="section-title" style="margin-top:16px;">Pengalaman & Aktivitas</h2>
      <div class="accent-line"></div>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;">
        <?php foreach ($activities as $a): ?>
        <div class="activity-card fade-up">
          <div style="display:flex;gap:14px;align-items:flex-start;">
            <div class="edu-icon"><?= $a['icon'] ?></div>
            <div>
              <div style="font-size:0.7rem;color:var(--primary);font-weight:600;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;"><?= e($a['year']) ?></div>
              <h4 style="font-size:0.9rem;font-weight:600;color:var(--text-main);margin-bottom:4px;"><?= e($a['title']) ?></h4>
              <p style="font-size:0.8rem;color:var(--text-muted);"><?= e($a['sub']) ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<?php require_once 'config/footer.php'; ?>
<script src="js/script.js"></script>
</body></html>
