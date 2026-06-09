<?php
$pageTitle = 'Projects';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo = getDB();

// Delete
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("SELECT thumbnail FROM projects WHERE id=?");
    $stmt->execute([$_POST['delete_id']]);
    $row = $stmt->fetch();
    if ($row && $row['thumbnail'] && file_exists(dirname(__DIR__,2) . '/' . $row['thumbnail'])) {
        unlink(dirname(__DIR__,2) . '/' . $row['thumbnail']);
    }
    $pdo->prepare("DELETE FROM projects WHERE id=?")->execute([$_POST['delete_id']]);
    setFlash('success','Project berhasil dihapus.');
    redirect('/portfolio_redesign/admin/projects/index.php');
}

// Toggle active
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['toggle_id'])) {
    $pdo->prepare("UPDATE projects SET is_active = NOT is_active WHERE id=?")->execute([$_POST['toggle_id']]);
    redirect('/portfolio_redesign/admin/projects/index.php');
}

$flash = getFlash();
$projects = $pdo->query("SELECT * FROM projects ORDER BY sort_order ASC, created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Projects – Admin</title>
  <link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css">
</head>
<body>
<div class="admin-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="admin-main">
    <?php require_once __DIR__ . '/../includes/topnav.php'; ?>
    <div class="admin-content fade-in">

      <div class="breadcrumb">
        <a href="/portfolio_redesign/admin/index.php">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Projects</span>
      </div>

      <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
      <?php endif; ?>

      <div class="content-card">
        <div class="card-header">
          <div class="card-title">💼 Daftar Project (<?= count($projects) ?>)</div>
          <div class="card-actions">
            <div class="search-box"><span>🔍</span><input type="text" id="tableSearch" placeholder="Cari project..."></div>
            <a href="create.php" class="btn btn-primary">+ Tambah Project</a>
          </div>
        </div>
        <div class="table-wrapper">
          <table data-searchable>
            <thead>
              <tr>
                <th>#</th><th>Thumbnail</th><th>Judul</th><th>Teknologi</th>
                <th>GitHub</th><th>Status</th><th>Urutan</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($projects)): ?>
              <tr><td colspan="8">
                <div class="empty-state">
                  <div class="empty-icon">💼</div>
                  <div class="empty-title">Belum ada project</div>
                  <div class="empty-desc">Klik "Tambah Project" untuk mulai.</div>
                </div>
              </td></tr>
              <?php else: ?>
              <?php foreach ($projects as $i => $p): ?>
              <tr>
                <td style="color:var(--text-dim)"><?= $i+1 ?></td>
                <td>
                  <?php if ($p['thumbnail']): ?>
                    <img src="/portfolio_redesign/<?= e($p['thumbnail']) ?>" class="thumb-preview" alt="">
                  <?php else: ?>
                    <div class="thumb-emoji-preview"><?= e($p['thumb_emoji'] ?: '💻') ?></div>
                  <?php endif; ?>
                </td>
                <td><?= e($p['title']) ?></td>
                <td>
                  <?php foreach (explode(',', $p['technologies']) as $tech): ?>
                    <span class="skill-pill"><?= e(trim($tech)) ?></span>
                  <?php endforeach; ?>
                </td>
                <td>
                  <?php if ($p['github_url']): ?>
                    <a href="<?= e($p['github_url']) ?>" target="_blank" style="color:var(--primary);font-size:0.8rem;">🔗 Link</a>
                  <?php else: ?>
                    <span style="color:var(--text-dim);font-size:0.8rem;">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="toggle_id" value="<?= $p['id'] ?>">
                    <label class="toggle-switch" title="Toggle aktif">
                      <input type="checkbox" class="toggle-active" <?= $p['is_active']?'checked':'' ?>>
                      <span class="toggle-slider"></span>
                    </label>
                  </form>
                </td>
                <td style="color:var(--text-dim)"><?= $p['sort_order'] ?></td>
                <td>
                  <div class="td-actions">
                    <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-glass btn-icon" title="Edit">✏️</a>
                    <button class="btn btn-danger btn-icon" title="Hapus"
                      onclick="confirmDelete('Hapus project &quot;<?= addslashes(e($p['title'])) ?>&quot;?','del<?= $p['id'] ?>')">🗑️</button>
                    <form id="del<?= $p['id'] ?>" method="POST" style="display:none;">
                      <input type="hidden" name="delete_id" value="<?= $p['id'] ?>">
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</div>
<div class="modal-overlay" id="confirmModal">
  <div class="modal modal-sm">
    <div class="modal-body" style="text-align:center;padding:32px;">
      <div class="confirm-icon">🗑️</div>
      <h3 style="font-size:1rem;font-weight:700;margin-bottom:8px;">Konfirmasi Hapus</h3>
      <p class="confirm-text" id="confirmMessage"></p>
    </div>
    <div class="modal-footer" style="justify-content:center;">
      <button class="btn btn-glass" onclick="closeModal('confirmModal')">Batal</button>
      <button class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
    </div>
  </div>
</div>
<script src="/portfolio_redesign/admin/js/admin.js"></script>
</body></html>
