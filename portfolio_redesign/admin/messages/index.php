<?php
$pageTitle = 'Pesan Masuk';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo   = getDB();
$flash = getFlash();

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $pdo->prepare("DELETE FROM messages WHERE id=?")->execute([$_POST['delete_id']]);
    setFlash('success', 'Pesan berhasil dihapus.');
    redirect('/portfolio_redesign/admin/messages/index.php');
}

// Mark all read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
    $pdo->exec("UPDATE messages SET is_read=1");
    setFlash('success', 'Semua pesan ditandai sudah dibaca.');
    redirect('/portfolio_redesign/admin/messages/index.php');
}

// Mark single read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    $pdo->prepare("UPDATE messages SET is_read=1 WHERE id=?")->execute([$_POST['mark_read']]);
    redirect('/portfolio_redesign/admin/messages/index.php');
}

// Delete all read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_read'])) {
    $pdo->exec("DELETE FROM messages WHERE is_read=1");
    setFlash('success', 'Pesan yang sudah dibaca berhasil dihapus.');
    redirect('/portfolio_redesign/admin/messages/index.php');
}

// Pagination
$perPage = 12;
$page    = max(1, intval($_GET['page'] ?? 1));
$total   = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$pages   = ceil($total / $perPage);
$offset  = ($page - 1) * $perPage;

// Active message detail
$activeId  = intval($_GET['id'] ?? 0);
$activeMsg = null;
if ($activeId) {
    $s = $pdo->prepare("SELECT * FROM messages WHERE id=?");
    $s->execute([$activeId]);
    $activeMsg = $s->fetch();
    if ($activeMsg && !$activeMsg['is_read']) {
        $pdo->prepare("UPDATE messages SET is_read=1 WHERE id=?")->execute([$activeId]);
        $activeMsg['is_read'] = 1;
    }
}

$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}")->fetchAll();
$unreadTotal = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read=0")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Pesan Masuk – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css">
<style>
.msg-list { border-right:1px solid var(--border); height:calc(100vh - 200px); overflow-y:auto; }
.msg-item { display:flex; gap:12px; padding:14px 16px; border-bottom:1px solid rgba(255,255,255,0.04); cursor:pointer; transition:background 0.15s; text-decoration:none; }
.msg-item:hover { background:var(--glass); }
.msg-item.active { background:rgba(168,85,247,0.08); border-left:3px solid var(--primary); }
.msg-item.unread .msg-sender { font-weight:700; color:var(--text); }
.msg-avatar { width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.875rem; font-weight:700; color:#fff; flex-shrink:0; background:linear-gradient(135deg,var(--primary),var(--accent)); }
.msg-sender { font-size:0.82rem; color:var(--text-muted); }
.msg-preview { font-size:0.75rem; color:var(--text-dim); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.msg-time { font-size:0.68rem; color:var(--text-dim); white-space:nowrap; }
.msg-dot { width:8px; height:8px; border-radius:50%; background:var(--primary); flex-shrink:0; margin-top:4px; }
</style>
</head>
<body><div class="admin-layout">
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
<?php require_once __DIR__ . '/../includes/topnav.php'; ?>
<div class="admin-content fade-in" style="padding-bottom:0;">
  <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">Pesan Masuk</span></div>
  <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>

  <!-- Stats row -->
  <div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
    <div style="padding:12px 20px;background:var(--glass);border:1px solid var(--border);border-radius:var(--radius-sm);display:flex;gap:10px;align-items:center;">
      <span style="font-size:1.2rem;">💬</span>
      <div><div style="font-size:1.2rem;font-weight:700;"><?= $total ?></div><div style="font-size:0.72rem;color:var(--text-dim);">Total Pesan</div></div>
    </div>
    <div style="padding:12px 20px;background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.2);border-radius:var(--radius-sm);display:flex;gap:10px;align-items:center;">
      <span style="font-size:1.2rem;">📬</span>
      <div><div style="font-size:1.2rem;font-weight:700;color:var(--primary);"><?= $unreadTotal ?></div><div style="font-size:0.72rem;color:var(--text-dim);">Belum Dibaca</div></div>
    </div>
    <div style="margin-left:auto;display:flex;gap:8px;align-items:center;">
      <?php if ($unreadTotal > 0): ?>
      <form method="POST" style="display:inline;">
        <input type="hidden" name="mark_all_read" value="1">
        <button type="submit" class="btn btn-glass btn-sm">✓ Tandai Semua Dibaca</button>
      </form>
      <?php endif; ?>
      <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus semua pesan yang sudah dibaca?')">
        <input type="hidden" name="delete_read" value="1">
        <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus Dibaca</button>
      </form>
    </div>
  </div>

  <!-- Two-column layout -->
  <div style="display:grid;grid-template-columns:340px 1fr;gap:0;background:var(--glass);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;height:calc(100vh - 280px);">

    <!-- Message list -->
    <div class="msg-list">
      <?php if (empty($messages)): ?>
      <div class="empty-state"><div class="empty-icon">💬</div><div class="empty-title">Tidak ada pesan</div></div>
      <?php else: foreach ($messages as $m): ?>
      <a href="?id=<?= $m['id'] ?>" class="msg-item <?= $m['id']==$activeId?'active':'' ?> <?= !$m['is_read']?'unread':'' ?>">
        <div class="msg-avatar"><?= strtoupper(substr($m['name'],0,1)) ?></div>
        <div style="flex:1;min-width:0;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2px;">
            <span class="msg-sender"><?= e($m['name']) ?></span>
            <span class="msg-time"><?= date('d/m', strtotime($m['created_at'])) ?></span>
          </div>
          <div class="msg-preview"><?= e(substr($m['subject'] ?: 'Tidak ada subjek', 0, 36)) ?></div>
        </div>
        <?php if (!$m['is_read']): ?><div class="msg-dot"></div><?php endif; ?>
      </a>
      <?php endforeach; endif; ?>

      <!-- Pagination in list -->
      <?php if ($pages > 1): ?>
      <div style="padding:12px 16px;border-top:1px solid var(--border);display:flex;justify-content:center;gap:4px;">
        <?php for ($i=1;$i<=$pages;$i++): ?>
          <a href="?page=<?= $i ?><?= $activeId?"&id=$activeId":'' ?>" class="page-btn <?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Message detail -->
    <div style="padding:28px;overflow-y:auto;">
      <?php if ($activeMsg): ?>
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
          <div style="display:flex;gap:14px;align-items:center;">
            <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--pink));display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:#fff;">
              <?= strtoupper(substr($activeMsg['name'],0,1)) ?>
            </div>
            <div>
              <div style="font-weight:700;font-size:1rem;color:var(--text);"><?= e($activeMsg['name']) ?></div>
              <div style="font-size:0.8rem;color:var(--text-muted);"><?= e($activeMsg['email']) ?></div>
              <div style="font-size:0.72rem;color:var(--text-dim);"><?= date('d M Y H:i', strtotime($activeMsg['created_at'])) ?></div>
            </div>
          </div>
          <div style="display:flex;gap:8px;">
            <a href="mailto:<?= e($activeMsg['email']) ?>?subject=Re: <?= urlencode($activeMsg['subject'] ?? '') ?>" class="btn btn-outline btn-sm">↩ Balas Email</a>
            <form method="POST" id="delmsg<?= $activeMsg['id'] ?>" style="display:inline;">
              <input type="hidden" name="delete_id" value="<?= $activeMsg['id'] ?>">
            </form>
            <button class="btn btn-danger btn-sm" onclick="confirmDelete('Hapus pesan dari <?= addslashes(e($activeMsg['name'])) ?>?','delmsg<?= $activeMsg['id'] ?>')">🗑️ Hapus</button>
          </div>
        </div>

        <?php if ($activeMsg['subject']): ?>
          <div style="font-size:1.05rem;font-weight:700;color:var(--text);margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border);"><?= e($activeMsg['subject']) ?></div>
        <?php endif; ?>

        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:var(--radius);padding:20px;line-height:1.8;color:var(--text-muted);font-size:0.9rem;white-space:pre-wrap;"><?= e($activeMsg['message']) ?></div>

        <div style="margin-top:20px;padding:14px;background:rgba(168,85,247,0.05);border:1px solid rgba(168,85,247,0.15);border-radius:var(--radius-sm);">
          <a href="mailto:<?= e($activeMsg['email']) ?>" style="color:var(--primary);font-size:0.85rem;font-weight:500;">📧 Balas ke <?= e($activeMsg['email']) ?></a>
        </div>
      <?php else: ?>
        <div class="empty-state" style="height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;">
          <div class="empty-icon">💬</div>
          <div class="empty-title">Pilih pesan untuk dibaca</div>
          <div class="empty-desc">Klik salah satu pesan di kiri untuk melihat isinya.</div>
        </div>
      <?php endif; ?>
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
