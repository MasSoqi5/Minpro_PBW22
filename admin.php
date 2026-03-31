<?php
require __DIR__ . '/config/koneksi.php';

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

function e($val) {
    return htmlspecialchars($val ?? '', ENT_QUOTES, 'UTF-8');
}

// ── Ambil data profil ──────────────────────────────────────
$profil = ['nama' => 'Syauqi Etna Lazhuardhy', 'tagline' => '', 'deskripsi' => ''];
$r = mysqli_query($conn, "SELECT nama, tagline, deskripsi FROM profil ORDER BY id ASC LIMIT 1");
if ($r && mysqli_num_rows($r) > 0) {
    $profil = mysqli_fetch_assoc($r);
}

// ── Ambil semua skills ─────────────────────────────────────
$skills = [];
$r = mysqli_query($conn, "SELECT id, nama, level, urutan FROM skills ORDER BY urutan ASC");
if ($r) while ($row = mysqli_fetch_assoc($r)) $skills[] = $row;

// ── Ambil semua pengalaman ─────────────────────────────────
$pengalaman = [];
$r = mysqli_query($conn, "SELECT id, deskripsi, urutan FROM pengalaman ORDER BY urutan ASC");
if ($r) while ($row = mysqli_fetch_assoc($r)) $pengalaman[] = $row;

// ── Ambil semua sertifikat ─────────────────────────────────
$sertifikat = [];
$r = mysqli_query($conn, "SELECT id, judul, deskripsi, gambar FROM sertifikat ORDER BY id ASC");
if ($r) while ($row = mysqli_fetch_assoc($r)) $sertifikat[] = $row;

// ── Flash message ──────────────────────────────────────────
$flashMap = [
    'skill_tambah'       => ['ok',  'Skill berhasil ditambahkan!'],
    'skill_hapus'        => ['ok',  'Skill berhasil dihapus.'],
    'skill_gagal'        => ['err', 'Gagal memproses skill. Coba lagi.'],
    'pengalaman_tambah'  => ['ok',  'Pengalaman berhasil ditambahkan!'],
    'pengalaman_hapus'   => ['ok',  'Pengalaman berhasil dihapus.'],
    'pengalaman_gagal'   => ['err', 'Gagal memproses pengalaman.'],
    'sertifikat_tambah'  => ['ok',  'Sertifikat berhasil ditambahkan!'],
    'sertifikat_hapus'   => ['ok',  'Sertifikat berhasil dihapus.'],
    'sertifikat_gagal'   => ['err', 'Gagal memproses sertifikat. Pastikan file valid.'],
    'profil_update'      => ['ok',  'Profil berhasil diperbarui!'],
    'profil_gagal'       => ['err', 'Gagal memperbarui profil.'],
];
$status = $_GET['status'] ?? '';
$flash  = isset($flashMap[$status]) ? $flashMap[$status] : null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin — <?= e($profil['nama']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* ── Admin-specific styles ── */
        body { background: #080d14; color: #c9d1d9; font-family: 'Outfit', sans-serif; }

        #admin-wrap { max-width: 1100px; margin: 100px auto 60px; padding: 0 24px; }

        .admin-title {
            font-size: 2rem; font-weight: 700; color: #00c8ff;
            text-align: center; margin-bottom: 8px; font-family: 'Share Tech Mono', monospace;
        }
        .admin-sub { text-align: center; color: #7d8590; margin-bottom: 48px; }

        /* ── Flash toast ── */
        .toast-wrap { position: fixed; top: 80px; right: 24px; z-index: 9999; }
        .toast-pop {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 20px; border-radius: 10px; font-size: 14px;
            opacity: 0; transform: translateY(-10px);
            transition: all .3s ease; pointer-events: none;
            backdrop-filter: blur(8px);
        }
        .toast-pop.show   { opacity: 1; transform: translateY(0); }
        .toast-pop.hide   { opacity: 0; transform: translateY(-10px); }
        .toast-ok  { background: rgba(0,200,100,.15); border: 1px solid rgba(0,200,100,.4); color: #3de070; }
        .toast-err { background: rgba(220,50,50,.15);  border: 1px solid rgba(220,50,50,.4);  color: #ff7070; }
        .toast-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .toast-ok  .toast-dot { background: #3de070; }
        .toast-err .toast-dot { background: #ff7070; }

        /* ── Panel kartu ── */
        .admin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 24px; }

        .panel-card {
            background: #0d1520; border: 1px solid #1e2d40;
            border-radius: 16px; padding: 28px;
        }
        .panel-card h3 {
            font-size: 1rem; font-weight: 600; color: #00c8ff;
            margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
        }
        .panel-card h3 i { font-size: 18px; }

        /* ── Form elements ── */
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 12px; color: #7d8590; margin-bottom: 6px; }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%; padding: 10px 14px;
            background: #111926; border: 1px solid #1e2d40;
            border-radius: 8px; color: #c9d1d9; font-size: 14px;
            outline: none; transition: border-color .2s;
            font-family: 'Outfit', sans-serif;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus { border-color: #00c8ff; }
        .form-group textarea { resize: vertical; min-height: 80px; }

        .btn-admin {
            width: 100%; padding: 11px; border: none; border-radius: 8px;
            background: linear-gradient(135deg, #00c8ff, #0072ff);
            color: #fff; font-weight: 600; font-size: 14px;
            cursor: pointer; transition: opacity .2s;
        }
        .btn-admin:hover { opacity: .85; }

        /* ── Daftar item ── */
        hr.divider { border: none; border-top: 1px solid #1e2d40; margin: 20px 0; }

        .item-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }
        .item-list li {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 10px 12px; background: #111926;
            border-radius: 8px; border: 1px solid #1e2d40;
        }
        .item-badge {
            font-size: 10px; font-weight: 700; padding: 3px 8px;
            border-radius: 20px; white-space: nowrap; flex-shrink: 0; margin-top: 2px;
        }
        .badge-blue   { background: rgba(0,200,255,.15); color: #00c8ff; border: 1px solid rgba(0,200,255,.3); }
        .badge-purple { background: rgba(160,100,255,.15); color: #b07fff; border: 1px solid rgba(160,100,255,.3); }
        .badge-green  { background: rgba(0,200,100,.15); color: #3de070; border: 1px solid rgba(0,200,100,.3); }

        .item-text { flex: 1; font-size: 13px; line-height: 1.5; word-break: break-word; }
        .item-pct  { font-size: 12px; color: #00c8ff; font-weight: 600; white-space: nowrap; }

        .btn-hapus {
            padding: 4px 10px; border: 1px solid rgba(220,50,50,.4);
            background: rgba(220,50,50,.1); color: #ff7070;
            border-radius: 6px; font-size: 12px; cursor: pointer;
            text-decoration: none; white-space: nowrap; flex-shrink: 0;
            transition: background .2s;
        }
        .btn-hapus:hover { background: rgba(220,50,50,.25); }

        .empty-note { color: #7d8590; font-size: 13px; font-style: italic; }

        /* ── Profil card ── */
        .profil-card { grid-column: 1 / -1; }

        /* ── Cert preview ── */
        .cert-thumb {
            width: 48px; height: 36px; object-fit: cover;
            border-radius: 4px; flex-shrink: 0;
        }
        .cert-thumb-placeholder {
            width: 48px; height: 36px; background: #1e2d40;
            border-radius: 4px; flex-shrink: 0; display: flex;
            align-items: center; justify-content: center;
            font-size: 18px; color: #7d8590;
        }

        /* ── Skill bar preview ── */
        .skill-preview-bar {
            height: 4px; background: #1e2d40; border-radius: 4px;
            margin-top: 4px; overflow: hidden;
        }
        .skill-preview-fill { height: 100%; background: #00c8ff; border-radius: 4px; }

        @media (max-width: 600px) {
            .admin-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav id="navbar">
        <div class="nav-brand"><span>&gt;_</span> <?= e($profil['nama']); ?></div>
        <ul class="nav-links">
            <li><a href="index.php#home" class="nav-link">[ home ]</a></li>
            <li><a href="index.php#about" class="nav-link">[ about ]</a></li>
            <li><a href="index.php#sertifikat" class="nav-link">[ sertifikat ]</a></li>
            <li><a href="admin.php" class="nav-link active">[ admin ]</a></li>
        </ul>
    </nav>

    <!-- FLASH MESSAGE -->
    <?php if ($flash): ?>
    <div class="toast-wrap">
        <div class="toast-pop toast-<?= $flash[0]; ?>" role="alert">
            <span class="toast-dot"></span>
            <span><?= e($flash[1]); ?></span>
        </div>
    </div>
    <?php endif; ?>

    <div id="admin-wrap">
        <p class="admin-title">&gt;_ Admin Panel</p>
        <p class="admin-sub">Kelola konten portfolio kamu di sini — tambah atau hapus data kapan saja.</p>

        <div class="admin-grid">

            <!-- ══════════ PROFIL ══════════ -->
            <div class="panel-card profil-card">
                <h3><i class="bi bi-person-circle"></i> Edit Profil</h3>
                <form action="actions/aksi_update_profil.php" method="post">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= e($profil['nama']); ?>" placeholder="Nama kamu" required>
                    </div>
                    <div class="form-group">
                        <label>Tagline / Judul</label>
                        <input type="text" name="tagline" value="<?= e($profil['tagline']); ?>" placeholder="Contoh: Future Data Analyst | Web Developer">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi / About Me</label>
                        <textarea name="deskripsi" rows="4" placeholder="Ceritakan tentang diri kamu..."><?= e($profil['deskripsi']); ?></textarea>
                    </div>
                    <button type="submit" class="btn-admin"><i class="bi bi-save"></i> Simpan Profil</button>
                </form>
            </div>

            <!-- ══════════ SKILLS ══════════ -->
            <div class="panel-card">
                <h3><i class="bi bi-bar-chart-line"></i> Tambah Skill</h3>
                <form action="actions/aksi_tambah_skill.php" method="post">
                    <div class="form-group">
                        <label>Nama Skill</label>
                        <input type="text" name="nama" placeholder="Contoh: Python" required>
                    </div>
                    <div class="form-group">
                        <label>Level (0–100)</label>
                        <input type="number" name="level" placeholder="Contoh: 75" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label>Urutan Tampil</label>
                        <input type="number" name="urutan" placeholder="Contoh: 1" min="0">
                    </div>
                    <button type="submit" class="btn-admin"><i class="bi bi-plus-circle"></i> Tambah Skill</button>
                </form>

                <hr class="divider">
                <h3 style="color:#7d8590;font-size:.85rem;margin-bottom:12px;">Daftar Skill</h3>
                <?php if (empty($skills)): ?>
                    <p class="empty-note">// belum ada skill</p>
                <?php else: ?>
                    <ul class="item-list">
                        <?php foreach ($skills as $s): ?>
                        <li>
                            <span class="item-badge badge-blue"><?= e($s['level']); ?>%</span>
                            <div class="item-text">
                                <?= e($s['nama']); ?>
                                <div class="skill-preview-bar">
                                    <div class="skill-preview-fill" style="width:<?= (int)$s['level']; ?>%"></div>
                                </div>
                            </div>
                            <a class="btn-hapus" href="actions/aksi_hapus_skill.php?id=<?= (int)$s['id']; ?>"
                               onclick="return confirm('Hapus skill <?= e($s['nama']); ?>?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- ══════════ PENGALAMAN ══════════ -->
            <div class="panel-card">
                <h3><i class="bi bi-briefcase"></i> Tambah Pengalaman</h3>
                <form action="actions/aksi_tambah_pengalaman.php" method="post">
                    <div class="form-group">
                        <label>Deskripsi Pengalaman</label>
                        <textarea name="deskripsi" rows="4" placeholder="Contoh: Staff Departemen Adwel - INFORSA (2024)" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Urutan Tampil</label>
                        <input type="number" name="urutan" placeholder="Contoh: 1" min="0">
                    </div>
                    <button type="submit" class="btn-admin"><i class="bi bi-plus-circle"></i> Tambah Pengalaman</button>
                </form>

                <hr class="divider">
                <h3 style="color:#7d8590;font-size:.85rem;margin-bottom:12px;">Daftar Pengalaman</h3>
                <?php if (empty($pengalaman)): ?>
                    <p class="empty-note">// belum ada pengalaman</p>
                <?php else: ?>
                    <ul class="item-list">
                        <?php foreach ($pengalaman as $p): ?>
                        <li>
                            <span class="item-badge badge-purple">EXP</span>
                            <span class="item-text"><?= e($p['deskripsi']); ?></span>
                            <a class="btn-hapus" href="actions/aksi_hapus_pengalaman.php?id=<?= (int)$p['id']; ?>"
                               onclick="return confirm('Hapus pengalaman ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- ══════════ SERTIFIKAT ══════════ -->
            <div class="panel-card">
                <h3><i class="bi bi-award"></i> Tambah Sertifikat</h3>
                <form action="actions/aksi_tambah_sertifikat.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Judul Sertifikat</label>
                        <input type="text" name="judul" placeholder="Contoh: INFORSA Certificate" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat sertifikat..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Upload Gambar (JPG/PNG/WEBP)</label>
                        <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" id="file-cert">
                        <span id="file-name" style="font-size:12px;color:#7d8590;margin-top:4px;display:block;">Belum ada file dipilih</span>
                    </div>
                    <button type="submit" class="btn-admin"><i class="bi bi-plus-circle"></i> Tambah Sertifikat</button>
                </form>

                <hr class="divider">
                <h3 style="color:#7d8590;font-size:.85rem;margin-bottom:12px;">Daftar Sertifikat</h3>
                <?php if (empty($sertifikat)): ?>
                    <p class="empty-note">// belum ada sertifikat</p>
                <?php else: ?>
                    <ul class="item-list">
                        <?php foreach ($sertifikat as $cert): ?>
                        <li>
                            <?php if (!empty($cert['gambar'])): ?>
                                <img src="<?= e($cert['gambar']); ?>" alt="<?= e($cert['judul']); ?>" class="cert-thumb"
                                     onerror="this.style.display='none'">
                            <?php else: ?>
                                <div class="cert-thumb-placeholder"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                            <span class="item-badge badge-green">CERT</span>
                            <span class="item-text"><?= e($cert['judul']); ?></span>
                            <a class="btn-hapus" href="actions/aksi_hapus_sertifikat.php?id=<?= (int)$cert['id']; ?>"
                               onclick="return confirm('Hapus sertifikat <?= e($cert['judul']); ?>?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

        </div><!-- /admin-grid -->
    </div><!-- /admin-wrap -->

    <footer style="text-align:center;padding:20px;color:#7d8590;font-size:13px;border-top:1px solid #1e2d40;">
        &copy; 2026 <?= e($profil['nama']); ?> — Admin Panel
    </footer>

    <script>
        // Flash toast auto-show & hide
        const toast = document.querySelector('.toast-pop');
        if (toast) {
            setTimeout(() => toast.classList.add('show'), 80);
            setTimeout(() => toast.classList.add('hide'), 3500);
        }

        // File input nama file
        const fileInput = document.getElementById('file-cert');
        const fileName  = document.getElementById('file-name');
        if (fileInput && fileName) {
            fileInput.addEventListener('change', () => {
                fileName.textContent = fileInput.files[0]
                    ? fileInput.files[0].name
                    : 'Belum ada file dipilih';
            });
        }
    </script>
</body>
</html>
