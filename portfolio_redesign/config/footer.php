<?php
// config/footer.php - Footer pulls from DB
$_pdo_footer = null;
try { $_pdo_footer = getDB(); } catch(Exception $e) {}

$_about_footer  = $_pdo_footer ? $_pdo_footer->query("SELECT full_name,email,phone FROM about_me LIMIT 1")->fetch() : null;
$_socials_footer = $_pdo_footer ? $_pdo_footer->query("SELECT * FROM social_media WHERE is_active=1 ORDER BY sort_order LIMIT 8")->fetchAll() : [];
$_unreadFooter   = $_pdo_footer ? $_pdo_footer->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn() : 0;
?>
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="logo">Porto<span>.</span></div>
        <p>Personal portfolio website <?= e($_about_footer['full_name'] ?? 'Lisna Agustin') ?>, mahasiswa Informatika yang sedang mengembangkan diri di dunia pengembangan web.</p>
        <?php if (!empty($_socials_footer)): ?>
        <div style="display:flex;gap:10px;margin-top:16px;">
          <?php foreach ($_socials_footer as $_s): ?>
            <a href="<?= e($_s['url']) ?>" target="_blank" title="<?= e($_s['platform']) ?>"
               style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;font-size:0.9rem;transition:all 0.2s;"
               onmouseover="this.style.background='rgba(168,85,247,0.15)';this.style.borderColor='rgba(168,85,247,0.3)'"
               onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.borderColor='rgba(255,255,255,0.08)'">
              <?= e($_s['icon']) ?>
            </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <div class="footer-col">
        <h4>Navigasi</h4>
        <ul>
          <li><a href="/portfolio_redesign/index.php">Home</a></li>
          <li><a href="/portfolio_redesign/about.php">About</a></li>
          <li><a href="/portfolio_redesign/portfolio.php">Portfolio</a></li>
          <li><a href="/portfolio_redesign/skills.php">Skills</a></li>
          <li><a href="/portfolio_redesign/cv.php">CV</a></li>
          <li><a href="/portfolio_redesign/contact.php">Contact</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Kontak</h4>
        <ul>
          <?php if ($_about_footer && $_about_footer['email']): ?>
            <li><a href="mailto:<?= e($_about_footer['email']) ?>">📧 <?= e($_about_footer['email']) ?></a></li>
          <?php endif; ?>
          <?php foreach ($_socials_footer as $_s): ?>
            <li><a href="<?= e($_s['url']) ?>" target="_blank"><?= e($_s['icon']) ?> <?= e($_s['platform']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> Porto. — Dibuat dengan ❤️ menggunakan PHP & MySQL</span>
      <span><?= e($_about_footer['full_name'] ?? 'Lisna Agustin') ?> · Mahasiswa Informatika</span>
    </div>
  </div>
</footer>

<!-- Back to Top -->
<button id="backToTop" aria-label="Back to top">↑</button>

<!-- Mini Music Player -->
<div class="mini-player" id="miniPlayer">
  <div class="mini-art">🎵</div>
  <div class="mini-info">
    <div class="mini-title">—</div>
    <div class="mini-artist">—</div>
  </div>
  <button class="mini-ctrl" aria-label="Play/Pause">▶</button>
</div>

<!-- Flash Message -->
<?php
$_footer_flash = getFlash();
if ($_footer_flash): ?>
<div class="alert alert-<?= e($_footer_flash['type']) ?>"
     style="position:fixed;bottom:88px;right:24px;z-index:9999;max-width:320px;box-shadow:var(--shadow-lg);">
  <?= e($_footer_flash['message']) ?>
</div>
<?php endif; ?>

<!-- Admin quick-link (only when logged in as admin) -->
<?php
try {
    if (isLoggedIn() && isset($_pdo_footer)) {
        $__chk = $_pdo_footer->prepare("SELECT role FROM users WHERE id=? LIMIT 1");
        $__chk->execute([$_SESSION['user_id']]);
        $__r = $__chk->fetch();
        if ($__r && $__r['role'] === 'admin'): ?>
<a href="/portfolio_redesign/admin/index.php"
   style="position:fixed;bottom:24px;left:24px;z-index:998;display:flex;align-items:center;gap:8px;padding:9px 16px;background:rgba(168,85,247,0.2);border:1px solid rgba(168,85,247,0.4);border-radius:100px;font-size:0.78rem;font-weight:600;color:#c084fc;backdrop-filter:blur(12px);transition:all 0.2s;"
   onmouseover="this.style.background='rgba(168,85,247,0.35)'"
   onmouseout="this.style.background='rgba(168,85,247,0.2)'">
  🛠️ Admin Dashboard
  <?php if ($_unreadFooter > 0): ?>
  <span style="background:#ef4444;color:#fff;font-size:0.6rem;font-weight:700;padding:2px 6px;border-radius:100px;"><?= $_unreadFooter ?></span>
  <?php endif; ?>
</a>
<?php   endif;
    }
} catch(Exception $e) {}
?>
