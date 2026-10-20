<?php
$pageTitle = $pageTitle ?? 'Stockify';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Stockify</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="dashboard.php">
            <span class="brand-mark material-symbols-outlined">inventory_2</span>
            <span><strong>Stockify</strong><small>Inventory Management</small></span>
        </a>
        <p class="nav-label">MENU UTAMA</p>
        <nav class="nav-links">
            <a class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
            <a class="<?= $currentPage === 'barang.php' ? 'active' : '' ?>" href="barang.php"><span class="material-symbols-outlined">inventory</span>Inventory</a>
            <a class="<?= $currentPage === 'tambah_barang.php' ? 'active' : '' ?>" href="tambah_barang.php"><span class="material-symbols-outlined">add_box</span>Tambah Barang</a>
        </nav>
        <div class="sidebar-bottom">
            <a href="logout.php" class="logout-link" data-confirm="Yakin ingin logout?"><span class="material-symbols-outlined">logout</span>Logout</a>
        </div>
    </aside>
    <div class="main-area">
        <header class="topbar">
            <button class="menu-toggle" id="menuToggle" aria-label="Buka menu"><span class="material-symbols-outlined">menu</span></button>
            <div class="breadcrumb">Stockify <span>/</span> <?= htmlspecialchars($pageTitle) ?></div>
            <div class="profile"><div class="avatar"><?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div><div><strong><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></strong><small>Administrator</small></div></div>
        </header>
        <main class="page-content">
