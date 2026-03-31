<?php
require __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama']      ?? '');
    $tagline   = trim($_POST['tagline']   ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($nama === '') {
        header('Location: ../admin.php?status=profil_gagal');
        exit;
    }

    // Cek apakah sudah ada profil
    $cek = mysqli_query($conn, "SELECT id FROM profil LIMIT 1");
    if ($cek && mysqli_num_rows($cek) > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE profil SET nama=?, tagline=?, deskripsi=? WHERE id=1");
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO profil (nama, tagline, deskripsi) VALUES (?, ?, ?)");
    }

    mysqli_stmt_bind_param($stmt, 'sss', $nama, $tagline, $deskripsi);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../admin.php?status=profil_update');
    } else {
        header('Location: ../admin.php?status=profil_gagal');
    }
    mysqli_stmt_close($stmt);
} else {
    header('Location: ../admin.php');
}
exit;
?>
