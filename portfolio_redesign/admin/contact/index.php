<?php
$pageTitle = 'Contact & Sosial Media';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo   = getDB();
$flash = getFlash();

// DELETE social media
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_social'])) {
    $pdo->prepare("DELETE FROM social_media WHERE id=?")->execute([$_POST['delete_social']]);
    setFlash('success', 'Sosial media berhasil dihapus.');
    redirect('/portfolio_redesign/admin/contact/index.php');
}

// TOGGLE social media
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_social'])) {
    $pdo->prepare("UPDATE social_media SET is_active = NOT is_active WHERE id=?")->execute([$_POST['toggle_social']]);
    redirect('/portfolio_redesign/admin/contact/index.php');
}

// ADD social media
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_social'])) {
    $platform   = trim($_POST['platform'] ?? '');
    $label      = trim($_POST['label'] ?? '');
    $url        = trim($_POST['url'] ?? '');
    $icon       = trim($_POST['icon'] ?? '🔗');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    if ($platform && $url) {
        $pdo->prepare("INSERT INTO social_media (platform,label,url,icon,sort_order) VALUES (?,?,?,?,?)")
            ->execute([$platform, $label ?: $platform, $url, $icon, $sort_order]);
        setFlash('success', 'Sosial media berhasil ditambahkan!');
    }
    redirect('/portfolio_redesign/admin/contact/index.php');
}

// EDIT social media
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_social'])) {
    $id         = intval($_POST['edit_id']);
    $platform   = trim($_POST['platform'] ?? '');
    $label      = trim($_POST['label'] ?? '');
    $url        = trim($_POST['url'] ?? '');
    $icon       = trim($_POST['icon'] ?? '🔗');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    if ($url) {
        $pdo->prepare("UPDATE social_media SET platform=?,label=?,url=?,icon=?,sort_order=? WHERE id=?")
            ->execute([$platform, $label, $url, $icon, $sort_order, $id]);
        setFlash('success', 'Sosial media berhasil diupdate!');
    }
    redirect('/portfolio_redesign/admin/contact/index.php');
}

$socials = $pdo->query("SELECT * FROM social_media ORDER BY sort_order, id")->fetchAll();
$about   = $pdo->query("SELECT * FROM about_me LIMIT 1")->fetch();
// Edit target
$editId  = intval($_GET['edit'] ?? 0);
$editRow = null;
if ($editId) {
    $s = $pdo->prepare("SELECT * FROM social_media WHERE id=?"); $s->execute([$editId]);
    $editRow = $s->fetch();
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Contact & Sosmed – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
<?php require_once __DIR__ . '/../includes/topnav.php'; ?>
<div class="admin-content fade-in">
  <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">Contact & Sosmed</span></div>
  <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:24px;align-items:start;">

    <!-- ADD / EDIT FORM -->
    <div class="content-card">
      <div class="card-header"><div class="card-title"><?= $editRow ? '✏️ Edit Sosial Media' : '➕ Tambah Sosial Media' ?></div></div>
      <div class="card-body">
        <form method="POST">
          <?php if ($editRow): ?>
            <input type="hidden" name="edit_social" value="1">
            <input type="hidden" name="edit_id" value="<?= $editRow['id'] ?>">
          <?php else: ?>
            <input type="hidden" name="add_social" value="1">
          <?php endif; ?>
          <div class="form-row">
            <div class="form-group">
              <label>Platform *</label>
              <input type="text" name="platform" class="form-control" value="<?= e($editRow['platform'] ?? '') ?>" placeholder="GitHub, Instagram..." required>
            </div>
            <div class="form-group">
              <label>Label / Nama</label>
              <input type="text" name="label" class="form-control" value="<?= e($editRow['label'] ?? '') ?>" placeholder="username kamu">
            </div>
          </div>
          <div class="form-group">
            <label>URL / Link *</label>
            <input type="text" name="url" class="form-control" value="<?= e($editRow['url'] ?? '') ?>" placeholder="https://..." required>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Icon / Emoji</label>
              <input type="text" name="icon" class="form-control" value="<?= e($editRow['icon'] ?? '🔗') ?>" maxlength="10">
            </div>
            <div class="form-group">
              <label>Urutan</label>
              <input type="number" name="sort_order" class="form-control" value="<?= e($editRow['sort_order'] ?? 0) ?>" min="0">
            </div>
          </div>
          <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">💾 <?= $editRow ? 'Update' : 'Tambah' ?></button>
            <?php if ($editRow): ?><a href="index.php" class="btn btn-glass">Batal</a><?php endif; ?>
          </div>
        </form>

        <hr style="border:none;border-top:1px solid var(--border);margin:28px 0;">

        <!-- Contact info quick edit -->
        <h4 style="font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:16px;text-transform:uppercase;letter-spacing:0.08em;">Info Kontak (dari About Me)</h4>
        <?php if ($about): ?>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <?php $infos = [['📧','Email',$about['email']],['📱','WhatsApp',$about['phone']],['📍','Lokasi',$about['location']]];
          foreach ($infos as [$icon,$lbl,$val]): ?>
          <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--glass);border:1px solid var(--border);border-radius:var(--radius-sm);">
            <span><?= $icon ?></span>
            <div>
              <div style="font-size:0.68rem;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.06em;"><?= $lbl ?></div>
              <div style="font-size:0.82rem;color:var(--text-muted);"><?= e($val ?? '—') ?></div>
            </div>
            <a href="/portfolio_redesign/admin/about/index.php" style="margin-left:auto;font-size:0.75rem;color:var(--primary);">Edit →</a>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- SOCIALS LIST -->
    <div class="content-card">
      <div class="card-header"><div class="card-title">🔗 Daftar Sosial Media (<?= count($socials) ?>)</div></div>
      <div class="table-wrapper">
        <table data-searchable>
          <thead><tr><th>Icon</th><th>Platform</th><th>URL</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody>
            <?php if (empty($socials)): ?>
            <tr><td colspan="6"><div class="empty-state"><div class="empty-icon">🔗</div><div class="empty-title">Belum ada sosial media</div></div></td></tr>
            <?php else: foreach ($socials as $s): ?>
            <tr>
              <td style="font-size:1.4rem;"><?= e($s['icon']) ?></td>
              <td><strong><?= e($s['platform']) ?></strong><br><span style="font-size:0.75rem;color:var(--text-dim);"><?= e($s['label']) ?></span></td>
              <td><a href="<?= e($s['url']) ?>" target="_blank" style="color:var(--primary);font-size:0.78rem;max-width:160px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($s['url']) ?></a></td>
              <td style="color:var(--text-dim)"><?= $s['sort_order'] ?></td>
              <td>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="toggle_social" value="<?= $s['id'] ?>">
                  <label class="toggle-switch"><input type="checkbox" class="toggle-active" <?= $s['is_active']?'checked':'' ?>><span class="toggle-slider"></span></label>
                </form>
              </td>
              <td>
                <div class="td-actions">
                  <a href="?edit=<?= $s['id'] ?>" class="btn btn-glass btn-icon" title="Edit">✏️</a>
                  <form method="POST" style="display:inline;" id="delsoc<?= $s['id'] ?>">
                    <input type="hidden" name="delete_social" value="<?= $s['id'] ?>">
                  </form>
                  <button class="btn btn-danger btn-icon" onclick="confirmDelete('Hapus <?= addslashes(e($s['platform'])) ?>?','delsoc<?= $s['id'] ?>')">🗑️</button>
                </div>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
</main>
</div>
<div class="modal-overlay" id="confirmModal">
  <div class="modal modal-sm"><div class="modal-body" style="text-align:center;padding:32px;">
    <div class="confirm-icon">🗑️</div><p class="confirm-text" id="confirmMessage"></p></div>
    <div class="modal-footer" style="justify-content:center;">
      <button class="btn btn-glass" onclick="closeModal('confirmModal')">Batal</button>
      <button class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
    </div>
  </div>
</div>
<script src="/portfolio_redesign/admin/js/admin.js"></script>
</body></html>
