<?php
require __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $urutan    = (int) ($_POST['urutan'] ?? 0);

    if ($deskripsi === '') {
        header('Location: ../admin.php?status=pengalaman_gagal');
        exit;
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO pengalaman (deskripsi, urutan) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'si', $deskripsi, $urutan);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../admin.php?status=pengalaman_tambah');
    } else {
        header('Location: ../admin.php?status=pengalaman_gagal');
    }
    mysqli_stmt_close($stmt);
} else {
    header('Location: ../admin.php');
}
exit;
?>
