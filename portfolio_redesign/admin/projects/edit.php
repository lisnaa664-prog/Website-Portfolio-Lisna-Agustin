<?php
$pageTitle = 'Edit Project';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo = getDB();

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id=?");
$stmt->execute([$id]);
$project = $stmt->fetch();
if (!$project) { setFlash('error','Project tidak ditemukan.'); redirect('/portfolio_redesign/admin/projects/index.php'); }

$errors = []; $old = $project;

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $old = array_merge($old, $_POST);
    $title=$_POST['title']??''; $description=$_POST['description']??'';
    $technologies=$_POST['technologies']??''; $github_url=$_POST['github_url']??'';
    $demo_url=$_POST['demo_url']??''; $thumb_emoji=$_POST['thumb_emoji']??'💻';
    $sort_order=intval($_POST['sort_order']??0); $is_active=isset($_POST['is_active'])?1:0;
    if (!trim($title)) $errors['title']='Judul wajib diisi.';
    if (!trim($description)) $errors['description']='Deskripsi wajib diisi.';

    $thumbnail = $project['thumbnail'];
    if (!empty($_FILES['thumbnail']['name'])) {
        $ext=strtolower(pathinfo($_FILES['thumbnail']['name'],PATHINFO_EXTENSION));
        if (!in_array($ext,['jpg','jpeg','png','gif','webp'])) { $errors['thumbnail']='Format tidak valid.'; }
        elseif ($_FILES['thumbnail']['size']>3*1024*1024) { $errors['thumbnail']='Maks 3 MB.'; }
        else {
            $newName='proj_'.uniqid().'.'.$ext;
            $dir=dirname(__DIR__,2).'/uploads/projects/';
            if (!is_dir($dir)) mkdir($dir,0755,true);
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'],$dir.$newName)) {
                if ($project['thumbnail'] && file_exists(dirname(__DIR__,2).'/'.$project['thumbnail'])) unlink(dirname(__DIR__,2).'/'.$project['thumbnail']);
                $thumbnail='uploads/projects/'.$newName;
            }
        }
    }
    if (isset($_POST['remove_thumb']) && $thumbnail) {
        if (file_exists(dirname(__DIR__,2).'/'.$thumbnail)) unlink(dirname(__DIR__,2).'/'.$thumbnail);
        $thumbnail=null;
    }

    if (empty($errors)) {
        $pdo->prepare("UPDATE projects SET title=?,description=?,technologies=?,github_url=?,demo_url=?,thumbnail=?,thumb_emoji=?,sort_order=?,is_active=?,updated_at=NOW() WHERE id=?")
            ->execute([trim($title),trim($description),$technologies,$github_url,$demo_url,$thumbnail,$thumb_emoji,$sort_order,$is_active,$id]);
        setFlash('success','Project berhasil diupdate!');
        redirect('/portfolio_redesign/admin/projects/index.php');
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Edit Project – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="admin-main">
    <?php require_once __DIR__ . '/../includes/topnav.php'; ?>
    <div class="admin-content fade-in">
      <div class="breadcrumb">
        <a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span>
        <a href="index.php">Projects</a><span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Edit</span>
      </div>
      <div class="content-card" style="max-width:780px;">
        <div class="card-header">
          <div class="card-title">✏️ Edit Project: <?= e($project['title']) ?></div>
        </div>
        <div class="card-body">
          <?php if (!empty($errors)): ?><div class="alert alert-error">⚠️ Ada kesalahan pada form.</div><?php endif; ?>
          <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group">
                <label>Judul Project *</label>
                <input type="text" name="title" class="form-control" value="<?= e($old['title']) ?>" required>
              </div>
              <div class="form-group">
                <label>Emoji Thumbnail</label>
                <input type="text" name="thumb_emoji" class="form-control" value="<?= e($old['thumb_emoji']??'💻') ?>" maxlength="10">
              </div>
            </div>
            <div class="form-group">
              <label>Deskripsi *</label>
              <textarea name="description" class="form-control" rows="4"><?= e($old['description']) ?></textarea>
            </div>
            <div class="form-group">
              <label>Teknologi</label>
              <input type="text" name="technologies" class="form-control" value="<?= e($old['technologies']) ?>" placeholder="HTML, CSS, ...">
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Link GitHub</label>
                <input type="url" name="github_url" class="form-control" value="<?= e($old['github_url']) ?>">
              </div>
              <div class="form-group">
                <label>Link Demo</label>
                <input type="url" name="demo_url" class="form-control" value="<?= e($old['demo_url']) ?>">
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
            <div class="form-group">
              <label>Thumbnail</label>
              <?php if ($project['thumbnail']): ?>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;padding:12px;background:var(--glass);border-radius:var(--radius-sm);border:1px solid var(--border);">
                  <img src="/portfolio_redesign/<?= e($project['thumbnail']) ?>" style="width:60px;height:40px;object-fit:cover;border-radius:6px;">
                  <span style="font-size:0.8rem;color:var(--text-muted);flex:1;">Thumbnail saat ini</span>
                  <label style="display:flex;align-items:center;gap:6px;font-size:0.78rem;color:var(--red);cursor:pointer;">
                    <input type="checkbox" name="remove_thumb" style="accent-color:var(--red);"> Hapus thumbnail
                  </label>
                </div>
              <?php endif; ?>
              <div class="file-upload">
                <input type="file" name="thumbnail" accept="image/*" data-preview="thumbPreview">
                <div class="file-upload-icon">🖼️</div>
                <div class="file-upload-text">Upload gambar baru (opsional)</div>
                <div class="file-upload-hint">JPG, PNG, WebP. Maks 3MB.</div>
              </div>
              <img id="thumbPreview" style="display:none;width:120px;height:80px;object-fit:cover;border-radius:8px;margin-top:10px;border:1px solid var(--border);">
            </div>
            <div style="display:flex;gap:10px;">
              <button type="submit" class="btn btn-primary">💾 Update Project</button>
              <a href="index.php" class="btn btn-glass">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</div>
<script src="/portfolio_redesign/admin/js/admin.js"></script>
</body></html>
