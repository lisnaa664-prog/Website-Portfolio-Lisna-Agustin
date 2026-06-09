# 🌐 Website Portofolio Lisna Agustin

### *Personal Portfolio Website — Dark Futuristic & Glassmorphism*

## 📋 Deskripsi Singkat

**Website Portofolio Lisna Agustin** adalah sebuah website portofolio pribadi yang dirancang untuk menampilkan profil, keahlian, dan hasil karya proyek secara profesional dan interaktif. Website ini dibangun menggunakan **PHP Native** tanpa framework, dengan konsep desain **Dark Futuristic** dan **Glassmorphism** yang modern dan elegan.

Website ini juga dilengkapi dengan **Dashboard Admin** yang memungkinkan pengelolaan seluruh isi konten website secara dinamis melalui sistem CRUD — mulai dari data profil, proyek, keahlian, hingga lagu favorit — tanpa perlu mengubah kode secara langsung.

---

## 🎨 Tampilan & Konsep Desain

Website ini menggunakan konsep desain modern yang terinspirasi dari antarmuka futuristik dan estetika gelap (*dark mode*), dengan elemen-elemen berikut:

| Konsep | Keterangan |
|---|---|
| 🌑 **Dark Futuristic** | Latar belakang gelap dengan nuansa ungu, biru indigo, dan pink yang elegan |
| 🪟 **Glassmorphism** | Efek kaca buram (*frosted glass*) pada kartu dan komponen UI |
| ✨ **Gradient & Glow** | Gradasi warna ungu ke pink pada elemen penting dan efek cahaya halus |
| 💫 **Smooth Animation** | Animasi transisi halus, efek hover, dan scroll fade-in pada setiap elemen |
| 📱 **Responsive Design** | Tampilan menyesuaikan layar smartphone, tablet, dan desktop secara penuh |

---

## ✨ Fitur Utama

### 🌐 Website Publik

- **Halaman Home** — Hero section dengan animasi typing, statistik, dan foto profil berputar
- **Halaman About** — Profil lengkap, riwayat pendidikan, minat, dan tujuan karier
- **Halaman Skills** — Skill bar animasi berdasarkan level, kategori Hard/Soft/Tool
- **Halaman Projects** — Galeri proyek dengan foto thumbnail, badge teknologi, dan modal preview foto
- **Halaman CV** — Curriculum vitae lengkap dengan tombol download PDF
- **Halaman Contact** — Form kirim pesan yang tersimpan ke database + daftar sosial media dinamis
- **Section Musik** — Pemutar musik favorit berbasis **Spotify Web Embed** langsung di halaman utama

### 🛠️ Dashboard Admin

- **Overview Dashboard** — Statistik ringkas: jumlah proyek, skill, pesan masuk, dan lain-lain
- **CRUD Projects** — Tambah, edit, hapus proyek beserta upload foto thumbnail
- **CRUD Skills** — Tambah, edit, hapus skill dengan pengaturan level dan kategori
- **CRUD About Me** — Edit profil, deskripsi, dan upload foto
- **CRUD CV** — Upload, ganti, dan hapus file CV dalam format PDF
- **CRUD Contact & Sosmed** — Kelola link dan ikon sosial media secara dinamis
- **CRUD Musik Favorit** — Tambah, edit, hapus lagu dengan integrasi Spotify embed
- **Inbox Pesan** — Tampilan dua panel: daftar pesan dan isi pesan dengan fitur tandai dibaca

---

## 🚀 Teknologi yang Digunakan

### Bahasa Pemrograman & Markup

| Teknologi | Kegunaan |
|---|---|
| **HTML5** | Struktur halaman dan konten website |
| **CSS3** | Styling, animasi, glassmorphism, dan layout responsif |
| **JavaScript (ES6+)** | Interaktivitas, animasi scroll, music player, dan validasi form |
| **PHP Native** | Backend dinamis, routing halaman, dan pemrosesan data |
| **MySQL** | Penyimpanan seluruh data konten website |

### Library & External Resource

| Library | Kegunaan |
|---|---|
| **Google Fonts** | Font *Syne* (heading) dan *DM Sans* (body) |
| **Font Awesome** | Ikon-ikon antarmuka pengguna |
| **Spotify Web Embed** | Pemutar musik Spotify langsung di website |

---

## 🛠️ Tools Pendukung

| Tool | Fungsi |
|---|---|
| 💻 **Visual Studio Code** | Code editor utama untuk pengembangan website |
| 🖥️ **XAMPP** | Server lokal Apache + PHP + MySQL untuk pengembangan di localhost |
| 🗄️ **phpMyAdmin** | Antarmuka visual untuk mengelola database MySQL |
| 🌐 **Google Chrome** | Browser utama untuk pengujian tampilan dan DevTools |
| 🐙 **GitHub** | Version control dan repositori kode sumber |

---

## 📁 Struktur Folder Project

```
portfolio_redesign/
│
├── 📂 admin/                        # Dashboard Admin
│   ├── 📂 about/                    # Halaman kelola profil
│   │   └── index.php
│   ├── 📂 contact/                  # Kelola kontak & sosial media
│   │   └── index.php
│   ├── 📂 css/
│   │   └── admin.css                # Stylesheet dashboard admin
│   ├── 📂 cv/                       # Upload & kelola CV
│   │   └── index.php
│   ├── 📂 includes/                 # Komponen reusable admin
│   │   ├── auth_check.php           # Middleware cek login & role
│   │   ├── sidebar.php              # Sidebar navigasi admin
│   │   └── topnav.php               # Topbar navigasi admin
│   ├── 📂 js/
│   │   └── admin.js                 # JavaScript dashboard admin
│   ├── 📂 messages/                 # Inbox pesan masuk
│   │   └── index.php
│   ├── 📂 music/                    # Kelola musik favorit
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── index.php
│   ├── 📂 projects/                 # CRUD proyek portfolio
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── index.php
│   ├── 📂 skills/                   # CRUD keahlian
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── index.php
│   └── index.php                    # Halaman utama dashboard
│
├── 📂 auth/                         # Proses autentikasi
│   ├── login_process.php            # Proses login (redirect by role)
│   ├── logout.php                   # Proses logout
│   └── register_process.php         # Proses registrasi akun baru
│
├── 📂 config/                       # Konfigurasi global
│   ├── footer.php                   # Footer dinamis (dari database)
│   ├── koneksi.php                  # Koneksi PDO + helper functions
│   └── navbar.php                   # Navbar dengan deteksi admin
│
├── 📂 css/
│   └── style.css                    # Stylesheet utama website publik
│
├── 📂 files/                        # File statis (CV default)
│
├── 📂 images/                       # Foto profil & aset gambar
│
├── 📂 js/
│   └── script.js                    # JavaScript website publik
│
├── 📂 uploads/                      # File yang diupload via admin
│   ├── cv/                          # File CV (PDF)
│   ├── music/                       # Cover art & audio musik
│   ├── profile/                     # Foto profil
│   └── projects/                    # Foto thumbnail proyek
│
├── about.php                        # Halaman About Me
├── contact.php                      # Halaman Contact
├── cv.php                           # Halaman CV / Resume
├── database.sql                     # Script database (schema + seed)
├── index.php                        # Halaman Home (utama)
├── login.php                        # Halaman Login
├── portfolio.php                    # Halaman Portfolio / Projects
├── register.php                     # Halaman Register
├── skills.php                       # Halaman Skills                      
```

---

## ⚙️ Cara Menjalankan Project di Localhost

### Prasyarat

Pastikan perangkat sudah terinstal:
- ✅ [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 7.4+)
- ✅ Browser modern (Google Chrome, Firefox, Edge)

### Langkah-langkah

**1. Clone atau download repository ini**

```bash
git clone https://github.com/lisnaa664-prog/portfolio-website.git
```

Atau unduh sebagai ZIP lalu ekstrak.

**2. Salin folder ke htdocs XAMPP**

```
C:\xampp\htdocs\portfolio_redesign\
```

**3. Jalankan XAMPP**

Buka **XAMPP Control Panel**, lalu klik **Start** pada:
- ✅ Apache
- ✅ MySQL

**4. Import database**

- Buka browser, akses: `http://localhost/phpmyadmin`
- Klik **New** → buat database baru dengan nama `portfolio_db`
- Pilih tab **Import** → pilih file `database.sql` dari folder project
- Klik **Go** untuk mengeksekusi

**5. Sesuaikan konfigurasi database** *(jika diperlukan)*

Buka file `config/koneksi.php`, sesuaikan dengan konfigurasi lokal:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'root');      // sesuaikan username MySQL kamu
define('DB_PASS', '');          // sesuaikan password MySQL kamu
```

**6. Akses website di browser**

```
http://localhost/portfolio_redesign/
```

**7. Login ke Dashboard Admin**

```
http://localhost/portfolio_redesign/login.php
```

| Field    | Nilai                    |
|----------|--------------------------|
| Email    | `admin@portfolio.com`    |
| Password | `admin123`               |

---

## 🎯 Tujuan Pembuatan Website

Website portofolio ini dibuat dengan beberapa tujuan utama:

1. **Memperkenalkan diri secara digital** — Menampilkan identitas, latar belakang pendidikan, dan kepribadian secara profesional kepada siapa pun yang mengunjungi website
2. **Menampilkan karya dan proyek** — Menjadi wadah untuk mendokumentasikan hasil belajar dan proyek yang telah dikerjakan selama perkuliahan
3. **Mengembangkan kemampuan teknis** — Mempraktikkan ilmu pengembangan web yang dipelajari di perkuliahan, mulai dari desain antarmuka hingga pengelolaan database
4. **Membangun portofolio profesional** — Sebagai bukti nyata kemampuan kepada calon pemberi kerja atau kolaborator di masa depan
5. **Belajar membangun sistem lengkap** — Memahami alur kerja pengembangan website dari awal hingga akhir, termasuk sistem autentikasi, CRUD, dan manajemen file

---

## 👩‍💻 Author

<div align="center">

**Lisna Agustin**

🎓 Mahasiswa Teknik Informatika — Universitas Samudra, Kota Langsa

📧 [lisnaa664@gmail.com](mailto:lisnaa664@gmail.com)
🐙 [github.com/lisnaa664-prog](https://github.com/lisnaa664-prog)
💼 [linkedin.com/in/lisna-agustin-b5221b406](https://www.linkedin.com/in/lisna-agustin-b5221b406/)
📸 [instagram.com/lisnaa_la](https://www.instagram.com/lisnaa_la)

</div>

---

## 🙏 Penutup

Terima kasih telah mengunjungi dan membaca dokumentasi project ini. Website portofolio ini masih terus dalam tahap pengembangan seiring bertambahnya kemampuan dan proyek baru yang dikerjakan.

Jika terdapat pertanyaan, saran, atau ingin berkolaborasi, jangan ragu untuk menghubungi melalui email atau media sosial yang tertera di atas.

> *"Setiap baris kode adalah langkah kecil menuju tujuan yang lebih besar."*

---

<div align="center">

Dibuat dengan ❤️ oleh **Lisna Agustin** &nbsp;·&nbsp; © 2026

[![Visitors](https://img.shields.io/badge/Status-In%20Development-c084fc?style=flat-square)](https://github.com/lisnaa664-prog)

</div>
