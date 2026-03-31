-- ================================================
--  DATABASE: portfolio_syauqi
--  Import file ini lewat phpMyAdmin
-- ================================================

CREATE DATABASE IF NOT EXISTS portfolio_syauqi
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portfolio_syauqi;

-- -----------------------------------------------
-- Tabel: profil
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS profil (
  id         INT PRIMARY KEY AUTO_INCREMENT,
  nama       VARCHAR(150) NOT NULL,
  tagline    VARCHAR(255) NOT NULL,
  deskripsi  TEXT         NOT NULL,
  foto       VARCHAR(255) NOT NULL
);

-- -----------------------------------------------
-- Tabel: skills
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS skills (
  id      INT PRIMARY KEY AUTO_INCREMENT,
  nama    VARCHAR(100) NOT NULL,
  level   INT          NOT NULL,
  urutan  INT          DEFAULT 0
);

-- -----------------------------------------------
-- Tabel: pengalaman
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS pengalaman (
  id          INT PRIMARY KEY AUTO_INCREMENT,
  deskripsi   TEXT NOT NULL,
  urutan      INT  DEFAULT 0
);

-- -----------------------------------------------
-- Tabel: sertifikat
-- -----------------------------------------------
CREATE TABLE IF NOT EXISTS sertifikat (
  id          INT PRIMARY KEY AUTO_INCREMENT,
  gambar      VARCHAR(255) NOT NULL,
  judul       VARCHAR(200) NOT NULL,
  deskripsi   TEXT         NOT NULL
);

-- ================================================
-- DATA SAMPLE (sesuai website kamu)
-- ================================================

INSERT INTO profil (nama, tagline, deskripsi, foto) VALUES (
  'Syauqi Etna Lazhuardhy',
  'Future Data Analyst | Future Web Developer | Proud Collage of Mulawarman University',
  'Nama Saya Syauqi Etna Lazhuardhy, mahasiswa Sistem Informasi angkatan 2024 yang saat ini sedang menempuh semester 4 dan mendalami bidang web developer dan Data Analyst. Saya memiliki ketertarikan pada visualisasi, penyampaian serta merancang website.',
  'Foto gw.jpeg'
);

INSERT INTO skills (nama, level, urutan) VALUES
  ('HTML',       50, 1),
  ('CSS',        60, 2),
  ('JavaScript', 40, 3);

INSERT INTO pengalaman (deskripsi, urutan) VALUES
  ('Staff Departemen Adwel - INFORSA (2024)', 1),
  ('Project Java TaniKita: projek yang menghitung pencatatan hasil panen petani yang akurat dan terjamin (2025)', 2),
  ('Sedang belajar bahasa pemrograman flutter/dart maupun html/css untuk menyempurnakan skill web dan app mobile', 3);

INSERT INTO sertifikat (gambar, judul, deskripsi) VALUES (
  'Syauqi Etna Lazhuardhy.png',
  'INFORSA Certificate',
  'Sertifikat penuntasan masa kerja sebagai anggota departemen ADWEL dalam Himpunan Mahasiswa Sistem Informasi tahun jabatan 2024-2025'
);
