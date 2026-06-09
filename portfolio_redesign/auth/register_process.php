<?php
/**
 * auth/register_process.php
 * Proses pendaftaran akun baru
 */
require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../register.php');
}

$name     = trim($_POST['name']     ?? '');
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');
$confirm  = trim($_POST['confirm']  ?? '');

// Validasi
if (!$name || !$email || !$password || !$confirm) {
    setFlash('error', 'Semua kolom wajib diisi.');
    redirect('../register.php');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('error', 'Format email tidak valid.');
    redirect('../register.php');
}
if (strlen($password) < 6) {
    setFlash('error', 'Password minimal 6 karakter.');
    redirect('../register.php');
}
if ($password !== $confirm) {
    setFlash('error', 'Konfirmasi password tidak cocok.');
    redirect('../register.php');
}

try {
    $pdo  = getDB();

    // Cek email duplikat
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->execute([$email]);
    if ($check->fetch()) {
        setFlash('error', 'Email sudah terdaftar. Gunakan email lain atau login.');
        redirect('../register.php');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')")
        ->execute([$name, $email, $hash]);

    setFlash('success', 'Akun berhasil dibuat! Silakan login.');
    redirect('../login.php');

} catch (PDOException $e) {
    setFlash('error', 'Gagal membuat akun. Silakan coba lagi.');
    redirect('../register.php');
}
