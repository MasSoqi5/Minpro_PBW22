<?php
require __DIR__ . '/../config/koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    // Ambil nama file gambar dulu sebelum dihapus, biar file-nya juga ikut terhapus
    $res = mysqli_query($conn, "SELECT gambar FROM sertifikat WHERE id = $id");
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $filePath = __DIR__ . '/../' . $row['gambar'];
        if (!empty($row['gambar']) && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM sertifikat WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../admin.php?status=sertifikat_hapus');
    } else {
        header('Location: ../admin.php?status=sertifikat_gagal');
    }
    mysqli_stmt_close($stmt);
} else {
    header('Location: ../admin.php');
}
exit;
?>
