<?php
require_once 'config/koneksi.php';
$pdo     = getDB();
$about   = $pdo->query("SELECT * FROM about_me LIMIT 1")->fetch();
$socials = $pdo->query("SELECT * FROM social_media WHERE is_active=1 ORDER BY sort_order")->fetchAll();
$success = ''; $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_contact'])) {
    $nama   = trim($_POST['nama'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan  = trim($_POST['pesan'] ?? '');

    if (!$nama || !$email || !$pesan) {
        $error = 'Semua kolom bertanda * wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (mb_strlen($pesan) < 10) {
        $error = 'Pesan minimal 10 karakter.';
    } else {
        try {
            $pdo->prepare("INSERT INTO messages (name, email, subject, message, created_at) VALUES (?,?,?,?,NOW())")
                ->execute([$nama, $email, $subjek, $pesan]);
            $success = "Terima kasih, {$nama}! Pesan Anda telah dikirim. Saya akan membalas segera. 🎉";
        } catch (PDOException $ex) {
            $error = 'Gagal menyimpan pesan. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Porto. – Contact</title><link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php require_once 'config/navbar.php'; ?>

<div class="page-header">
  <div class="container">
    <div class="section-tag">Kontak</div>
    <h1 class="section-title">Mari Terhubung</h1>
    <p class="section-subtitle">Punya pertanyaan atau proyek menarik? Saya siap mendengarkan!</p>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="contact-grid">

      <!-- Info -->
      <div class="contact-info fade-up">
        <div class="section-tag">Hubungi Saya</div>
        <h2 style="margin-top:16px;">Ada yang Ingin Dibicarakan?</h2>
        <div class="accent-line"></div>
        <p>Saya selalu terbuka untuk diskusi, kolaborasi, atau sekadar berkenalan. Jangan ragu menghubungi saya.</p>

        <div class="contact-items">
          <?php if ($about['email']): ?>
          <a href="mailto:<?= e($about['email']) ?>" class="contact-item">
            <div class="contact-item-icon">📧</div>
            <div class="contact-item-text"><div class="label">Email</div><div class="value"><?= e($about['email']) ?></div></div>
          </a>
          <?php endif; ?>
          <?php if ($about['phone']): ?>
          <a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$about['phone']) ?>" target="_blank" class="contact-item">
            <div class="contact-item-icon">📱</div>
            <div class="contact-item-text"><div class="label">WhatsApp</div><div class="value"><?= e($about['phone']) ?></div></div>
          </a>
          <?php endif; ?>
          <?php if ($about['location']): ?>
          <div class="contact-item">
            <div class="contact-item-icon">📍</div>
            <div class="contact-item-text"><div class="label">Lokasi</div><div class="value"><?= e($about['location']) ?></div></div>
          </div>
          <?php endif; ?>
          <div class="contact-item">
            <div class="contact-item-icon">⏰</div>
            <div class="contact-item-text"><div class="label">Waktu Respons</div><div class="value">Biasanya dalam 24 jam</div></div>
          </div>
        </div>

        <!-- Sosial Media dari DB -->
        <?php if (!empty($socials)): ?>
        <div style="margin-top:20px;">
          <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-dim);margin-bottom:12px;">Media Sosial</div>
          <div class="social-links">
            <?php foreach ($socials as $s): ?>
              <a href="<?= e($s['url']) ?>" target="_blank" class="social-link" title="<?= e($s['platform']) ?>"><?= e($s['icon']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <div style="margin-top:28px;padding:20px;background:rgba(16,185,129,0.07);border:1px solid rgba(16,185,129,0.18);border-radius:var(--radius);">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
            <span style="width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block;"></span>
            <strong style="font-size:0.875rem;color:#34d399;">Tersedia untuk Magang / Freelance</strong>
          </div>
          <p style="font-size:0.82rem;color:var(--text-muted);">Saat ini mencari kesempatan magang atau proyek freelance part-time. Hubungi saya!</p>
        </div>
      </div>

      <!-- Form -->
      <div class="contact-form-wrapper fade-up">
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:24px;color:var(--text-main);">📩 Kirim Pesan</h3>
        <?php if ($success): ?><div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error):   ?><div class="alert alert-error">❌ <?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form id="contactForm" method="POST" action="contact.php" novalidate>
          <input type="hidden" name="send_contact" value="1">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
              <label for="nama">Nama Lengkap *</label>
              <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama kamu" value="<?= isset($_POST['nama']) ? e($_POST['nama']) : '' ?>" autocomplete="name">
              <span class="form-error">Nama tidak boleh kosong.</span>
            </div>
            <div class="form-group">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="email@domain.com" value="<?= isset($_POST['email']) ? e($_POST['email']) : '' ?>" autocomplete="email">
              <span class="form-error">Email tidak valid.</span>
            </div>
          </div>
          <div class="form-group">
            <label for="subjek">Subjek</label>
            <input type="text" id="subjek" name="subjek" class="form-control" placeholder="Perihal pesan kamu" value="<?= isset($_POST['subjek']) ? e($_POST['subjek']) : '' ?>">
          </div>
          <div class="form-group">
            <label for="pesan">Pesan *</label>
            <textarea id="pesan" name="pesan" class="form-control" rows="5" placeholder="Tuliskan pesanmu di sini..."><?= isset($_POST['pesan']) ? e($_POST['pesan']) : '' ?></textarea>
            <span class="form-error">Pesan minimal 10 karakter.</span>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Kirim Pesan ✉️</button>
        </form>
      </div>

    </div>
  </div>
</section>

<?php require_once 'config/footer.php'; ?>
<script src="js/script.js"></script>
<style>
  @media(max-width:560px){ .contact-form-wrapper form div[style*="grid"]{display:block;} }
</style>
</body></html>
