<?php
$pageTitle = 'Edit Skill';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo = getDB();
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM skills WHERE id=?"); $stmt->execute([$id]);
$skill = $stmt->fetch();
if (!$skill) { setFlash('error','Skill tidak ditemukan.'); redirect('/portfolio_redesign/admin/skills/index.php'); }
$errors = []; $old = $skill;

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $old = array_merge($skill, $_POST);
    $name=$_POST['name']??''; $category=$_POST['category']??'hard';
    $level=intval($_POST['level']??50); $icon=$_POST['icon']??'⚡';
    $sort_order=intval($_POST['sort_order']??0); $is_active=isset($_POST['is_active'])?1:0;
    if (!trim($name)) $errors['name']='Nama wajib diisi.';
    if (empty($errors)) {
        $pdo->prepare("UPDATE skills SET name=?,category=?,level=?,icon=?,sort_order=?,is_active=? WHERE id=?")
            ->execute([trim($name),$category,$level,$icon,$sort_order,$is_active,$id]);
        setFlash('success','Skill berhasil diupdate!');
        redirect('/portfolio_redesign/admin/skills/index.php');
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Edit Skill – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="admin-main">
    <?php require_once __DIR__ . '/../includes/topnav.php'; ?>
    <div class="admin-content fade-in">
      <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><a href="index.php">Skills</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">Edit</span></div>
      <div class="content-card" style="max-width:560px;">
        <div class="card-header"><div class="card-title">✏️ Edit: <?= e($skill['name']) ?></div></div>
        <div class="card-body">
          <form method="POST">
            <div class="form-row">
              <div class="form-group">
                <label>Nama Skill *</label>
                <input type="text" name="name" class="form-control" value="<?= e($old['name']) ?>" required>
              </div>
              <div class="form-group">
                <label>Icon</label>
                <input type="text" name="icon" class="form-control" value="<?= e($old['icon']) ?>" maxlength="10">
              </div>
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select name="category" class="form-control">
                <?php foreach (['hard'=>'Hard Skill','soft'=>'Soft Skill','tool'=>'Tool'] as $v=>$l): ?>
                  <option value="<?= $v ?>" <?= $old['category']===$v?'selected':'' ?>><?= $l ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Level: <span id="levelVal"><?= $old['level'] ?>%</span></label>
              <input type="range" name="level" min="0" max="100" value="<?= $old['level'] ?>" data-display="levelVal" data-bar="levelBar">
              <div class="level-display">
                <div class="level-bar"><div class="level-bar-fill" id="levelBar" style="width:<?= $old['level'] ?>%;"></div></div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="<?= e($old['sort_order']) ?>">
              </div>
              <div class="form-group">
                <label>Status</label>
                <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
                  <label class="toggle-switch">
                    <input type="checkbox" name="is_active" <?= $old['is_active']?'checked':'' ?>>
                    <span class="toggle-slider"></span>
                  </label>
                  <span style="font-size:0.85rem;color:var(--text-muted);">Aktif</span>
                </div>
              </div>
            </div>
            <div style="display:flex;gap:10px;">
              <button type="submit" class="btn btn-primary">💾 Update</button>
              <a href="index.php" class="btn btn-glass">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</div>
<script src="/portfolio_redesign/admin/js/admin.js"></script></body></html>
