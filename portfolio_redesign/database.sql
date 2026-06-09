-- ============================================================
-- DATABASE SCRIPT: portfolio_db (Updated with Admin Dashboard)
-- Portfolio Website – Lisna Agustin
-- ============================================================

CREATE DATABASE IF NOT EXISTS portfolio_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

-- ============================================================
-- TABEL: users
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
  id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  name       VARCHAR(100)  NOT NULL,
  email      VARCHAR(150)  NOT NULL UNIQUE,
  password   VARCHAR(255)  NOT NULL,
  role       ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: messages
-- ============================================================
CREATE TABLE IF NOT EXISTS messages (
  id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  name       VARCHAR(100)  NOT NULL,
  email      VARCHAR(150)  NOT NULL,
  subject    VARCHAR(200)  NULL,
  message    TEXT          NOT NULL,
  is_read    TINYINT(1)    NOT NULL DEFAULT 0,
  created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: projects
-- ============================================================
CREATE TABLE IF NOT EXISTS projects (
  id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  title       VARCHAR(200)  NOT NULL,
  description TEXT          NOT NULL,
  technologies VARCHAR(500) NULL,
  github_url  VARCHAR(500)  NULL,
  demo_url    VARCHAR(500)  NULL,
  thumbnail   VARCHAR(300)  NULL,
  thumb_emoji VARCHAR(10)   NULL DEFAULT '💻',
  sort_order  INT           NOT NULL DEFAULT 0,
  is_active   TINYINT(1)    NOT NULL DEFAULT 1,
  created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: skills
-- ============================================================
CREATE TABLE IF NOT EXISTS skills (
  id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  name       VARCHAR(100)  NOT NULL,
  category   ENUM('hard','soft','tool') NOT NULL DEFAULT 'hard',
  level      TINYINT       NOT NULL DEFAULT 50 COMMENT '0-100',
  icon       VARCHAR(10)   NULL DEFAULT '⚡',
  sort_order INT           NOT NULL DEFAULT 0,
  is_active  TINYINT(1)    NOT NULL DEFAULT 1,
  created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: about_me
-- ============================================================
CREATE TABLE IF NOT EXISTS about_me (
  id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  full_name   VARCHAR(150)  NOT NULL,
  tagline     VARCHAR(250)  NULL,
  description TEXT          NOT NULL,
  photo       VARCHAR(300)  NULL,
  email       VARCHAR(150)  NULL,
  phone       VARCHAR(30)   NULL,
  location    VARCHAR(150)  NULL,
  birth_year  YEAR          NULL,
  university  VARCHAR(200)  NULL,
  major       VARCHAR(200)  NULL,
  gpa         DECIMAL(3,2)  NULL,
  updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: cv_files
-- ============================================================
CREATE TABLE IF NOT EXISTS cv_files (
  id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  filename    VARCHAR(300)  NOT NULL,
  original_name VARCHAR(300) NOT NULL,
  file_size   INT           NULL,
  is_active   TINYINT(1)    NOT NULL DEFAULT 1,
  uploaded_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: social_media
-- ============================================================
CREATE TABLE IF NOT EXISTS social_media (
  id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  platform   VARCHAR(50)   NOT NULL,
  label      VARCHAR(100)  NOT NULL,
  url        VARCHAR(500)  NOT NULL,
  icon       VARCHAR(10)   NOT NULL DEFAULT '🔗',
  sort_order INT           NOT NULL DEFAULT 0,
  is_active  TINYINT(1)    NOT NULL DEFAULT 1,
  created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: favorite_music
-- ============================================================
CREATE TABLE IF NOT EXISTS favorite_music (
  id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  title       VARCHAR(200)  NOT NULL,
  artist      VARCHAR(200)  NOT NULL,
  year        YEAR          NULL,
  thumbnail   VARCHAR(300)  NULL,
  thumb_emoji VARCHAR(10)   NULL DEFAULT '🎵',
  audio_url   VARCHAR(500)  NULL COMMENT 'URL mp3 atau embed',
  audio_file  VARCHAR(300)  NULL COMMENT 'file lokal upload',
  duration    VARCHAR(10)   NULL DEFAULT '0:00',
  bg_color    VARCHAR(200)  NULL DEFAULT 'linear-gradient(135deg,#c084fc,#818cf8)',
  sort_order  INT           NOT NULL DEFAULT 0,
  is_active   TINYINT(1)    NOT NULL DEFAULT 1,
  created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATA SEED
-- ============================================================

-- Admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Admin Portfolio', 'admin@portfolio.com',
 '$2y$12$6dHmElWHN7jXK.aHivJbae.jzxbv5k4jMqLgL/P2m.Gvnr7FPxT.2', 'admin'),
('Lisna Agustin', 'lisnaa664@gmail.com',
 '$2y$12$6dHmElWHN7jXK.aHivJbae.jzxbv5k4jMqLgL/P2m.Gvnr7FPxT.2', 'admin');

-- About Me seed
INSERT INTO about_me (full_name, tagline, description, photo, email, phone, location, university, major, gpa) VALUES
('Lisna Agustin', 'Mahasiswa Informatika | Web Developer',
 'Mahasiswa Teknik Informatika semester 4 di Universitas Samudra yang sedang belajar pengembangan web dan dasar pemrograman. Tertarik memahami cara kerja website dan terus berkembang melalui proyek nyata.',
 'images/foto_lisna.jpeg', 'lisnaa664@gmail.com', '+62 821-3745-5140',
 'Kota Langsa, Indonesia', 'Universitas Samudra', 'Teknik Informatika', 3.72);

-- Projects seed
INSERT INTO projects (title, description, technologies, github_url, demo_url, thumb_emoji, sort_order) VALUES
('Website Wisata Kalimantan Barat',
 'Website informasi destinasi wisata yang ada di Kalimantan Barat. Menampilkan berbagai tempat wisata menarik seperti Danau Sentarum, Pantai Kijing, dan wisata budaya Melayu.',
 'HTML,CSS,JavaScript,PHP',
 'https://lisnaa664-prog.github.io/web_wisata_kalimantan', '', '🏝️', 1),
('Website Pemesanan Bus',
 'Website pemesanan tiket bus secara online yang menyediakan fitur pencarian jadwal, pemilihan kursi, serta proses pemesanan tiket untuk perjalanan antar kota.',
 'HTML,CSS,JavaScript,PHP,MySQL', '', '', '🚌', 2),
('Grafik Komputer dengan OpenGL',
 'Tugas Komputer Grafik menggunakan OpenGL (C++) yang berisi latihan dasar pembuatan objek 2D seperti titik, garis, lingkaran, persegi, dan segitiga.',
 'C++,OpenGL,GLFW',
 'https://github.com/lisnaa664-prog/opengl-tugas', '', '🎮', 3);

-- Skills seed
INSERT INTO skills (name, category, level, icon, sort_order) VALUES
('HTML & CSS',    'hard', 82, '🌐', 1),
('JavaScript',    'hard', 72, '⚡', 2),
('PHP',           'hard', 68, '🐘', 3),
('C++',           'hard', 78, '⚙️', 4),
('MySQL',         'hard', 65, '🗄️', 5),
('Kerja Sama',    'soft', 90, '🤝', 1),
('Komunikasi',    'soft', 85, '💬', 2),
('Problem Solving','soft',80, '🧩', 3),
('Adaptasi',      'soft', 88, '📈', 4),
('VS Code',       'tool', 85, '💻', 1),
('XAMPP',         'tool', 80, '🛠️', 2),
('Microsoft Office','tool',90,'📝', 3);

-- Social media seed
INSERT INTO social_media (platform, label, url, icon, sort_order) VALUES
('GitHub',    'github',    'https://github.com/lisnaa664-prog',                                          '🐙', 1),
('LinkedIn',  'linkedin',  'https://www.linkedin.com/in/lisna-agustin-b5221b406/',                       '💼', 2),
('Instagram', 'instagram', 'https://www.instagram.com/lisnaa_la?igsh=MTd1dnpucnA2dXhzag==',             '📸', 3),
('Email',     'email',     'mailto:lisnaa664@gmail.com',                                                 '📧', 4),
('WhatsApp',  'whatsapp',  'https://wa.me/6282137455140',                                                '📱', 5);

-- Music seed
INSERT INTO favorite_music (title, artist, year, thumb_emoji, bg_color, duration, sort_order) VALUES
('Stay',    'BLACKPINK', 2022, '🌸', 'https://open.spotify.com/embed/track/3tP6QKbXvtrxiDI7QwKyUf?utm_source=generator', 'linear-gradient(135deg,#ec4899,#8b5cf6)', '3:30', 1),
('Racecar', 'izna',      2024, '🏎️', 'https://open.spotify.com/embed/track/3ylwpGWNGip7lRhNCpCBLR?utm_source=generator', 'linear-gradient(135deg,#06b6d4,#8b5cf6)', '3:15', 2);


-- ============================================================
-- UPDATE: Pastikan kolom audio_url ada (untuk instalasi lama)
-- Jalankan jika sudah pernah import database.sql sebelumnya:
-- ============================================================
-- ALTER TABLE favorite_music ADD COLUMN IF NOT EXISTS audio_url VARCHAR(500) NULL COMMENT 'URL mp3, Spotify embed, atau YouTube';

-- Update Spotify embed untuk lagu yang sudah ada:
-- UPDATE favorite_music SET audio_url = 'https://open.spotify.com/embed/track/3tP6QKbXvtrxiDI7QwKyUf?utm_source=generator' WHERE title = 'Stay' AND artist = 'BLACKPINK';
-- UPDATE favorite_music SET audio_url = 'https://open.spotify.com/embed/track/3ylwpGWNGip7lRhNCpCBLR?utm_source=generator' WHERE title = 'Racecar' AND artist = 'izna';
