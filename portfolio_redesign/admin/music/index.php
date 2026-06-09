<?php
$pageTitle = 'Musik Favorit';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo   = getDB();
$flash = getFlash();

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("SELECT thumbnail, audio_file FROM favorite_music WHERE id=?");
    $stmt->execute([$_POST['delete_id']]);
    $row = $stmt->fetch();
    if ($row) {
        $base = dirname(__DIR__, 2) . '/';
        if ($row['thumbnail'] && file_exists($base . $row['thumbnail'])) unlink($base . $row['thumbnail']);
        if ($row['audio_file'] && file_exists($base . $row['audio_file'])) unlink($base . $row['audio_file']);
        $pdo->prepare("DELETE FROM favorite_music WHERE id=?")->execute([$_POST['delete_id']]);
    }
    setFlash('success', 'Lagu berhasil dihapus.');
    redirect('/portfolio_redesign/admin/music/index.php');
}

// TOGGLE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    $pdo->prepare("UPDATE favorite_music SET is_active = NOT is_active WHERE id=?")->execute([$_POST['toggle_id']]);
    redirect('/portfolio_redesign/admin/music/index.php');
}

$songs = $pdo->query("SELECT * FROM favorite_music ORDER BY sort_order, id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Musik Favorit – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
<?php require_once __DIR__ . '/../includes/topnav.php'; ?>
<div class="admin-content fade-in">
  <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">Musik Favorit</span></div>
  <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>

  <div class="content-card">
    <div class="card-header">
      <div class="card-title">🎵 Daftar Lagu Favorit (<?= count($songs) ?>)</div>
      <div class="card-actions">
        <div class="search-box"><span>🔍</span><input id="tableSearch" placeholder="Cari lagu..."></div>
        <a href="create.php" class="btn btn-primary">+ Tambah Lagu</a>
      </div>
    </div>
    <div class="table-wrapper">
      <table data-searchable>
        <thead><tr><th>#</th><th>Art</th><th>Judul</th><th>Artis</th><th>Tahun</th><th>Durasi</th><th>Audio</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          <?php if (empty($songs)): ?>
          <tr><td colspan="9"><div class="empty-state"><div class="empty-icon">🎵</div><div class="empty-title">Belum ada lagu</div><div class="empty-desc">Klik "+ Tambah Lagu" untuk menambahkan.</div></div></td></tr>
          <?php else: foreach ($songs as $i => $s): ?>
          <tr>
            <td style="color:var(--text-dim)"><?= $i+1 ?></td>
            <td>
              <?php if ($s['thumbnail']): ?>
                <img src="/portfolio_redesign/<?= e($s['thumbnail']) ?>" class="thumb-preview" style="width:44px;height:44px;border-radius:8px;" alt="">
              <?php else: ?>
                <div style="width:44px;height:44px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;background:<?= e($s['bg_color']??'var(--glass)') ?>;flex-shrink:0;"><?= e($s['thumb_emoji']) ?></div>
              <?php endif; ?>
            </td>
            <td style="font-weight:600;"><?= e($s['title']) ?></td>
            <td><?= e($s['artist']) ?></td>
            <td style="color:var(--text-dim)"><?= e($s['year'] ?? '—') ?></td>
            <td style="color:var(--text-dim)"><?= e($s['duration'] ?? '—') ?></td>
            <td>
              <?php if ($s['audio_url']): ?>
                <span class="badge badge-purple">🔗 URL</span>
              <?php elseif ($s['audio_file']): ?>
                <span class="badge badge-green">📁 File</span>
              <?php else: ?>
                <span class="badge badge-gray">Tidak ada</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="toggle_id" value="<?= $s['id'] ?>">
                <label class="toggle-switch"><input type="checkbox" class="toggle-active" <?= $s['is_active']?'checked':'' ?>><span class="toggle-slider"></span></label>
              </form>
            </td>
            <td>
              <div class="td-actions">
                <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-glass btn-icon" title="Edit">✏️</a>
                <form method="POST" style="display:none;" id="delmus<?= $s['id'] ?>"><input type="hidden" name="delete_id" value="<?= $s['id'] ?>"></form>
                <button class="btn btn-danger btn-icon" onclick="confirmDelete('Hapus lagu &quot;<?= addslashes(e($s['title'])) ?>&quot;?','delmus<?= $s['id'] ?>')">🗑️</button>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</main>
</div>
<div class="modal-overlay" id="confirmModal">
  <div class="modal modal-sm"><div class="modal-body" style="text-align:center;padding:32px;">
    <div class="confirm-icon">🗑️</div><h3 style="font-size:1rem;font-weight:700;margin-bottom:8px;">Hapus Lagu?</h3>
    <p class="confirm-text" id="confirmMessage"></p></div>
    <div class="modal-footer" style="justify-content:center;">
      <button class="btn btn-glass" onclick="closeModal('confirmModal')">Batal</button>
      <button class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
    </div>
  </div>
</div>
<script src="/portfolio_redesign/admin/js/admin.js"></script>
</body></html>
