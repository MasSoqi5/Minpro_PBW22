# 🌐 Portfolio Website — Syauqi Etna Lazhuardhy

> Website portfolio pribadi yang dibangun dengan PHP Native + MySQL, menampilkan informasi profil, skill, pengalaman, dan sertifikat secara dinamis dengan tampilan modern bertema dark/cyberpunk.

---

## ✨ Tampilan & Fitur

### 🦸 Hero Section
Bagian pertama yang langsung terlihat saat website dibuka. Berisi:
- **Nama & Tagline** — ditarik langsung dari database tabel `profil`
- **Typing Animation** — teks mengetik otomatis bergantian (dibuat dengan JavaScript murni)
- **Foto Profil** — ditampilkan dengan efek lingkaran dan border animasi
- **Partikel Background** — titik-titik bergerak yang saling terhubung (Canvas API)
- **Custom Cursor** — kursor unik yang mengikuti gerakan mouse dengan efek lag halus
- **Scroll Progress Bar** — garis tipis di atas layar yang menunjukkan seberapa jauh halaman telah di-scroll
- **Loading Screen** — animasi loading bar saat pertama kali halaman dibuka

### 👤 About & Skills Section
Bagian tentang diri dan kemampuan. Berisi:
- **Deskripsi diri** — diambil dari kolom `deskripsi` tabel `profil`
- **Skills Bar** — progress bar untuk setiap skill dengan persentase level, data dari tabel `skills`
- **Pengalaman** — daftar pengalaman organisasi/proyek dari tabel `pengalaman`
- **Reveal Animation** — setiap elemen muncul dengan animasi fade-in saat di-scroll

### 🏆 Sertifikat Section
Menampilkan kartu sertifikat yang dimiliki. Berisi:
- **Card Sertifikat** — menampilkan gambar, judul, dan deskripsi sertifikat
- **Data dinamis** — semua sertifikat diambil dari tabel `sertifikat` di database
- **Hover Effect** — kartu bergerak sedikit ke atas saat di-hover

### ⚙️ Admin Panel
Halaman khusus untuk mengelola seluruh konten website tanpa perlu menyentuh kode. Fitur:
- **Edit Profil** — ubah nama, tagline, dan deskripsi diri
- **Tambah / Hapus Skill** — kelola daftar skill beserta level persentasenya
- **Tambah / Hapus Pengalaman** — kelola daftar pengalaman
- **Tambah / Hapus Sertifikat** — upload gambar sertifikat + judul + deskripsi
- **Flash Message** — notifikasi sukses/gagal setiap aksi
- Diakses melalui: `localhost/portfolio_syauqi/admin.php`

---

## 🛠️ Tech Stack

| Teknologi | Kegunaan |
|---|---|
| **PHP Native** | Backend — mengambil & menampilkan data dari database |
| **MySQL** | Database — menyimpan data profil, skill, pengalaman, sertifikat |
| **HTML5** | Struktur halaman web |
| **CSS3** | Styling — tampilan dark theme, animasi, responsive |
| **JavaScript (Vanilla)** | Efek interaktif — partikel, typing, cursor, scroll, animasi |
| **XAMPP** | Local server (Apache + MySQL) untuk menjalankan PHP |
| **Bootstrap Icons** | Ikon-ikon yang digunakan di seluruh halaman |
| **Google Fonts** | Font `Outfit` dan `Share Tech Mono` |

---

## 🗄️ Struktur Database

Database: `portfolio_syauqi`

```sql
-- Tabel profil
CREATE TABLE profil (
  id        INT PRIMARY KEY AUTO_INCREMENT,
  nama      VARCHAR(100),
  tagline   VARCHAR(255),
  deskripsi TEXT,
  foto      VARCHAR(255)
);

-- Tabel skills
CREATE TABLE skills (
  id      INT PRIMARY KEY AUTO_INCREMENT,
  nama    VARCHAR(100),
  level   INT,
  urutan  INT
);

-- Tabel pengalaman
CREATE TABLE pengalaman (
  id         INT PRIMARY KEY AUTO_INCREMENT,
  deskripsi  TEXT,
  urutan     INT
);

-- Tabel sertifikat
CREATE TABLE sertifikat (
  id         INT PRIMARY KEY AUTO_INCREMENT,
  judul      VARCHAR(255),
  deskripsi  TEXT,
  gambar     VARCHAR(255)
);
```

---

## 📁 Struktur Folder

```
portfolio_syauqi/
├── actions/
│   ├── aksi_tambah_skill.php
│   ├── aksi_hapus_skill.php
│   ├── aksi_tambah_pengalaman.php
│   ├── aksi_hapus_pengalaman.php
│   ├── aksi_tambah_sertifikat.php
│   ├── aksi_hapus_sertifikat.php
│   └── aksi_update_profil.php
├── config/
│   └── db.php               ← konfigurasi koneksi database
├── uploads/                 ← folder gambar sertifikat yang diupload
├── index.php                ← halaman utama portfolio
├── admin.php                ← halaman admin panel
├── style.css                ← seluruh styling website
└── script.js                ← seluruh efek JavaScript
```

---

## 🚀 Cara Install & Menjalankan

### Prasyarat
- [XAMPP](https://www.apachefriends.org/) sudah terinstall
- Apache & MySQL sudah **Running** di XAMPP Control Panel

### Langkah-langkah

**1. Clone repository ini**
```bash
git clone https://github.com/USERNAME/NAMA_REPO.git
```

**2. Pindahkan ke folder htdocs**
```
C:\xampp\htdocs\portfolio_syauqi\
```

**3. Buat database di phpMyAdmin**
- Buka `localhost/phpmyadmin`
- Buat database baru bernama `portfolio_syauqi`
- Import atau jalankan query SQL di atas untuk membuat tabel-tabelnya

**4. Sesuaikan konfigurasi database**

Buka file `config/db.php` dan sesuaikan:
```php
$host   = 'localhost';
$user   = 'root';
$pass   = '';                   // kosong = default XAMPP
$dbname = 'portfolio_syauqi';
```

**5. Buka di browser**
```
http://localhost/portfolio_syauqi/index.php
```

**6. Isi konten lewat Admin Panel**
```
http://localhost/portfolio_syauqi/admin.php
```

---

## 👨‍💻 Tentang Pembuat

**Syauqi Etna Lazhuardhy**
Mahasiswa Sistem Informasi Universitas Mulawarman angkatan 2024.
Tertarik di bidang Web Developer dan Data Analyst.

---

## 📄 Lisensi

Project ini dibuat untuk keperluan pembelajaran mata kuliah Pemrograman Berbasis Web (PBW).
© 2026 Syauqi Etna Lazhuardhy
