<?php
$pageTitle = 'Skills';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo = getDB();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete_id'])) {
    $pdo->prepare("DELETE FROM skills WHERE id=?")->execute([$_POST['delete_id']]);
    setFlash('success','Skill berhasil dihapus.');
    redirect('/portfolio_redesign/admin/skills/index.php');
}

$flash = getFlash();
$skills = $pdo->query("SELECT * FROM skills ORDER BY category, sort_order, name")->fetchAll();
$grouped = ['hard'=>[],'soft'=>[],'tool'=>[]];
foreach ($skills as $s) $grouped[$s['category']][] = $s;
$catLabels = ['hard'=>'Hard Skills','soft'=>'Soft Skills','tool'=>'Tools'];
$catColors = ['hard'=>'purple','soft'=>'cyan','tool'=>'yellow'];
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Skills – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="admin-main">
    <?php require_once __DIR__ . '/../includes/topnav.php'; ?>
    <div class="admin-content fade-in">
      <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">Skills</span></div>
      <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>

      <div class="content-card">
        <div class="card-header">
          <div class="card-title">⚡ Daftar Skill (<?= count($skills) ?>)</div>
          <div class="card-actions">
            <div class="search-box"><span>🔍</span><input id="tableSearch" placeholder="Cari skill..."></div>
            <a href="create.php" class="btn btn-primary">+ Tambah Skill</a>
          </div>
        </div>
        <div class="table-wrapper">
          <table data-searchable>
            <thead><tr><th>#</th><th>Skill</th><th>Kategori</th><th>Level</th><th>Icon</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
              <?php if (empty($skills)): ?>
              <tr><td colspan="7"><div class="empty-state"><div class="empty-icon">⚡</div><div class="empty-title">Belum ada skill</div></div></td></tr>
              <?php else: $i=1; foreach ($skills as $s): ?>
              <tr>
                <td style="color:var(--text-dim)"><?= $i++ ?></td>
                <td><?= e($s['name']) ?></td>
                <td><span class="badge badge-<?= $catColors[$s['category']] ?>"><?= $catLabels[$s['category']] ?></span></td>
                <td>
                  <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:80px;height:4px;background:rgba(255,255,255,0.08);border-radius:2px;overflow:hidden;">
                      <div style="height:100%;width:<?= $s['level'] ?>%;background:linear-gradient(90deg,var(--primary),var(--pink));border-radius:2px;"></div>
                    </div>
                    <span style="font-size:0.75rem;color:var(--primary);font-weight:600;"><?= $s['level'] ?>%</span>
                  </div>
                </td>
                <td style="font-size:1.3rem;"><?= e($s['icon']) ?></td>
                <td><?= $s['is_active'] ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-gray">Nonaktif</span>' ?></td>
                <td>
                  <div class="td-actions">
                    <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-glass btn-icon">✏️</a>
                    <button class="btn btn-danger btn-icon" onclick="confirmDelete('Hapus skill &quot;<?= addslashes(e($s['name'])) ?>&quot;?','del<?= $s['id'] ?>')">🗑️</button>
                    <form id="del<?= $s['id'] ?>" method="POST" style="display:none;">
                      <input type="hidden" name="delete_id" value="<?= $s['id'] ?>">
                    </form>
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
    <div class="confirm-icon">🗑️</div><h3 style="font-size:1rem;font-weight:700;margin-bottom:8px;">Hapus Skill?</h3>
    <p class="confirm-text" id="confirmMessage"></p></div>
    <div class="modal-footer" style="justify-content:center;">
      <button class="btn btn-glass" onclick="closeModal('confirmModal')">Batal</button>
      <button class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
    </div>
  </div>
</div>
<script src="/portfolio_redesign/admin/js/admin.js"></script></body></html>
