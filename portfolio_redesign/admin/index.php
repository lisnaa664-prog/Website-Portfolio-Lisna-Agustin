<?php
$pageTitle = 'Dashboard Overview';
require_once __DIR__ . '/includes/auth_check.php';

// Fetch stats
$pdo = getDB();
$stats = [
  'projects' => $pdo->query("SELECT COUNT(*) FROM projects WHERE is_active=1")->fetchColumn(),
  'skills'   => $pdo->query("SELECT COUNT(*) FROM skills WHERE is_active=1")->fetchColumn(),
  'music'    => $pdo->query("SELECT COUNT(*) FROM favorite_music WHERE is_active=1")->fetchColumn(),
  'messages' => $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn(),
  'unread'   => $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn(),
  'socials'  => $pdo->query("SELECT COUNT(*) FROM social_media WHERE is_active=1")->fetchColumn(),
];

// Recent messages
$recentMessages = $pdo->query(
  "SELECT id, name, email, subject, is_read, created_at FROM messages ORDER BY created_at DESC LIMIT 6"
)->fetchAll();

// Recent projects
$recentProjects = $pdo->query(
  "SELECT id, title, technologies, thumb_emoji, is_active, created_at FROM projects ORDER BY created_at DESC LIMIT 5"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard – Porto.</title>
  <link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css">
</head>
<body>
<div class="admin-layout">

  <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

  <main class="admin-main">
    <?php require_once __DIR__ . '/includes/topnav.php'; ?>

    <div class="admin-content fade-in">

      <!-- BREADCRUMB -->
      <div class="breadcrumb">
        <span>🏠</span>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Dashboard</span>
      </div>

      <!-- WELCOME -->
      <div style="margin-bottom:28px;">
        <h1 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;margin-bottom:6px;">
          Halo, <?= e($adminUser['name']) ?> 👋
        </h1>
        <p style="color:var(--text-muted);font-size:0.9rem;">
          Ini ringkasan portfolio website kamu. Terakhir update: <?= date('d M Y H:i') ?>.
        </p>
      </div>

      <!-- STAT CARDS -->
      <div class="stats-grid">
        <div class="stat-card purple">
          <div class="stat-card-header">
            <div class="stat-label">Projects</div>
            <div class="stat-icon">💼</div>
          </div>
          <div class="stat-value"><?= $stats['projects'] ?></div>
          <div class="stat-change"><a href="projects/index.php" style="color:inherit;">Kelola →</a></div>
        </div>
        <div class="stat-card cyan">
          <div class="stat-card-header">
            <div class="stat-label">Skills</div>
            <div class="stat-icon">⚡</div>
          </div>
          <div class="stat-value"><?= $stats['skills'] ?></div>
          <div class="stat-change"><a href="skills/index.php" style="color:inherit;">Kelola →</a></div>
        </div>
        <div class="stat-card pink">
          <div class="stat-card-header">
            <div class="stat-label">Musik Favorit</div>
            <div class="stat-icon">🎵</div>
          </div>
          <div class="stat-value"><?= $stats['music'] ?></div>
          <div class="stat-change"><a href="music/index.php" style="color:inherit;">Kelola →</a></div>
        </div>
        <div class="stat-card yellow">
          <div class="stat-card-header">
            <div class="stat-label">Sosial Media</div>
            <div class="stat-icon">🔗</div>
          </div>
          <div class="stat-value"><?= $stats['socials'] ?></div>
          <div class="stat-change"><a href="contact/index.php" style="color:inherit;">Kelola →</a></div>
        </div>
        <div class="stat-card red">
          <div class="stat-card-header">
            <div class="stat-label">Pesan Masuk</div>
            <div class="stat-icon">💬</div>
          </div>
          <div class="stat-value"><?= $stats['messages'] ?></div>
          <div class="stat-change <?= $stats['unread']>0 ? 'up':'' ?>">
            <?= $stats['unread'] ?> belum dibaca
          </div>
        </div>
        <div class="stat-card green">
          <div class="stat-card-header">
            <div class="stat-label">IPK</div>
            <div class="stat-icon">🎓</div>
          </div>
          <div class="stat-value">3.72</div>
          <div class="stat-change up">Semester 4</div>
        </div>
      </div>

      <!-- MAIN GRID -->
      <div style="display:grid;grid-template-columns:1.6fr 1fr;gap:24px;">

        <!-- Recent Projects -->
        <div class="content-card">
          <div class="card-header">
            <div class="card-title">💼 Project Terbaru</div>
            <div class="card-actions">
              <a href="projects/create.php" class="btn btn-primary btn-sm">+ Tambah</a>
            </div>
          </div>
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Project</th>
                  <th>Teknologi</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($recentProjects)): ?>
                <tr><td colspan="3"><div class="empty-state"><div class="empty-icon">💼</div><div class="empty-title">Belum ada project</div></div></td></tr>
                <?php else: ?>
                <?php foreach ($recentProjects as $p): ?>
                <tr>
                  <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                      <div class="thumb-emoji-preview" style="width:36px;height:36px;font-size:1.1rem;"><?= e($p['thumb_emoji']) ?></div>
                      <span style="font-size:0.875rem;"><?= e($p['title']) ?></span>
                    </div>
                  </td>
                  <td>
                    <?php foreach (array_slice(explode(',', $p['technologies']), 0, 2) as $tech): ?>
                      <span class="skill-pill"><?= e(trim($tech)) ?></span>
                    <?php endforeach; ?>
                  </td>
                  <td><?= $p['is_active'] ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-gray">Nonaktif</span>' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Recent Messages -->
        <div class="content-card">
          <div class="card-header">
            <div class="card-title">💬 Pesan Terbaru</div>
            <a href="messages/index.php" class="btn btn-glass btn-sm">Lihat Semua</a>
          </div>
          <div class="card-body" style="padding:0;">
            <?php if (empty($recentMessages)): ?>
            <div class="empty-state"><div class="empty-icon">💬</div><div class="empty-title">Belum ada pesan</div></div>
            <?php else: ?>
            <?php foreach ($recentMessages as $m): ?>
            <a href="messages/index.php?id=<?= $m['id'] ?>" style="display:flex;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);transition:background 0.15s;text-decoration:none;" onmouseover="this.style.background='var(--glass)'" onmouseout="this.style.background=''">
              <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;font-size:0.875rem;font-weight:700;color:#fff;flex-shrink:0;">
                <?= strtoupper(substr($m['name'],0,1)) ?>
              </div>
              <div style="flex:1;min-width:0;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                  <span style="font-size:0.82rem;font-weight:<?= $m['is_read']?'500':'700' ?>;color:var(--text);"><?= e($m['name']) ?></span>
                  <?php if (!$m['is_read']): ?><span class="badge badge-purple" style="font-size:0.6rem;">Baru</span><?php endif; ?>
                </div>
                <div style="font-size:0.75rem;color:var(--text-dim);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e(substr($m['subject'] ?: 'Tidak ada subjek',0,40)) ?></div>
              </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

      </div>

      <!-- QUICK ACTIONS -->
      <div class="content-card" style="margin-top:24px;">
        <div class="card-header">
          <div class="card-title">⚡ Aksi Cepat</div>
        </div>
        <div class="card-body" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;">
          <?php
          $actions = [
            ['href'=>'projects/create.php', 'icon'=>'💼', 'label'=>'Tambah Project'],
            ['href'=>'skills/create.php',   'icon'=>'⚡', 'label'=>'Tambah Skill'],
            ['href'=>'music/create.php',    'icon'=>'🎵', 'label'=>'Tambah Musik'],
            ['href'=>'about/index.php',     'icon'=>'👤', 'label'=>'Edit Profile'],
            ['href'=>'cv/index.php',        'icon'=>'📄', 'label'=>'Upload CV'],
            ['href'=>'contact/index.php',   'icon'=>'🔗', 'label'=>'Edit Sosmed'],
          ];
          foreach ($actions as $a): ?>
          <a href="<?= $a['href'] ?>" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;padding:20px;border-radius:var(--radius);background:var(--glass);border:1px solid var(--border);transition:var(--transition);text-align:center;" onmouseover="this.style.borderColor='rgba(168,85,247,0.35)';this.style.background='var(--glass-hov)'" onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--glass)'">
            <span style="font-size:1.6rem;"><?= $a['icon'] ?></span>
            <span style="font-size:0.8rem;font-weight:500;color:var(--text-muted);"><?= $a['label'] ?></span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </main>
</div>

<!-- Confirm Delete Modal -->
<div class="modal-overlay" id="confirmModal">
  <div class="modal modal-sm">
    <div class="modal-body" style="text-align:center;padding:32px;">
      <div class="confirm-icon">🗑️</div>
      <h3 style="font-size:1rem;font-weight:700;margin-bottom:8px;">Konfirmasi Hapus</h3>
      <p class="confirm-text" id="confirmMessage">Yakin ingin menghapus data ini?</p>
    </div>
    <div class="modal-footer" style="justify-content:center;gap:12px;">
      <button class="btn btn-glass" onclick="closeModal('confirmModal')">Batal</button>
      <button class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
    </div>
  </div>
</div>

<script src="/portfolio_redesign/admin/js/admin.js"></script>
</body>
</html>
