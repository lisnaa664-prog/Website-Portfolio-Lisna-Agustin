<?php
$pageTitle = 'CV Management';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo   = getDB();
$flash = getFlash();

// Delete a CV file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("SELECT filename FROM cv_files WHERE id=?");
    $stmt->execute([$_POST['delete_id']]);
    $row = $stmt->fetch();
    if ($row) {
        $path = dirname(__DIR__, 2) . '/uploads/cv/' . $row['filename'];
        if (file_exists($path)) unlink($path);
        $pdo->prepare("DELETE FROM cv_files WHERE id=?")->execute([$_POST['delete_id']]);
    }
    setFlash('success', 'CV berhasil dihapus.');
    redirect('/portfolio_redesign/admin/cv/index.php');
}

// Set active CV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_active'])) {
    $pdo->exec("UPDATE cv_files SET is_active=0");
    $pdo->prepare("UPDATE cv_files SET is_active=1 WHERE id=?")->execute([$_POST['set_active']]);
    setFlash('success', 'CV aktif berhasil diubah.');
    redirect('/portfolio_redesign/admin/cv/index.php');
}

// Upload new CV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cv_file'])) {
    $file = $_FILES['cv_file'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        setFlash('error', 'Hanya file PDF yang diizinkan.');
    } elseif ($file['size'] > 10 * 1024 * 1024) {
        setFlash('error', 'Ukuran file maksimal 10 MB.');
    } else {
        $newName  = 'CV_' . date('Ymd_His') . '.pdf';
        $uploadDir = dirname(__DIR__, 2) . '/uploads/cv/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
            // Deactivate old
            $pdo->exec("UPDATE cv_files SET is_active=0");
            $pdo->prepare("INSERT INTO cv_files (filename, original_name, file_size, is_active) VALUES (?,?,?,1)")
                ->execute([$newName, $file['name'], $file['size']]);
            setFlash('success', 'CV berhasil diupload dan dijadikan aktif!');
        } else {
            setFlash('error', 'Gagal mengupload file.');
        }
    }
    redirect('/portfolio_redesign/admin/cv/index.php');
}

$cvFiles = $pdo->query("SELECT * FROM cv_files ORDER BY uploaded_at DESC")->fetchAll();
$activeCV = $pdo->query("SELECT * FROM cv_files WHERE is_active=1 LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>CV – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
<?php require_once __DIR__ . '/../includes/topnav.php'; ?>
<div class="admin-content fade-in">
  <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">CV</span></div>
  <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    <!-- Upload Form -->
    <div class="content-card">
      <div class="card-header"><div class="card-title">📤 Upload CV Baru</div></div>
      <div class="card-body">
        <?php if ($activeCV): ?>
        <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:var(--radius-sm);padding:16px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
          <span style="font-size:1.5rem;">📄</span>
          <div>
            <div style="font-size:0.8rem;color:var(--green);font-weight:600;">CV Aktif Saat Ini</div>
            <div style="font-size:0.85rem;color:var(--text-muted);"><?= e($activeCV['original_name']) ?></div>
            <div style="font-size:0.72rem;color:var(--text-dim);"><?= round($activeCV['file_size']/1024) ?> KB · <?= date('d M Y', strtotime($activeCV['uploaded_at'])) ?></div>
          </div>
          <a href="/portfolio_redesign/uploads/cv/<?= e($activeCV['filename']) ?>" target="_blank" class="btn btn-success btn-sm" style="margin-left:auto;">⬇ Download</a>
        </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
          <div class="file-upload" style="margin-bottom:20px;">
            <input type="file" name="cv_file" accept=".pdf" required>
            <div class="file-upload-icon">📄</div>
            <div class="file-upload-text">Klik atau drag & drop file CV di sini</div>
            <div class="file-upload-hint">Format PDF saja. Maks 10 MB.</div>
          </div>
          <p style="font-size:0.78rem;color:var(--text-dim);margin-bottom:16px;">* CV baru yang diupload akan otomatis menjadi CV aktif yang tampil di website.</p>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">📤 Upload CV</button>
        </form>
      </div>
    </div>

    <!-- CV History -->
    <div class="content-card">
      <div class="card-header"><div class="card-title">📋 Riwayat CV (<?= count($cvFiles) ?>)</div></div>
      <div class="card-body" style="padding:0;">
        <?php if (empty($cvFiles)): ?>
          <div class="empty-state"><div class="empty-icon">📄</div><div class="empty-title">Belum ada CV diupload</div></div>
        <?php else: ?>
        <?php foreach ($cvFiles as $cv): ?>
        <div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border);">
          <span style="font-size:1.5rem;flex-shrink:0;">📄</span>
          <div style="flex:1;min-width:0;">
            <div style="font-size:0.82rem;color:var(--text);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($cv['original_name']) ?></div>
            <div style="font-size:0.72rem;color:var(--text-dim);"><?= round($cv['file_size']/1024) ?> KB · <?= date('d M Y H:i', strtotime($cv['uploaded_at'])) ?></div>
          </div>
          <?php if ($cv['is_active']): ?>
            <span class="badge badge-green">✓ Aktif</span>
          <?php else: ?>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="set_active" value="<?= $cv['id'] ?>">
              <button type="submit" class="btn btn-glass btn-sm">Aktifkan</button>
            </form>
          <?php endif; ?>
          <a href="/portfolio_redesign/uploads/cv/<?= e($cv['filename']) ?>" target="_blank" class="btn btn-glass btn-icon" title="Download">⬇</a>
          <form method="POST" style="display:inline;" id="delcv<?= $cv['id'] ?>">
            <input type="hidden" name="delete_id" value="<?= $cv['id'] ?>">
          </form>
          <button class="btn btn-danger btn-icon" title="Hapus" onclick="confirmDelete('Hapus CV ini?','delcv<?= $cv['id'] ?>')">🗑️</button>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
</main>
</div>
<div class="modal-overlay" id="confirmModal">
  <div class="modal modal-sm"><div class="modal-body" style="text-align:center;padding:32px;">
    <div class="confirm-icon">🗑️</div><h3 style="font-size:1rem;font-weight:700;margin-bottom:8px;">Konfirmasi Hapus</h3>
    <p class="confirm-text" id="confirmMessage"></p></div>
    <div class="modal-footer" style="justify-content:center;">
      <button class="btn btn-glass" onclick="closeModal('confirmModal')">Batal</button>
      <button class="btn btn-danger" id="confirmDeleteBtn">Ya, Hapus</button>
    </div>
  </div>
</div>
<script src="/portfolio_redesign/admin/js/admin.js"></script>
</body></html>
