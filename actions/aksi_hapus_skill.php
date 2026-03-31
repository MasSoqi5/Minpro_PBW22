<?php
require __DIR__ . '/../config/koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "DELETE FROM skills WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../admin.php?status=skill_hapus');
    } else {
        header('Location: ../admin.php?status=skill_gagal');
    }
    mysqli_stmt_close($stmt);
} else {
    header('Location: ../admin.php');
}
exit;
?>
