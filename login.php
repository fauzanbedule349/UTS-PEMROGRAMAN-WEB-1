<?php
session_start();
require_once 'config/database.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Stockify</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body class="login-body">
    <main class="login-layout">
        <section class="login-panel">
            <a class="brand login-brand" href="login.php">
                <span class="brand-mark material-symbols-outlined">inventory_2</span>
                <span><strong>Stockify</strong><small>Inventory Management</small></span>
            </a>
            <div class="login-heading">
                <p class="eyebrow">SELAMAT DATANG KEMBALI</p>
                <h1>Login ke akun Anda</h1>
                <p>Masuk untuk mengelola inventaris barang.</p>
            </div>
            <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="post" action="login.php" class="form-stack" data-validate>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" placeholder="Masukkan username" autocomplete="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input id="password" name="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required>
                        <button type="button" class="password-toggle" data-toggle-password="password" aria-label="Tampilkan password"><span class="material-symbols-outlined">visibility</span></button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-full">Sign In <span class="material-symbols-outlined">arrow_forward</span></button>
            </form>
            <p class="login-footnote">© <?= date('Y') ?> Stockify Inventory System</p>
        </section>
        <section class="login-visual">
            <div class="visual-orb orb-one"></div><div class="visual-orb orb-two"></div>
            <div class="visual-content">
                <div class="visual-icon material-symbols-outlined">inventory_2</div>
                <p class="eyebrow light">INVENTORY MADE SIMPLE</p>
                <h2>Kelola stok dengan lebih mudah.</h2>
                <p>Pantau barang, periksa kondisi, dan kelola data inventaris dari satu tempat.</p>
                <div class="visual-stat"><span class="material-symbols-outlined">verified_user</span> Pengelolaan inventaris yang terorganisir</div>
            </div>
        </section>
    </main>
</body>
</html>
