<?php
require __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul']     ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($judul === '') {
        header('Location: ../admin.php?status=sertifikat_gagal');
        exit;
    }

    // Proses upload gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext      = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            header('Location: ../admin.php?status=sertifikat_gagal');
            exit;
        }

        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $namaFile = uniqid('cert_', true) . '.' . $ext;
        $tujuan   = $uploadDir . $namaFile;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuan)) {
            $gambar = 'uploads/' . $namaFile;
        } else {
            header('Location: ../admin.php?status=sertifikat_gagal');
            exit;
        }
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO sertifikat (judul, deskripsi, gambar) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $judul, $deskripsi, $gambar);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../admin.php?status=sertifikat_tambah');
    } else {
        header('Location: ../admin.php?status=sertifikat_gagal');
    }
    mysqli_stmt_close($stmt);
} else {
    header('Location: ../admin.php');
}
exit;
?>
