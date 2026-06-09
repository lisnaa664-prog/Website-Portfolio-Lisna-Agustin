<?php
// admin/includes/topnav.php
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<header class="admin-topnav">
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">☰</button>

  <div class="topnav-title"><?= e($pageTitle) ?></div>

  <div class="topnav-search">
    <span class="topnav-search-icon">🔍</span>
    <input type="text" placeholder="Cari sesuatu..." id="globalSearch" autocomplete="off">
  </div>

  <div class="topnav-actions">
    <a href="/portfolio_redesign/admin/messages/index.php" class="topnav-btn" title="Pesan Masuk">
      💬
      <?php if (($unreadCount ?? 0) > 0): ?>
        <span class="badge"><?= $unreadCount ?></span>
      <?php endif; ?>
    </a>
    <a href="/portfolio_redesign/index.php" target="_blank" class="topnav-btn" title="Lihat Website">🌐</a>
  </div>
</header>
