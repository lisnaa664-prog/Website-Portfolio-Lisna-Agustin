<?php
$pageTitle = 'Tambah Lagu';
require_once __DIR__ . '/../includes/auth_check.php';
$pdo = getDB();
$errors = []; $old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old         = $_POST;
    $title       = trim($_POST['title'] ?? '');
    $artist      = trim($_POST['artist'] ?? '');
    $year        = trim($_POST['year'] ?? '');
    $thumb_emoji = trim($_POST['thumb_emoji'] ?? '🎵');
    $audio_url   = trim($_POST['audio_url'] ?? '');
    $duration    = trim($_POST['duration'] ?? '0:00');
    $bg_color    = trim($_POST['bg_color'] ?? 'linear-gradient(135deg,#c084fc,#818cf8)');
    $sort_order  = intval($_POST['sort_order'] ?? 0);
    $is_active   = isset($_POST['is_active']) ? 1 : 0;

    if (!$title)  $errors['title']  = 'Judul wajib diisi.';
    if (!$artist) $errors['artist'] = 'Artis wajib diisi.';

    // Upload thumbnail
    $thumbnail = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
            $errors['thumbnail'] = 'Format gambar tidak valid.';
        } elseif ($_FILES['thumbnail']['size'] > 2*1024*1024) {
            $errors['thumbnail'] = 'Gambar maks 2 MB.';
        } else {
            $newName   = 'music_' . uniqid() . '.' . $ext;
            $uploadDir = dirname(__DIR__, 2) . '/uploads/music/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadDir . $newName)) {
                $thumbnail = 'uploads/music/' . $newName;
            }
        }
    }

    // Upload audio file
    $audio_file = null;
    if (!empty($_FILES['audio_file']['name'])) {
        $ext = strtolower(pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['mp3','ogg','wav','m4a'])) {
            $errors['audio_file'] = 'Format audio harus mp3/ogg/wav/m4a.';
        } elseif ($_FILES['audio_file']['size'] > 20*1024*1024) {
            $errors['audio_file'] = 'File audio maks 20 MB.';
        } else {
            $newName   = 'audio_' . uniqid() . '.' . $ext;
            $uploadDir = dirname(__DIR__, 2) . '/uploads/music/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $uploadDir . $newName)) {
                $audio_file = 'uploads/music/' . $newName;
            }
        }
    }

    if (empty($errors)) {
        $pdo->prepare("INSERT INTO favorite_music (title,artist,year,thumbnail,thumb_emoji,audio_url,audio_file,duration,bg_color,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?)")
            ->execute([$title, $artist, $year ?: null, $thumbnail, $thumb_emoji, $audio_url ?: null, $audio_file, $duration, $bg_color, $sort_order, $is_active]);
        setFlash('success', 'Lagu berhasil ditambahkan!');
        redirect('/portfolio_redesign/admin/music/index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Tambah Lagu – Admin</title><link rel="stylesheet" href="/portfolio_redesign/admin/css/admin.css"></head>
<body><div class="admin-layout">
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>
<main class="admin-main">
<?php require_once __DIR__ . '/../includes/topnav.php'; ?>
<div class="admin-content fade-in">
  <div class="breadcrumb"><a href="/portfolio_redesign/admin/index.php">Dashboard</a><span class="breadcrumb-sep">/</span><a href="index.php">Musik</a><span class="breadcrumb-sep">/</span><span class="breadcrumb-current">Tambah</span></div>
  <div class="content-card" style="max-width:760px;">
    <div class="card-header"><div class="card-title">🎵 Tambah Lagu Favorit</div></div>
    <div class="card-body">
      <?php if (!empty($errors)): ?><div class="alert alert-error">⚠️ Ada kesalahan. Periksa kembali form.</div><?php endif; ?>
      <form method="POST" enctype="multipart/form-data">

        <div class="form-row">
          <div class="form-group">
            <label>Judul Lagu *</label>
            <input type="text" name="title" class="form-control" value="<?= e($old['title']??'') ?>" placeholder="Stay" required>
          </div>
          <div class="form-group">
            <label>Artis *</label>
            <input type="text" name="artist" class="form-control" value="<?= e($old['artist']??'') ?>" placeholder="BLACKPINK" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Tahun Rilis</label>
            <input type="number" name="year" class="form-control" value="<?= e($old['year']??'') ?>" placeholder="2022" min="1900" max="2099">
          </div>
          <div class="form-group">
            <label>Durasi</label>
            <input type="text" name="duration" class="form-control" value="<?= e($old['duration']??'3:30') ?>" placeholder="3:30">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Emoji / Ikon Lagu</label>
            <input type="text" name="thumb_emoji" class="form-control" value="<?= e($old['thumb_emoji']??'🎵') ?>" maxlength="10">
            <div class="hint">Tampil jika tidak ada gambar.</div>
          </div>
          <div class="form-group">
            <label>Urutan Tampil</label>
            <input type="number" name="sort_order" class="form-control" value="<?= e($old['sort_order']??0) ?>" min="0">
          </div>
        </div>

        <!-- Background gradient -->
        <div class="form-group">
          <label>Warna Background Card</label>
          <select name="bg_color" class="form-control">
            <?php $gradients = [
              'linear-gradient(135deg,#ec4899,#8b5cf6)' => '🌸 Pink → Purple',
              'linear-gradient(135deg,#06b6d4,#8b5cf6)' => '🌊 Cyan → Purple',
              'linear-gradient(135deg,#c084fc,#818cf8)' => '💜 Purple → Indigo',
              'linear-gradient(135deg,#f59e0b,#ef4444)' => '🔥 Orange → Red',
              'linear-gradient(135deg,#10b981,#06b6d4)' => '💚 Green → Cyan',
              'linear-gradient(135deg,#6366f1,#ec4899)' => '✨ Indigo → Pink',
            ];
            foreach ($gradients as $v => $l): ?>
              <option value="<?= e($v) ?>" <?= ($old['bg_color']??'')===$v?'selected':'' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Audio URL -->
        <div class="form-group">
          <label>🎵 Spotify / URL Audio (opsional)</label>
          <input type="url" name="audio_url" class="form-control" value="<?= e($old['audio_url']??'') ?>" placeholder="https://open.spotify.com/track/... atau URL mp3">
          <div class="hint">Bisa diisi: Spotify URL (misal: https://open.spotify.com/track/xxx), link mp3 langsung, atau Spotify embed URL. Sistem otomatis konversi ke embed.</div>
        </div>

        <!-- Audio file upload -->
        <div class="form-group">
          <label>📁 Upload File Audio (opsional)</label>
          <div class="file-upload">
            <input type="file" name="audio_file" accept=".mp3,.ogg,.wav,.m4a">
            <div class="file-upload-icon">🎧</div>
            <div class="file-upload-text">Upload file audio lokal</div>
            <div class="file-upload-hint">Format: MP3, OGG, WAV, M4A. Maks 20 MB.</div>
          </div>
          <?php if (isset($errors['audio_file'])): ?><div style="color:var(--red);font-size:0.75rem;margin-top:4px;"><?= e($errors['audio_file']) ?></div><?php endif; ?>
          <div class="hint" style="margin-top:8px;">Jika keduanya diisi, file lokal yang diutamakan.</div>
        </div>

        <!-- Thumbnail -->
        <div class="form-group">
          <label>🖼️ Thumbnail / Cover Art (opsional)</label>
          <div class="file-upload">
            <input type="file" name="thumbnail" accept="image/*" data-preview="musicThumbPreview">
            <div class="file-upload-icon">🎵</div>
            <div class="file-upload-text">Upload cover art lagu</div>
            <div class="file-upload-hint">JPG, PNG, WebP. Maks 2 MB.</div>
          </div>
          <img id="musicThumbPreview" style="display:none;width:80px;height:80px;object-fit:cover;border-radius:10px;margin-top:10px;border:1px solid var(--border);">
          <?php if (isset($errors['thumbnail'])): ?><div style="color:var(--red);font-size:0.75rem;margin-top:4px;"><?= e($errors['thumbnail']) ?></div><?php endif; ?>
        </div>

        <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
          <label class="toggle-switch"><input type="checkbox" name="is_active" checked><span class="toggle-slider"></span></label>
          <span style="font-size:0.85rem;color:var(--text-muted);">Tampilkan di halaman utama</span>
        </div>

        <div style="display:flex;gap:10px;">
          <button type="submit" class="btn btn-primary">💾 Simpan Lagu</button>
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
