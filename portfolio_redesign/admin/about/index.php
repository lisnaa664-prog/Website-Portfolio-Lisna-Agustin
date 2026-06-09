<?php
$pageTitle = 'About Me';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo = getDB();
$flash = getFlash();

$about = $pdo->query("SELECT * FROM about_me LIMIT 1")->fetch();
if (!$about) {
    $pdo->query("INSERT INTO about_me (full_name,description) VALUES ('Lisna Agustin','Mahasiswa Informatika')");
    $about = $pdo->query("SELECT * FROM about_me LIMIT 1")->fetch();
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $full_name  = trim($_POST['full_name'] ?? '');
    $tagline    = trim($_POST['tagline'] ?? '');
    $description= trim($_POST['description'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $location   = trim($_POST['location'] ?? '');
    $university = trim($_POST['university'] ?? '');
    $major      = trim($_POST['major'] ?? '');
    $gpa        = floatval($_POST['gpa'] ?? 0);

    $photo = $about['photo'];
    if (!empty($_FILES['photo']['name'])) {
        $ext=strtolower(pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION));
        if (in_array($ext,['jpg','jpeg','png','webp'])) {
            $newName='profile_'.uniqid().'.'.$ext;
            $dir=dirname(__DIR__,2).'/uploads/profile/';
            if (!is_dir($dir)) mkdir($dir,0755,true);
            if (move_uploaded_file($_FILES['photo']['tmp_name'],$dir.$newName)) {
                if ($about['photo'] && file_exists(dirname(__DIR__,2).'/'.$about['photo']) && strpos($about['photo'],'uploads/')!==false) {
                    unlink(dirname(__DIR__,2).'/'.$about['photo']);
                }
                $photo='uploads/profile/'.$newName;
            }
        }
    }

    $pdo->prepare("UPDATE about_me SET full_name=?,tagline=?,description=?,email=?,phone=?,location=?,university=?,major=?,gpa=?,photo=? WHERE id=?")
        ->execute([$full_name,$tagline,$description,$email,$phone,$location,$university,$major,$gpa,$photo,$about['id']]);
    setFlash('success','Profil berhasil diupdate!');
    redirect('/portfolio_redesign/admin/about/index.php');
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>About Me – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="admin-main">
    <?php require_once __DIR__ . '/../includes/topnav.php'; ?>
    <div class="admin-content fade-in">
      <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">About Me</span></div>
      <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>

      <div style="display:grid;grid-template-columns:280px 1fr;gap:24px;align-items:start;">

        <!-- Sidebar Profile Preview -->
        <div class="content-card">
          <div class="card-body" style="text-align:center;">
            <?php $photoSrc = $about['photo'] ? '/portfolio_redesign/'.$about['photo'] : '/portfolio_redesign/images/foto_lisna.jpeg'; ?>
            <img id="photoPreview" src="<?= e($photoSrc) ?>" style="width:100px;height:100px;object-fit:cover;border-radius:50%;margin:0 auto 16px;border:3px solid rgba(168,85,247,0.4);">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:4px;"><?= e($about['full_name']) ?></h3>
            <p style="font-size:0.8rem;color:var(--text-muted);"><?= e($about['tagline']??'') ?></p>
            <div style="margin-top:20px;text-align:left;">
              <?php $infos = [
                ['📍',$about['location']??''],['📧',$about['email']??''],
                ['📱',$about['phone']??''],['🎓',$about['university']??''],
              ];
              foreach ($infos as [$icon,$val]): if(!$val) continue; ?>
              <div style="display:flex;align-items:center;gap:8px;font-size:0.78rem;color:var(--text-muted);margin-bottom:8px;">
                <span><?= $icon ?></span><span><?= e($val) ?></span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Edit Form -->
        <div class="content-card">
          <div class="card-header"><div class="card-title">👤 Edit Profil</div></div>
          <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
              <div class="form-row">
                <div class="form-group">
                  <label>Nama Lengkap *</label>
                  <input type="text" name="full_name" class="form-control" value="<?= e($about['full_name']) ?>" required>
                </div>
                <div class="form-group">
                  <label>Tagline</label>
                  <input type="text" name="tagline" class="form-control" value="<?= e($about['tagline']??'') ?>" placeholder="Mahasiswa Informatika | ...">
                </div>
              </div>

              <div class="form-group">
                <label>Deskripsi / Bio</label>
                <textarea name="description" class="form-control" rows="5"><?= e($about['description']) ?></textarea>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" value="<?= e($about['email']??'') ?>">
                </div>
                <div class="form-group">
                  <label>No. HP / WA</label>
                  <input type="text" name="phone" class="form-control" value="<?= e($about['phone']??'') ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Lokasi</label>
                  <input type="text" name="location" class="form-control" value="<?= e($about['location']??'') ?>" placeholder="Kota, Indonesia">
                </div>
                <div class="form-group">
                  <label>IPK</label>
                  <input type="number" name="gpa" step="0.01" min="0" max="4" class="form-control" value="<?= e($about['gpa']??'') ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Universitas</label>
                  <input type="text" name="university" class="form-control" value="<?= e($about['university']??'') ?>">
                </div>
                <div class="form-group">
                  <label>Jurusan</label>
                  <input type="text" name="major" class="form-control" value="<?= e($about['major']??'') ?>">
                </div>
              </div>

              <div class="form-group">
                <label>Foto Profil</label>
                <div class="file-upload">
                  <input type="file" name="photo" accept="image/*" data-preview="photoPreview">
                  <div class="file-upload-icon">📸</div>
                  <div class="file-upload-text">Upload foto baru (opsional)</div>
                  <div class="file-upload-hint">JPG, PNG, WebP. Maks 2MB.</div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </main>
</div>
<script src="/portfolio_redesign/admin/js/admin.js"></script></body></html>
