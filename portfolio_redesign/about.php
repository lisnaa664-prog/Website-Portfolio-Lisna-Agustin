<?php
require_once 'config/koneksi.php';
$pdo   = getDB();
$about = $pdo->query("SELECT * FROM about_me LIMIT 1")->fetch();
if (!$about) { $about = ['full_name'=>'Lisna Agustin','tagline'=>'Mahasiswa Informatika','description'=>'','email'=>'','phone'=>'','location'=>'Kota Langsa','university'=>'Universitas Samudra','major'=>'Teknik Informatika','gpa'=>3.72,'photo'=>'images/foto_lisna.jpeg']; }
$photoSrc = (!empty($about['photo']) && file_exists($about['photo'])) ? $about['photo'] : 'images/foto_lisna.jpeg';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Porto. – About Me</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require_once 'config/navbar.php'; ?>

<div class="page-header">
  <div class="container">
    <div class="section-tag">Tentang Saya</div>
    <h1 class="section-title">Kenali Saya Lebih Dekat</h1>
    <p class="section-subtitle">Perjalanan, minat, dan visi saya ke depan.</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="about-grid">
      <!-- Kiri -->
      <div class="about-image-side fade-up">
        <div class="about-photo-frame">
          <img src="<?= e($photoSrc) ?>" alt="<?= e($about['full_name']) ?>">
        </div>
        <div class="about-info-card">
          <h4>Info Singkat</h4>
          <div class="info-row"><span class="label">Nama</span><span class="value"><?= e($about['full_name']) ?></span></div>
          <?php if ($about['location']): ?><div class="info-row"><span class="label">Lokasi</span><span class="value"><?= e($about['location']) ?></span></div><?php endif; ?>
          <?php if ($about['email']): ?><div class="info-row"><span class="label">Email</span><span class="value"><?= e($about['email']) ?></span></div><?php endif; ?>
          <?php if ($about['gpa']): ?><div class="info-row"><span class="label">IPK</span><span class="value"><?= number_format($about['gpa'],2) ?></span></div><?php endif; ?>
          <div class="info-row"><span class="label">Status</span><span class="value" style="color:#4ade80;">● Open to Work</span></div>
        </div>
      </div>

      <!-- Kanan -->
      <div class="about-content fade-up">
        <div class="section-tag">Profil</div>
        <h2><?= e($about['full_name']) ?></h2>
        <div class="accent-line"></div>
        <p class="about-text"><?= nl2br(e($about['description'])) ?></p>

        <!-- Pendidikan -->
        <div style="margin-top:48px;">
          <div class="section-tag">Pendidikan</div>
          <h3 style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:var(--text-main);margin:16px 0 24px;">Riwayat Pendidikan</h3>
          <div class="education-list">
            <div class="edu-card fade-up">
              <div class="edu-icon">🎓</div>
              <div class="edu-body">
                <div class="year">2024 – Sekarang</div>
                <h4><?= e($about['major'] ?? 'Teknik Informatika') ?></h4>
                <p><?= e($about['university'] ?? 'Universitas Samudra') ?><?= $about['gpa'] ? ' · IPK: ' . number_format($about['gpa'],2) : '' ?></p>
              </div>
            </div>
            <div class="edu-card fade-up">
              <div class="edu-icon">🏫</div>
              <div class="edu-body">
                <div class="year">2021 – 2024</div>
                <h4>SMA Negeri 1 Kota Langsa</h4>
                <p>Jurusan IPA</p>
              </div>
            </div>
            <div class="edu-card fade-up">
              <div class="edu-icon">📚</div>
              <div class="edu-body">
                <div class="year">2018 – 2021</div>
                <h4>SMP Negeri 3 Kota Langsa</h4>
                <p>Nilai UN rata-rata 9.55</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Minat -->
        <div style="margin-top:48px;">
          <div class="section-tag">Minat & Hobi</div>
          <h3 style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:var(--text-main);margin:16px 0 20px;">Apa yang Saya Sukai</h3>
          <div class="interest-chips">
            <span class="interest-chip">🎬 Drakor Enthusiast</span>
            <span class="interest-chip">🍿 Anime Lover</span>
            <span class="interest-chip">🎤 Singing Time</span>
            <span class="interest-chip">📚 Comic Reader</span>
            <span class="interest-chip">💻 Exploring Coding</span>
            <span class="interest-chip">🌐 Learning Web</span>
            <span class="interest-chip">📝 Campus Life</span>
            <span class="interest-chip">🤝 Sharing & Discussion</span>
          </div>
        </div>

        <!-- Career Goal -->
        <div class="career-goal-box fade-up" style="margin-top:48px;">
          <h3>🎯 Tujuan Karier</h3>
          <p>Saya bercita-cita menjadi seorang developer yang mampu membuat website atau aplikasi dengan baik. Ke depannya, saya berharap dapat membuat produk digital yang bermanfaat, serta membantu usaha keluarga agar bisa berkembang lebih baik.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'config/footer.php'; ?>
<script src="js/script.js"></script>
</body></html>
