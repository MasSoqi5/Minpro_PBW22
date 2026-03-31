<?php
// ================================================
//  index.php — Portfolio Syauqi (Dinamis dari DB)
// ================================================
require_once 'config/db.php';

// --- Ambil data dari database ---
$profil     = $conn->query("SELECT * FROM profil LIMIT 1")->fetch_assoc();
$skills     = $conn->query("SELECT * FROM skills ORDER BY urutan ASC")->fetch_all(MYSQLI_ASSOC);
$pengalaman = $conn->query("SELECT * FROM pengalaman ORDER BY urutan ASC")->fetch_all(MYSQLI_ASSOC);
$sertifikat = $conn->query("SELECT * FROM sertifikat ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);

// Helper escape output
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Portfolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar">
        <div class="logo">MyPortfolio</div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About Me</a></li>
            <li><a href="#CERTIFICATES">Certificates</a></li>
        </ul>
    </nav>

    <!-- ================= HOME SECTION ================= -->
    <!-- Data: nama, tagline, foto — dari tabel profil -->
    <section id="home" class="home">
        <div class="home-content">
            <div class="text">
                <h1>Hi, I'm <span><?= e($profil['nama']) ?></span></h1>
                <p><?= e($profil['tagline']) ?></p>
                <a href="#about" class="btn">Learn More</a>
            </div>
            <div class="image">
                <img src="<?= e($profil['foto']) ?>" alt="Profile Picture">
            </div>
        </div>
    </section>

    <!-- ================= ABOUT SECTION ================= -->
    <section id="about" class="about">
        <h2>About Me</h2>

        <!-- Data: deskripsi — dari tabel profil -->
        <p class="about-desc">
            <?= e($profil['deskripsi']) ?>
        </p>

        <!-- ===== SKILLS — dari tabel skills ===== -->
        <div class="skills">
            <h3>My Skills</h3>

            <?php foreach ($skills as $skill): ?>
            <div class="skill">
                <p><?= e($skill['nama']) ?></p>
                <div class="progress">
                    <div class="progress-bar" style="width: <?= (int)$skill['level'] ?>%;">
                        <?= (int)$skill['level'] ?>%
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <!-- ===== EXPERIENCE — dari tabel pengalaman ===== -->
        <div class="experience">
            <h3>Experience</h3>
            <ul>
                <?php foreach ($pengalaman as $exp): ?>
                <li><?= e($exp['deskripsi']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

    </section>

    <!-- ================= CERTIFICATES SECTION ================= -->
    <!-- Data: gambar, judul, deskripsi — dari tabel sertifikat -->
    <section id="CERTIFICATES" class="CERTIFICATES">
        <h2>CERTIFICATES</h2>

        <div class="certificate-grid">
            <?php foreach ($sertifikat as $cert): ?>
            <div class="card">
                <img src="<?= e($cert['gambar']) ?>" alt="<?= e($cert['judul']) ?>">
                <h3><?= e($cert['judul']) ?></h3>
                <p><?= e($cert['deskripsi']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

    </section>

    <!-- ================= FOOTER ================= -->
    <footer>
        <p>&copy; <?= date('Y') ?> <?= e($profil['nama']) ?>. All Rights Reserved.</p>
    </footer>

</body>
</html>