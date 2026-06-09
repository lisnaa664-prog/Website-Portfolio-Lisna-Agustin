<?php
require_once 'config/koneksi.php';
if (isLoggedIn()) redirect('index.php');
$loggedOut = isset($_GET['logged_out']) && $_GET['logged_out'] === '1';
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Porto. – Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-page">

  <!-- Visual Kiri -->
  <div class="auth-visual">
    <div class="auth-visual-content">
      <a href="index.php" class="auth-visual-logo">Porto<span>.</span></a>
      <p class="auth-visual-tagline">
        "Selamat datang kembali! Masuk untuk mengakses portfolio dan mengelola profil Anda."
      </p>
      <div style="margin-top:48px;display:flex;gap:16px;font-size:3rem;opacity:0.25;">
        <span>💻</span><span>🎨</span><span>🚀</span>
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

      <h2>Masuk Akun</h2>
      <p class="sub">Belum punya akun? <a href="register.php">Daftar di sini</a></p>

      <?php if ($loggedOut): ?>
        <div class="alert alert-info">👋 Anda berhasil logout. Sampai jumpa lagi!</div>
      <?php endif; ?>
      <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
      <?php endif; ?>

      <form id="loginForm" method="POST" action="auth/login_process.php" novalidate>

        <div class="form-group">
          <label for="loginEmail">Email</label>
          <input type="email" id="loginEmail" name="email" class="form-control"
                 placeholder="email@contoh.com" autocomplete="email" required>
        </div>

        <div class="form-group">
          <label for="loginPassword">
            Password
            <span style="float:right;font-size:0.78rem;font-weight:400;">
              <a href="#" style="color:var(--primary);">Lupa password?</a>
            </span>
          </label>
          <div style="position:relative;">
            <input type="password" id="loginPassword" name="password" class="form-control"
                   placeholder="Masukkan password" autocomplete="current-password"
                   required style="padding-right:48px;">
            <button type="button" class="toggle-password" data-target="loginPassword"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:1rem;opacity:0.5;">👁️</button>
          </div>
        </div>

        <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px;">
          <input type="checkbox" id="remember" name="remember"
                 style="width:16px;height:16px;accent-color:var(--primary);">
          <label for="remember" style="font-size:0.85rem;color:var(--text-muted);cursor:pointer;">
            Ingat saya di perangkat ini
          </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;">
          Masuk →
        </button>
      </form>

      <div style="text-align:center;margin-top:20px;font-size:0.85rem;color:var(--text-muted);">
        Belum punya akun? <a href="register.php" style="color:var(--primary);font-weight:500;">Daftar sekarang</a>
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
