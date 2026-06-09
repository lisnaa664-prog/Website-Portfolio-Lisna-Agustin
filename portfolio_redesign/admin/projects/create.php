<?php
$pageTitle = 'Tambah Project';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo = getDB();
$errors = []; $old = [];

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $old = $_POST;
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $technologies= trim($_POST['technologies'] ?? '');
    $github_url  = trim($_POST['github_url'] ?? '');
    $demo_url    = trim($_POST['demo_url'] ?? '');
    $thumb_emoji = trim($_POST['thumb_emoji'] ?? '💻');
    $sort_order  = intval($_POST['sort_order'] ?? 0);
    $is_active   = isset($_POST['is_active']) ? 1 : 0;

    if (!$title) $errors['title'] = 'Judul wajib diisi.';
    if (!$description) $errors['description'] = 'Deskripsi wajib diisi.';

    // Upload thumbnail
    $thumbnail = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $ext     = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowed)) {
            $errors['thumbnail'] = 'Format gambar tidak valid (jpg/jpeg/png/gif/webp).';
        } elseif ($_FILES['thumbnail']['size'] > 3*1024*1024) {
            $errors['thumbnail'] = 'Ukuran gambar maksimal 3 MB.';
        } else {
            $newName   = 'proj_' . uniqid() . '.' . $ext;
            $uploadDir = dirname(__DIR__,2) . '/uploads/projects/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadDir . $newName)) {
                $thumbnail = 'uploads/projects/' . $newName;
            } else {
                $errors['thumbnail'] = 'Gagal mengupload gambar.';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO projects (title,description,technologies,github_url,demo_url,thumbnail,thumb_emoji,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title,$description,$technologies,$github_url,$demo_url,$thumbnail,$thumb_emoji,$sort_order,$is_active]);
        setFlash('success','Project berhasil ditambahkan!');
        redirect('/portfolio_redesign/admin/projects/index.php');
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Tambah Project – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="admin-main">
    <?php require_once __DIR__ . '/../includes/topnav.php'; ?>
    <div class="admin-content fade-in">
      <div class="breadcrumb">
        <a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span>
        <a href="index.php">Projects</a><span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Tambah</span>
      </div>
      <div class="content-card" style="max-width:780px;">
        <div class="card-header"><div class="card-title">➕ Tambah Project Baru</div></div>
        <div class="card-body">
          <?php if (!empty($errors)): ?>
            <div class="alert alert-error">⚠️ Terdapat kesalahan pada form. Periksa kembali.</div>
          <?php endif; ?>
          <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group">
                <label>Judul Project *</label>
                <input type="text" name="title" class="form-control <?= isset($errors['title'])?'is-invalid':'' ?>" value="<?= e($old['title']??'') ?>" placeholder="Nama project kamu" required>
                <?php if (isset($errors['title'])): ?><div class="form-error-msg" style="color:var(--red);font-size:0.75rem;margin-top:4px;"><?= e($errors['title']) ?></div><?php endif; ?>
              </div>
              <div class="form-group">
                <label>Emoji Thumbnail</label>
                <input type="text" name="thumb_emoji" class="form-control" value="<?= e($old['thumb_emoji']??'💻') ?>" placeholder="💻" maxlength="10">
                <div class="hint">Emoji yang ditampilkan jika tidak ada gambar.</div>
              </div>
            </div>

            <div class="form-group">
              <label>Deskripsi *</label>
              <textarea name="description" class="form-control <?= isset($errors['description'])?'is-invalid':'' ?>" rows="4" placeholder="Deskripsi singkat project..."><?= e($old['description']??'') ?></textarea>
            </div>

            <div class="form-group">
              <label>Teknologi (pisahkan dengan koma)</label>
              <input type="text" name="technologies" class="form-control" value="<?= e($old['technologies']??'') ?>" placeholder="HTML, CSS, JavaScript, PHP">
              <div class="hint">Contoh: HTML, CSS, JavaScript, MySQL</div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Link GitHub</label>
                <input type="url" name="github_url" class="form-control" value="<?= e($old['github_url']??'') ?>" placeholder="https://github.com/...">
              </div>
              <div class="form-group">
                <label>Link Demo</label>
                <input type="url" name="demo_url" class="form-control" value="<?= e($old['demo_url']??'') ?>" placeholder="https://...">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-control" value="<?= e($old['sort_order']??0) ?>" min="0">
              </div>
              <div class="form-group">
                <label>Status</label>
                <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
                  <label class="toggle-switch">
                    <input type="checkbox" name="is_active" <?= isset($old['is_active'])&&$old['is_active']?'checked':(!isset($_POST['submit'])?'checked':'') ?>>
                    <span class="toggle-slider"></span>
                  </label>
                  <span style="font-size:0.85rem;color:var(--text-muted);">Tampilkan di portfolio</span>
                </div>
              </div>
            </div>

            <!-- FOTO PROJECT -->
            <div class="form-group">
              <label>📸 Foto / Screenshot Project</label>
              <p style="font-size:0.78rem;color:var(--text-dim);margin-bottom:12px;">
                Upload screenshot atau foto tampilan project kamu. Akan ditampilkan sebagai thumbnail di halaman Portfolio.
              </p>

              <!-- Preview area -->
              <div id="thumbPreviewBox" style="display:none;margin-bottom:14px;border-radius:14px;overflow:hidden;border:1px solid var(--border-2);position:relative;height:180px;">
                <img id="thumbPreview" src="" alt="Preview"
                     style="width:100%;height:100%;object-fit:cover;display:block;">
                <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 50%,rgba(0,0,0,0.6));pointer-events:none;"></div>
                <div style="position:absolute;bottom:10px;left:12px;font-size:0.72rem;color:rgba(255,255,255,0.6);">Preview foto project</div>
                <button type="button" onclick="clearThumb()" title="Hapus preview"
                        style="position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;background:rgba(0,0,0,0.6);border:1px solid rgba(255,255,255,0.2);color:#fff;font-size:0.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;">✕</button>
              </div>

              <div class="file-upload" id="thumbUploadBox">
                <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*"
                       onchange="previewThumb(this)">
                <div class="file-upload-icon">🖼️</div>
                <div class="file-upload-text">Klik atau drag foto project ke sini</div>
                <div class="file-upload-hint">JPG, PNG, WebP, GIF · Maks 3 MB · Rekomendasi: 1280×720px</div>
              </div>
              <?php if (isset($errors['thumbnail'])): ?>
                <div style="color:var(--red);font-size:0.75rem;margin-top:6px;"><?= e($errors['thumbnail']) ?></div>
              <?php endif; ?>

              <div style="margin-top:10px;padding:10px 14px;background:rgba(168,85,247,0.06);border:1px solid rgba(168,85,247,0.15);border-radius:var(--radius-sm);font-size:0.75rem;color:var(--text-dim);">
                💡 Tips: Gunakan screenshot browser (fullpage) atau foto hasil project untuk tampilan terbaik.
                Jika tidak diupload, akan tampil emoji yang sudah diatur di atas.
              </div>
            </div>

            <script>
            function previewThumb(input) {
              const box = document.getElementById('thumbPreviewBox');
              const img = document.getElementById('thumbPreview');
              const upload = document.getElementById('thumbUploadBox');
              if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                  img.src = e.target.result;
                  box.style.display = 'block';
                  upload.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
              }
            }
            function clearThumb() {
              document.getElementById('thumbnailInput').value = '';
              document.getElementById('thumbPreviewBox').style.display = 'none';
              document.getElementById('thumbUploadBox').style.display = 'block';
            }
            </script>

            <div style="display:flex;gap:10px;">
              <button type="submit" name="submit" class="btn btn-primary">💾 Simpan Project</button>
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
