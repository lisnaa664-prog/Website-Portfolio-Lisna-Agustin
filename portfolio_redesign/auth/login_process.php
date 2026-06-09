<?php
/**
 * auth/login_process.php
 * Proses autentikasi login – redirect admin ke dashboard
 */
require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../login.php');
}

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    setFlash('error', 'Email dan password wajib diisi.');
    redirect('../login.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash('error', 'Format email tidak valid.');
    redirect('../login.php');
}

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    setFlash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
    redirect('../login.php');
}

if (!$user || !password_verify($password, $user['password'])) {
    setFlash('error', 'Email atau password salah.');
    redirect('../login.php');
}

// Login berhasil
session_regenerate_id(true);
$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role']  = $user['role'];

// Redirect berdasarkan role
if ($user['role'] === 'admin') {
    setFlash('success', 'Selamat datang, Admin ' . $user['name'] . '! 🚀');
    redirect('../admin/index.php');
} else {
    setFlash('success', 'Selamat datang, ' . $user['name'] . '! 👋');
    redirect('../index.php');
}
