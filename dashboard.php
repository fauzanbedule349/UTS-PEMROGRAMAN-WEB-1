<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/database.php';

$totalItems = (int)$pdo->query('SELECT COUNT(*) FROM items')->fetchColumn();
$totalCategories = (int)$pdo->query("SELECT COUNT(DISTINCT category) FROM items WHERE category <> ''")->fetchColumn();
$totalStock = (int)$pdo->query('SELECT COALESCE(SUM(quantity), 0) FROM items')->fetchColumn();
$needsRepair = (int)$pdo->query("SELECT COUNT(*) FROM items WHERE condition_status = 'Needs Repair'")->fetchColumn();
$recentItems = $pdo->query('SELECT * FROM items ORDER BY created_at DESC, id DESC LIMIT 5')->fetchAll();

$pageTitle = 'Dashboard';
require 'includes/header.php';
?>
<section class="page-heading">
    <div><p class="eyebrow">RINGKASAN INVENTARIS</p><h1>Dashboard</h1><p>Selamat datang, <?= htmlspecialchars($_SESSION['admin_username']) ?>. Berikut ringkasan inventaris Anda.</p></div>
    <a class="btn btn-primary" href="tambah_barang.php"><span class="material-symbols-outlined">add</span>Tambah Barang</a>
</section>
<?php if (isset($_GET['success'])): ?><div class="alert alert-success">Data barang berhasil disimpan.</div><?php endif; ?>
<div class="stats-grid">
    <article class="stat-card"><div class="stat-top"><span>Total Jenis Barang</span><span class="stat-icon blue material-symbols-outlined">inventory_2</span></div><strong><?= number_format($totalItems) ?></strong><small>Barang terdaftar</small></article>
    <article class="stat-card"><div class="stat-top"><span>Total Kategori</span><span class="stat-icon green material-symbols-outlined">category</span></div><strong><?= number_format($totalCategories) ?></strong><small>Kategori barang</small></article>
    <article class="stat-card"><div class="stat-top"><span>Total Stok</span><span class="stat-icon purple material-symbols-outlined">stacks</span></div><strong><?= number_format($totalStock) ?></strong><small>Unit barang tersedia</small></article>
    <article class="stat-card"><div class="stat-top"><span>Perlu Perbaikan</span><span class="stat-icon orange material-symbols-outlined">build</span></div><strong><?= number_format($needsRepair) ?></strong><small>Barang perlu diperiksa</small></article>
</div>
<section class="panel">
    <div class="panel-heading"><div><h2>Barang Terbaru</h2><p>Data inventaris yang terakhir ditambahkan.</p></div><a class="text-link" href="barang.php">Lihat semua <span class="material-symbols-outlined">arrow_forward</span></a></div>
    <?php if (!$recentItems): ?>
        <div class="empty-state"><span class="material-symbols-outlined">inventory</span><h3>Belum ada barang</h3><p>Tambahkan barang pertama untuk mulai mengelola inventaris.</p><a href="tambah_barang.php" class="btn btn-primary">Tambah Barang</a></div>
    <?php else: ?>
    <div class="table-wrap"><table><thead><tr><th>Nama Barang</th><th>Kategori</th><th>Stok</th><th>Kondisi</th><th>Tanggal Ditambahkan</th></tr></thead><tbody>
    <?php foreach ($recentItems as $item): ?>
    <tr><td><strong><?= htmlspecialchars($item['item_name']) ?></strong><small class="table-sub"><?= htmlspecialchars($item['description'] ?: 'Tidak ada deskripsi') ?></small></td><td><?= htmlspecialchars($item['category']) ?></td><td><?= number_format((int)$item['quantity']) ?></td><td><span class="status status-<?= strtolower(str_replace(' ', '-', $item['condition_status'])) ?>"><?= htmlspecialchars($item['condition_status']) ?></span></td><td><?= date('d M Y', strtotime($item['created_at'])) ?></td></tr>
    <?php endforeach; ?>
    </tbody></table></div>
    <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
