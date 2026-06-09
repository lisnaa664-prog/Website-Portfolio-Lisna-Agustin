<?php
/**
 * admin/includes/auth_check.php
 * Include di setiap halaman admin untuk cek login & role
 */
require_once __DIR__ . '/../../config/koneksi.php';

if (!isLoggedIn()) {
    setFlash('error', 'Silakan login terlebih dahulu.');
    redirect('../../login.php');
}

try {
    $pdo  = getDB();
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $adminUser = $stmt->fetch();
    if (!$adminUser || $adminUser['role'] !== 'admin') {
        setFlash('error', 'Akses ditolak. Hanya admin yang diizinkan.');
        redirect('../../index.php');
    }
} catch (PDOException $e) {
    die('DB Error: ' . e($e->getMessage()));
}
