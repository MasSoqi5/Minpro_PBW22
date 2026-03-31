<?php
require __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = trim($_POST['nama']   ?? '');
    $level  = (int) ($_POST['level'] ?? 0);
    $urutan = (int) ($_POST['urutan'] ?? 0);

    if ($nama === '' || $level < 0 || $level > 100) {
        header('Location: ../admin.php?status=skill_gagal');
        exit;
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO skills (nama, level, urutan) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sii', $nama, $level, $urutan);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../admin.php?status=skill_tambah');
    } else {
        header('Location: ../admin.php?status=skill_gagal');
    }
    mysqli_stmt_close($stmt);
} else {
    header('Location: ../admin.php');
}
exit;
?>
