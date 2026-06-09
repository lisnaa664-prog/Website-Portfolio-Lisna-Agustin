<?php
/**
 * config/navbar.php – Navbar with admin-aware links
 */
$_isAdmin = false;
if (isLoggedIn()) {
    try {
        $_pdo_nav = getDB();
        $_r = $_pdo_nav->prepare("SELECT role FROM users WHERE id=? LIMIT 1");
        $_r->execute([$_SESSION['user_id']]);
        $_row = $_r->fetch();
        $_isAdmin = ($_row && $_row['role'] === 'admin');
    } catch(Exception $e) {}
}
?>
<nav class="navbar" id="navbar">
  <div class="container">
    <a href="/portfolio_redesign/index.php" class="nav-brand">Porto<span>.</span></a>

    <ul class="nav-links">
      <li><a href="/portfolio_redesign/index.php">Home</a></li>
      <li><a href="/portfolio_redesign/about.php">About</a></li>
      <li><a href="/portfolio_redesign/portfolio.php">Portfolio</a></li>
      <li><a href="/portfolio_redesign/skills.php">Skills</a></li>
      <li><a href="/portfolio_redesign/cv.php">CV</a></li>
      <li><a href="/portfolio_redesign/contact.php">Contact</a></li>
    </ul>

    <div class="nav-cta">
      <?php if (isLoggedIn()): ?>
        <?php if ($_isAdmin): ?>
          <a href="/portfolio_redesign/admin/index.php" class="btn btn-glass" style="padding:8px 16px;font-size:0.78rem;gap:6px;">
            🛠️ Dashboard
          </a>
        <?php endif; ?>
        <a href="/portfolio_redesign/auth/logout.php" class="btn btn-outline" style="padding:8px 18px;font-size:0.8rem;">Logout</a>
      <?php else: ?>
        <a href="/portfolio_redesign/login.php" style="font-size:0.875rem;color:var(--text-muted);">Login</a>
        <a href="/portfolio_redesign/register.php" class="btn btn-primary" style="padding:9px 22px;font-size:0.82rem;">Daftar</a>
      <?php endif; ?>
    </div>

    <button class="nav-toggle" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>
