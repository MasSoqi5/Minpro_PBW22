<?php
// ================================================
//  config/db.php
//  Koneksi ke MySQL — sesuaikan jika perlu
// ================================================

$host   = 'localhost';
$user   = 'root';      // default XAMPP
$pass   = '';          // default XAMPP kosong
$dbname = 'portfolio_syauqi';

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    die('
    <div style="font-family:Arial;padding:40px;background:#f4f4f4;color:red;">
        <h2>❌ Koneksi Database Gagal</h2>
        <p>' . $conn->connect_error . '</p>
        <p>Pastikan:</p>
        <ul>
            <li>XAMPP sudah dijalankan (Apache + MySQL)</li>
            <li>Database <b>portfolio_syauqi</b> sudah dibuat lewat phpMyAdmin</li>
            <li>Username/password di config/db.php sudah benar</li>
        </ul>
    </div>
    ');
}
?>