<?php
// admin/includes/sidebar.php
// Ambil unread message count
$unreadCount = 0;
try {
    $pdo2 = getDB();
    $unreadCount = $pdo2->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
} catch(Exception $e) {}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo-icon">🚀</div>
    <div class="sidebar-logo">Porto<span>.</span>Admin</div>
  </div>

  <nav class="sidebar-nav">
    <div class="sidebar-section-label">Dashboard</div>
    <a href="/portfolio_redesign/admin/index.php"
       class="sidebar-item <?= $currentPage==='index.php' && strpos($_SERVER['PHP_SELF'],'admin')!==false ? 'active':'' ?>">
      <span class="sidebar-icon">📊</span>
      <span class="sidebar-label">Overview</span>
    </a>

    <div class="sidebar-section-label">Konten Portfolio</div>
    <a href="/portfolio_redesign/admin/projects/index.php"
       class="sidebar-item <?= strpos($_SERVER['PHP_SELF'],'projects')!==false ? 'active':'' ?>">
      <span class="sidebar-icon">💼</span>
      <span class="sidebar-label">Projects</span>
    </a>
    <a href="/portfolio_redesign/admin/skills/index.php"
       class="sidebar-item <?= strpos($_SERVER['PHP_SELF'],'skills')!==false ? 'active':'' ?>">
      <span class="sidebar-icon">⚡</span>
      <span class="sidebar-label">Skills</span>
    </a>
    <a href="/portfolio_redesign/admin/about/index.php"
       class="sidebar-item <?= strpos($_SERVER['PHP_SELF'],'about')!==false ? 'active':'' ?>">
      <span class="sidebar-icon">👤</span>
      <span class="sidebar-label">About Me</span>
    </a>
    <a href="/portfolio_redesign/admin/cv/index.php"
       class="sidebar-item <?= strpos($_SERVER['PHP_SELF'],'/cv/')!==false ? 'active':'' ?>">
      <span class="sidebar-icon">📄</span>
      <span class="sidebar-label">CV</span>
    </a>
    <a href="/portfolio_redesign/admin/contact/index.php"
       class="sidebar-item <?= strpos($_SERVER['PHP_SELF'],'contact')!==false ? 'active':'' ?>">
      <span class="sidebar-icon">📬</span>
      <span class="sidebar-label">Contact & Sosmed</span>
    </a>
    <a href="/portfolio_redesign/admin/music/index.php"
       class="sidebar-item <?= strpos($_SERVER['PHP_SELF'],'music')!==false ? 'active':'' ?>">
      <span class="sidebar-icon">🎵</span>
      <span class="sidebar-label">Musik Favorit</span>
    </a>

    <div class="sidebar-section-label">Inbox</div>
    <a href="/portfolio_redesign/admin/messages/index.php"
       class="sidebar-item <?= strpos($_SERVER['PHP_SELF'],'messages')!==false ? 'active':'' ?>">
      <span class="sidebar-icon">💬</span>
      <span class="sidebar-label">Pesan Masuk</span>
      <?php if ($unreadCount > 0): ?>
        <span class="sidebar-badge"><?= $unreadCount ?></span>
      <?php endif; ?>
    </a>

    <div class="sidebar-section-label">Website</div>
    <a href="/portfolio_redesign/index.php" target="_blank" class="sidebar-item">
      <span class="sidebar-icon">🌐</span>
      <span class="sidebar-label">Lihat Website</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar"><?= strtoupper(substr($adminUser['name'],0,1)) ?></div>
      <div class="sidebar-user-info">
        <div class="sidebar-user-name"><?= e($adminUser['name']) ?></div>
        <div class="sidebar-user-role">Administrator</div>
      </div>
    </div>
    <a href="/portfolio_redesign/auth/logout.php"
       style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:var(--radius-sm);color:var(--text-dim);font-size:0.8rem;transition:var(--transition);margin-top:6px;"
       onmouseover="this.style.color='var(--red)';this.style.background='rgba(239,68,68,0.08)';"
       onmouseout="this.style.color='var(--text-dim)';this.style.background='transparent';">
      <span>🚪</span> <span class="sidebar-label">Logout</span>
    </a>
  </div>
</aside>
