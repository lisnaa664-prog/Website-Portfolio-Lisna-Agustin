<?php
$pageTitle = 'Edit Lagu';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo = getDB();

$id   = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM favorite_music WHERE id=?");
$stmt->execute([$id]);
$song = $stmt->fetch();
if (!$song) { setFlash('error','Lagu tidak ditemukan.'); redirect('/portfolio_redesign/admin/music/index.php'); }

$errors = []; $old = $song;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old        = array_merge($song, $_POST);
    $title      = trim($_POST['title'] ?? '');
    $artist     = trim($_POST['artist'] ?? '');
    $year       = trim($_POST['year'] ?? '');
    $thumb_emoji= trim($_POST['thumb_emoji'] ?? '🎵');
    $audio_url  = trim($_POST['audio_url'] ?? '');
    $duration   = trim($_POST['duration'] ?? '0:00');
    $bg_color   = trim($_POST['bg_color'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $is_active  = isset($_POST['is_active']) ? 1 : 0;

    if (!$title)  $errors['title']  = 'Judul wajib diisi.';
    if (!$artist) $errors['artist'] = 'Artis wajib diisi.';

    $thumbnail  = $song['thumbnail'];
    $audio_file = $song['audio_file'];
    $base       = dirname(__DIR__, 2) . '/';

    if (!empty($_FILES['thumbnail']['name'])) {
        $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        if (in_array($ext,['jpg','jpeg','png','webp','gif']) && $_FILES['thumbnail']['size'] <= 2*1024*1024) {
            $newName = 'music_' . uniqid() . '.' . $ext;
            $dir     = $base . 'uploads/music/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dir . $newName)) {
                if ($thumbnail && file_exists($base.$thumbnail)) unlink($base.$thumbnail);
                $thumbnail = 'uploads/music/' . $newName;
            }
        }
    }
    if (isset($_POST['remove_thumb']) && $thumbnail) {
        if (file_exists($base.$thumbnail)) unlink($base.$thumbnail);
        $thumbnail = null;
    }

    if (!empty($_FILES['audio_file']['name'])) {
        $ext = strtolower(pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext,['mp3','ogg','wav','m4a']) && $_FILES['audio_file']['size'] <= 20*1024*1024) {
            $newName = 'audio_' . uniqid() . '.' . $ext;
            $dir     = $base . 'uploads/music/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $dir . $newName)) {
                if ($audio_file && file_exists($base.$audio_file)) unlink($base.$audio_file);
                $audio_file = 'uploads/music/' . $newName;
            }
        }
    }
    if (isset($_POST['remove_audio']) && $audio_file) {
        if (file_exists($base.$audio_file)) unlink($base.$audio_file);
        $audio_file = null;
    }

    if (empty($errors)) {
        $pdo->prepare("UPDATE favorite_music SET title=?,artist=?,year=?,thumbnail=?,thumb_emoji=?,audio_url=?,audio_file=?,duration=?,bg_color=?,sort_order=?,is_active=? WHERE id=?")
            ->execute([$title,$artist,$year?:null,$thumbnail,$thumb_emoji,$audio_url?:null,$audio_file,$duration,$bg_color,$sort_order,$is_active,$id]);
        setFlash('success','Lagu berhasil diupdate!');
        redirect('/portfolio_redesign/admin/music/index.php');
    }
}
$gradients = [
    'linear-gradient(135deg,#ec4899,#8b5cf6)' => '🌸 Pink → Purple',
    'linear-gradient(135deg,#06b6d4,#8b5cf6)' => '🌊 Cyan → Purple',
    'linear-gradient(135deg,#c084fc,#818cf8)' => '💜 Purple → Indigo',
    'linear-gradient(135deg,#f59e0b,#ef4444)' => '🔥 Orange → Red',
    'linear-gradient(135deg,#10b981,#06b6d4)' => '💚 Green → Cyan',
    'linear-gradient(135deg,#6366f1,#ec4899)' => '✨ Indigo → Pink',
];
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Edit Lagu – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
<?php require_once __DIR__ . '/../includes/topnav.php'; ?>
<div class="admin-content fade-in">
  <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><a href="index.php">Musik</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">Edit</span></div>
  <div class="content-card" style="max-width:760px;">
    <div class="card-header"><div class="card-title">✏️ Edit: <?= e($song['title']) ?> – <?= e($song['artist']) ?></div></div>
    <div class="card-body">
      <?php if (!empty($errors)): ?><div class="alert alert-error">⚠️ Ada kesalahan pada form.</div><?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
          <div class="form-group">
            <label>Judul Lagu *</label>
            <input type="text" name="title" class="form-control" value="<?= e($old['title']) ?>" required>
          </div>
          <div class="form-group">
            <label>Artis *</label>
            <input type="text" name="artist" class="form-control" value="<?= e($old['artist']) ?>" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Tahun Rilis</label>
            <input type="number" name="year" class="form-control" value="<?= e($old['year']??'') ?>">
          </div>
          <div class="form-group">
            <label>Durasi</label>
            <input type="text" name="duration" class="form-control" value="<?= e($old['duration']??'') ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Emoji Ikon</label>
            <input type="text" name="thumb_emoji" class="form-control" value="<?= e($old['thumb_emoji']) ?>" maxlength="10">
          </div>
          <div class="form-group">
            <label>Urutan</label>
            <input type="number" name="sort_order" class="form-control" value="<?= e($old['sort_order']) ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Warna Background</label>
          <select name="bg_color" class="form-control">
            <?php foreach ($gradients as $v => $l): ?>
              <option value="<?= e($v) ?>" <?= $old['bg_color']===$v?'selected':'' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>🎵 Spotify / URL Audio</label>
          <input type="url" name="audio_url" class="form-control" value="<?= e($old['audio_url']??'') ?>" placeholder="https://open.spotify.com/track/... atau URL mp3">
        </div>
        <!-- Audio file -->
        <div class="form-group">
          <label>📁 File Audio</label>
          <?php if ($song['audio_file']): ?>
          <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:var(--glass);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:10px;">
            <span>🎧</span>
            <div style="flex:1;font-size:0.8rem;color:var(--text-muted);"><?= e(basename($song['audio_file'])) ?></div>
            <label style="display:flex;align-items:center;gap:6px;font-size:0.75rem;color:var(--red);cursor:pointer;">
              <input type="checkbox" name="remove_audio"> Hapus audio
            </label>
          </div>
          <?php endif; ?>
          <div class="file-upload">
            <input type="file" name="audio_file" accept=".mp3,.ogg,.wav,.m4a">
            <div class="file-upload-icon">🎧</div>
            <div class="file-upload-text">Upload file audio baru (opsional)</div>
            <div class="file-upload-hint">MP3/OGG/WAV/M4A. Maks 20 MB.</div>
          </div>
        </div>
        <!-- Thumbnail -->
        <div class="form-group">
          <label>🖼️ Thumbnail</label>
          <?php if ($song['thumbnail']): ?>
          <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:var(--glass);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:10px;">
            <img src="/portfolio_redesign/<?= e($song['thumbnail']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
            <span style="flex:1;font-size:0.8rem;color:var(--text-muted);">Thumbnail saat ini</span>
            <label style="display:flex;align-items:center;gap:6px;font-size:0.75rem;color:var(--red);cursor:pointer;">
              <input type="checkbox" name="remove_thumb"> Hapus
            </label>
          </div>
          <?php endif; ?>
          <div class="file-upload">
            <input type="file" name="thumbnail" accept="image/*" data-preview="thumbPrev">
            <div class="file-upload-icon">🖼️</div>
            <div class="file-upload-text">Upload cover baru (opsional)</div>
            <div class="file-upload-hint">JPG, PNG, WebP. Maks 2 MB.</div>
          </div>
          <img id="thumbPrev" style="display:none;width:80px;height:80px;object-fit:cover;border-radius:10px;margin-top:10px;border:1px solid var(--border);">
        </div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
          <label class="toggle-switch"><input type="checkbox" name="is_active" <?= $old['is_active']?'checked':'' ?>><span class="toggle-slider"></span></label>
          <span style="font-size:0.85rem;color:var(--text-muted);">Tampilkan di halaman utama</span>
        </div>
        <div style="display:flex;gap:10px;">
          <button type="submit" class="btn btn-primary">💾 Update Lagu</button>
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
