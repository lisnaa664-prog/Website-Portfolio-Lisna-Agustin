<?php
require_once 'config/koneksi.php';
$pdo      = getDB();
$projects = $pdo->query("SELECT * FROM projects WHERE is_active=1 ORDER BY sort_order, created_at DESC")->fetchAll();

// Warna gradient fallback per index
$gradients = [
    ['from'=>'#c084fc','to'=>'#818cf8'],
    ['from'=>'#2dd4bf','to'=>'#6366f1'],
    ['from'=>'#f472b6','to'=>'#fb923c'],
    ['from'=>'#38bdf8','to'=>'#818cf8'],
    ['from'=>'#4ade80','to'=>'#2dd4bf'],
    ['from'=>'#f59e0b','to'=>'#ef4444'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Porto. – Portfolio</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* ===== PROJECT CARD – REDESIGN DENGAN FOTO ===== */
    .portfolio-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 28px;
    }

    .project-card {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 20px;
      overflow: hidden;
      transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1),
                  box-shadow 0.3s ease,
                  border-color 0.3s ease;
      display: flex;
      flex-direction: column;
    }
    .project-card:hover {
      transform: translateY(-8px);
      border-color: rgba(192,132,252,0.35);
      box-shadow: 0 28px 64px rgba(0,0,0,0.55),
                  0 0 0 1px rgba(192,132,252,0.12);
    }

    /* ===== THUMBNAIL AREA ===== */
    .proj-thumb {
      position: relative;
      width: 100%;
      height: 210px;
      overflow: hidden;
      flex-shrink: 0;
    }

    /* Foto project */
    .proj-thumb-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: top center;
      display: block;
      transition: transform 0.5s ease;
    }
    .project-card:hover .proj-thumb-img {
      transform: scale(1.06);
    }

    /* Fallback gradient + emoji (kalau tidak ada foto) */
    .proj-thumb-fallback {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3.5rem;
      position: relative;
    }

    /* Overlay gelap di bawah thumbnail */
    .proj-thumb-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(
        to bottom,
        transparent 40%,
        rgba(7,7,14,0.75) 100%
      );
      pointer-events: none; /* Penting: biar tidak menghalangi klik */
    }

    /* Badge teknologi di atas foto (pojok kiri atas) */
    .proj-thumb-tags {
      position: absolute;
      top: 12px;
      left: 12px;
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      z-index: 10; /* Naikkan z-index */
      pointer-events: auto; /* Pastikan bisa diklik */
    }
    .proj-thumb-tag {
      padding: 3px 10px;
      border-radius: 100px;
      font-size: 0.67rem;
      font-weight: 700;
      letter-spacing: 0.04em;
      background: rgba(0,0,0,0.55);
      color: rgba(255,255,255,0.9);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.15);
      pointer-events: none; /* Badge tidak perlu diklik */
    }

    /* Nomor urut project di pojok kanan atas */
    .proj-number {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.72rem;
      font-weight: 700;
      color: rgba(255,255,255,0.7);
      z-index: 10;
      pointer-events: none; /* Nomor tidak perlu diklik */
    }

    /* ===== CARD BODY ===== */
    .project-body {
      padding: 20px 22px 22px;
      display: flex;
      flex-direction: column;
      flex: 1;
      gap: 10px;
      position: relative;
      z-index: 5;
      background: inherit;
    }

    .project-title {
      font-family: 'Syne', sans-serif;
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--text-main);
      line-height: 1.3;
      margin: 0;
    }

    .project-desc {
      font-size: 0.855rem;
      color: var(--text-muted);
      line-height: 1.65;
      margin: 0;
      flex: 1;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .project-links {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 4px;
    }

    .project-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 100px;
      font-size: 0.79rem;
      font-weight: 600;
      transition: all 0.25s ease;
      text-decoration: none;
      cursor: pointer;
      position: relative;
      z-index: 15;
    }
    .proj-link-github {
      background: rgba(192,132,252,0.1);
      color: var(--primary);
      border: 1px solid rgba(192,132,252,0.25);
    }
    .proj-link-github:hover {
      background: var(--primary);
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(192,132,252,0.4);
    }
    .proj-link-demo {
      background: rgba(45,212,191,0.08);
      color: var(--teal);
      border: 1px solid rgba(45,212,191,0.2);
    }
    .proj-link-demo:hover {
      background: var(--teal);
      color: #000;
      transform: translateY(-2px);
    }
    .proj-link-disabled {
      opacity: 0.3;
      cursor: not-allowed;
      pointer-events: none;
      background: rgba(255,255,255,0.04);
      color: var(--text-dim);
      border: 1px solid rgba(255,255,255,0.07);
    }

    /* ===== MODAL PREVIEW ===== */
    .proj-preview-btn {
      position: absolute;
      bottom: 12px;
      right: 12px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(0,0,0,0.55);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      cursor: pointer;
      z-index: 20; /* Z-index paling tinggi */
      transition: all 0.2s;
      color: rgba(255,255,255,0.8);
      pointer-events: auto; /* Pastikan bisa diklik */
    }
    .proj-preview-btn:hover {
      background: rgba(192,132,252,0.7);
      border-color: rgba(192,132,252,0.5);
      color: #fff;
      transform: scale(1.1);
    }

    /* Modal foto preview */
    .img-modal {
      position: fixed;
      inset: 0;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background: rgba(0,0,0,0.9);
      backdrop-filter: blur(12px);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s, visibility 0.3s;
    }
    .img-modal.open {
      opacity: 1;
      visibility: visible;
    }
    .img-modal-inner {
      position: relative;
      max-width: 900px;
      width: 100%;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 32px 80px rgba(0,0,0,0.8);
      transform: scale(0.92);
      transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
    }
    .img-modal.open .img-modal-inner {
      transform: scale(1);
    }
    .img-modal-img {
      width: 100%;
      height: auto;
      max-height: 80vh;
      object-fit: contain;
      display: block;
      background: #0a0a0f;
    }
    .img-modal-close {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(0,0,0,0.7);
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      font-size: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
      z-index: 2;
    }
    .img-modal-close:hover {
      background: #ef4444;
      border-color: #ef4444;
    }
    .img-modal-caption {
      padding: 14px 20px;
      background: rgba(15,15,26,0.95);
      border-top: 1px solid rgba(255,255,255,0.07);
    }
    .img-modal-caption h4 {
      font-size: 0.95rem;
      font-weight: 700;
      color: #f1f5f9;
      margin-bottom: 4px;
    }
    .img-modal-caption p {
      font-size: 0.78rem;
      color: #94a3b8;
    }

    /* ===== WIP BOX ===== */
    .wip-box {
      text-align: center;
      padding: 52px 32px;
      background: rgba(255,255,255,0.03);
      border: 1px dashed rgba(255,255,255,0.1);
      border-radius: 20px;
      margin-top: 40px;
    }
    .wip-box h3 {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-muted);
      margin-bottom: 8px;
    }
    .wip-box p {
      font-size: 0.855rem;
      color: var(--text-dim);
    }

    @media (max-width: 640px) {
      .portfolio-grid { grid-template-columns: 1fr; }
      .proj-thumb { height: 190px; }
    }
  </style>
</head>
<body>
<?php require_once 'config/navbar.php'; ?>

<div class="page-header">
  <div class="container">
    <div class="section-tag">Karya Saya</div>
    <h1 class="section-title">Portfolio Proyek</h1>
    <p class="section-subtitle">Kumpulan tugas dan hasil belajar yang telah saya kerjakan.</p>
  </div>
</div>

<section class="section">
  <div class="container">

    <?php if (empty($projects)): ?>
      <div style="text-align:center;padding:80px 0;">
        <div style="font-size:3.5rem;margin-bottom:16px;opacity:0.25;">💼</div>
        <h3 style="color:var(--text-muted);margin-bottom:8px;">Belum ada project ditampilkan.</h3>
        <p style="color:var(--text-dim);font-size:0.875rem;">Admin dapat menambahkan project beserta foto melalui Dashboard.</p>
      </div>

    <?php else: ?>

    <!-- Jumlah project -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:32px;flex-wrap:wrap;gap:12px;">
      <p style="color:var(--text-muted);font-size:0.875rem;">
        Menampilkan <strong style="color:var(--primary);"><?= count($projects) ?></strong> project
      </p>
      <div style="display:flex;align-items:center;gap:8px;font-size:0.78rem;color:var(--text-dim);">
        <span style="width:10px;height:10px;border-radius:50%;background:var(--primary);display:inline-block;"></span>
        Klik 🔍 untuk melihat foto lebih besar
      </div>
    </div>

    <div class="portfolio-grid">
      <?php foreach ($projects as $i => $p):
        $g = $gradients[$i % count($gradients)];
        $hasThumbnail = !empty($p['thumbnail']);
        $thumbSrc = $hasThumbnail
          ? (str_starts_with($p['thumbnail'], 'http') ? $p['thumbnail'] : '/portfolio_redesign/' . $p['thumbnail'])
          : null;
        $techs = array_filter(array_map('trim', explode(',', $p['technologies'])));
      ?>
      <div class="project-card fade-up" style="--delay:<?= $i * 0.08 ?>s;">

        <!-- ===== THUMBNAIL ===== -->
        <div class="proj-thumb">

          <?php if ($thumbSrc): ?>
            <img src="<?= htmlspecialchars($thumbSrc) ?>"
                 alt="Screenshot <?= htmlspecialchars($p['title']) ?>"
                 class="proj-thumb-img"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div class="proj-thumb-fallback"
                 style="display:none;background:linear-gradient(135deg,<?= htmlspecialchars($g['from']) ?>,<?= htmlspecialchars($g['to']) ?>);">
              <?= htmlspecialchars($p['thumb_emoji'] ?? '💻') ?>
            </div>

          <?php else: ?>
            <div class="proj-thumb-fallback"
                 style="background:linear-gradient(135deg,<?= htmlspecialchars($g['from']) ?> 0%,<?= htmlspecialchars($g['to']) ?> 100%);">
              <span style="position:relative;z-index:1;filter:drop-shadow(0 4px 12px rgba(0,0,0,0.4));">
                <?= htmlspecialchars($p['thumb_emoji'] ?? '💻') ?>
              </span>
              <div style="position:absolute;inset:0;background-image:radial-gradient(circle,rgba(255,255,255,0.08) 1px,transparent 1px);background-size:20px 20px;pointer-events:none;"></div>
              <div style="position:absolute;bottom:10px;left:50%;transform:translateX(-50%);font-size:0.62rem;color:rgba(255,255,255,0.35);white-space:nowrap;letter-spacing:0.08em;text-transform:uppercase;">
                Upload foto via Admin
              </div>
            </div>
          <?php endif; ?>

          <div class="proj-thumb-overlay"></div>

          <div class="proj-thumb-tags">
            <?php foreach (array_slice($techs, 0, 3) as $tech): ?>
              <span class="proj-thumb-tag"><?= htmlspecialchars($tech) ?></span>
            <?php endforeach; ?>
            <?php if (count($techs) > 3): ?>
              <span class="proj-thumb-tag">+<?= count($techs) - 3 ?></span>
            <?php endif; ?>
          </div>

          <div class="proj-number"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></div>

          <?php if ($thumbSrc): ?>
          <button class="proj-preview-btn"
                  onclick="openImgModal('<?= htmlspecialchars($thumbSrc) ?>','<?= addslashes(htmlspecialchars($p['title'])) ?>','<?= addslashes(htmlspecialchars($p['description'])) ?>')"
                  title="Lihat foto lebih besar">
            🔍
          </button>
          <?php endif; ?>

        </div>

        <div class="project-body">
          <h3 class="project-title"><?= htmlspecialchars($p['title']) ?></h3>
          <p class="project-desc"><?= htmlspecialchars($p['description']) ?></p>

          <div class="project-links">
            <?php if (!empty($p['github_url'])): ?>
              <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" rel="noopener" class="project-link proj-link-github">
                🐙 GitHub
              </a>
            <?php endif; ?>
            <?php if (!empty($p['demo_url'])): ?>
              <a href="<?= htmlspecialchars($p['demo_url']) ?>" target="_blank" rel="noopener" class="project-link proj-link-demo">
                🌐 Live Demo
              </a>
            <?php endif; ?>
            <?php if (empty($p['github_url']) && empty($p['demo_url'])): ?>
              <span class="project-link proj-link-disabled">🔗 Link belum tersedia</span>
            <?php endif; ?>
          </div>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <div class="wip-box fade-up">
      <div style="font-size:2.5rem;margin-bottom:14px;">🚧</div>
      <h3>Masih Dalam Proses Belajar</h3>
      <p>Saya terus belajar dan akan menambahkan proyek baru ke depannya. Stay tuned!</p>
    </div>

  </div>
</section>

<!-- MODAL PREVIEW FOTO -->
<div class="img-modal" id="imgModal" onclick="if(event.target===this)closeImgModal()">
  <div class="img-modal-inner">
    <button class="img-modal-close" onclick="closeImgModal()" title="Tutup">✕</button>
    <img src="" alt="" class="img-modal-img" id="imgModalImg">
    <div class="img-modal-caption">
      <h4 id="imgModalTitle"></h4>
      <p id="imgModalDesc"></p>
    </div>
  </div>
</div>

<?php require_once 'config/footer.php'; ?>
<script src="js/script.js"></script>
<script>
function openImgModal(src, title, desc) {
  const modal = document.getElementById('imgModal');
  document.getElementById('imgModalImg').src = src;
  document.getElementById('imgModalTitle').textContent = title;
  document.getElementById('imgModalDesc').textContent = desc;
  modal.classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeImgModal() {
  document.getElementById('imgModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) { 
  if (e.key === 'Escape') closeImgModal(); 
});
document.querySelectorAll('.project-card').forEach(function(card, i) {
  card.style.transitionDelay = (i * 0.08) + 's';
});
</script>
</body>
</html>