<?php
require_once 'config/koneksi.php';
if (isLoggedIn()) redirect('index.php');
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Porto. – Daftar Akun</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-page">

  <!-- Visual Kiri -->
  <div class="auth-visual">
    <div class="auth-visual-content">
      <a href="index.php" class="auth-visual-logo">Porto<span>.</span></a>
      <p class="auth-visual-tagline">
        "Bergabunglah dan mulai perjalanan digital Anda bersama kami!"
      </p>
      <div style="margin-top:48px;text-align:left;">
        <?php
        $steps = [
          ['icon'=>'✍️','text'=>'Isi data diri Anda'],
          ['icon'=>'🔒','text'=>'Buat password yang aman'],
          ['icon'=>'🚀','text'=>'Mulai jelajahi portfolio!'],
        ];
        foreach ($steps as $i => $step):
        ?>
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;opacity:<?= 1 - ($i*0.15) ?>;">
          <div style="width:40px;height:40px;border-radius:50%;background:rgba(192,132,252,0.15);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
            <?= $step['icon'] ?>
          </div>
          <span style="color:rgba(255,255,255,0.75);font-size:0.95rem;"><?= $step['text'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Form Kanan -->
  <div class="auth-form-side">
    <div class="auth-form-box">

      <div class="mobile-logo" style="text-align:center;margin-bottom:32px;display:none;">
        <a href="index.php" style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--text-main);">
          Porto<span style="color:var(--primary);">.</span>
        </a>
      </div>

      <h2>Buat Akun</h2>
      <p class="sub">Sudah punya akun? <a href="login.php">Login di sini</a></p>

      <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
      <?php endif; ?>

      <form id="registerForm" method="POST" action="auth/register_process.php" novalidate>

        <div class="form-group">
          <label for="regName">Nama Lengkap *</label>
          <input type="text" id="regName" name="name" class="form-control"
                 placeholder="Nama lengkap" autocomplete="name" required>
          <span class="form-error">Nama tidak boleh kosong.</span>
        </div>

        <div class="form-group">
          <label for="regEmail">Email *</label>
          <input type="email" id="regEmail" name="email" class="form-control"
                 placeholder="email@contoh.com" autocomplete="email" required>
          <span class="form-error">Masukkan email yang valid.</span>
        </div>

        <div class="form-group">
          <label for="regPassword">Password *</label>
          <div style="position:relative;">
            <input type="password" id="regPassword" name="password" class="form-control"
                   placeholder="Minimal 6 karakter" autocomplete="new-password"
                   required style="padding-right:48px;">
            <button type="button" class="toggle-password" data-target="regPassword"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:1rem;opacity:0.5;">👁️</button>
          </div>
          <div class="password-strength" style="margin-top:8px;">
            <div class="strength-bar">
              <div class="strength-segment"></div>
              <div class="strength-segment"></div>
              <div class="strength-segment"></div>
              <div class="strength-segment"></div>
            </div>
            <span class="strength-text"></span>
          </div>
          <span class="form-error">Password minimal 6 karakter.</span>
        </div>

        <div class="form-group">
          <label for="regConfirm">Konfirmasi Password *</label>
          <div style="position:relative;">
            <input type="password" id="regConfirm" name="confirm" class="form-control"
                   placeholder="Ulangi password" autocomplete="new-password"
                   required style="padding-right:48px;">
            <button type="button" class="toggle-password" data-target="regConfirm"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:1rem;opacity:0.5;">👁️</button>
          </div>
          <span class="form-error">Konfirmasi password tidak cocok.</span>
        </div>

        <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:24px;">
          <input type="checkbox" id="terms" required
                 style="width:16px;height:16px;margin-top:3px;accent-color:var(--primary);flex-shrink:0;">
          <label for="terms" style="font-size:0.85rem;color:var(--text-muted);cursor:pointer;line-height:1.5;">
            Saya menyetujui <a href="#" style="color:var(--primary);">syarat & ketentuan</a>
            dan <a href="#" style="color:var(--primary);">kebijakan privasi</a>.
          </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;">
          Buat Akun →
        </button>
      </form>

      <div style="text-align:center;margin-top:20px;font-size:0.85rem;color:var(--text-muted);">
        Sudah punya akun? <a href="login.php" style="color:var(--primary);font-weight:500;">Login sekarang</a>
      </div>
      <div style="text-align:center;margin-top:12px;">
        <a href="index.php" style="font-size:0.82rem;color:var(--text-dim);">← Kembali ke Beranda</a>
      </div>

    </div>
  </div>
</div>

<style>
@media (max-width:768px) { .mobile-logo { display:block !important; } }
</style>
<script src="js/script.js"></script>
</body>
</html>
