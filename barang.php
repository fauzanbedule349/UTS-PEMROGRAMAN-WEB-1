<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/database.php';

$search = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');
$condition = trim($_GET['condition'] ?? '');

$sql = 'SELECT * FROM items WHERE 1=1';
$params = [];
if ($search !== '') {
    $sql .= ' AND (item_name LIKE ? OR category LIKE ? OR description LIKE ?)';
    $like = '%' . $search . '%';
    array_push($params, $like, $like, $like);
}
if ($category !== '') { $sql .= ' AND category = ?'; $params[] = $category; }
if ($condition !== '') { $sql .= ' AND condition_status = ?'; $params[] = $condition; }
$sql .= ' ORDER BY created_at DESC, id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
$categories = $pdo->query("SELECT DISTINCT category FROM items WHERE category <> '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Inventory';
require 'includes/header.php';
?>
<section class="page-heading">
    <div><p class="eyebrow">PENGELOLAAN BARANG</p><h1>Inventory Management</h1><p>Kelola, cari, dan perbarui data inventaris Anda.</p></div>
    <a href="tambah_barang.php" class="btn btn-primary"><span class="material-symbols-outlined">add</span>Tambah Barang</a>
</section>
<?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">Data barang berhasil dihapus.</div><?php endif; ?>
<?php if (isset($_GET['updated'])): ?><div class="alert alert-success">Data barang berhasil diperbarui.</div><?php endif; ?>
<section class="panel">
    <form method="get" class="filters">
        <div class="search-field"><span class="material-symbols-outlined">search</span><input type="search" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama atau deskripsi barang..."></div>
        <select name="category" aria-label="Filter kategori"><option value="">Semua Kategori</option><?php foreach ($categories as $cat): ?><option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option><?php endforeach; ?></select>
        <select name="condition" aria-label="Filter kondisi"><option value="">Semua Kondisi</option><?php foreach (['Good', 'Needs Repair', 'Damaged'] as $c): ?><option value="<?= $c ?>" <?= $condition === $c ? 'selected' : '' ?>><?= $c ?></option><?php endforeach; ?></select>
        <button class="btn btn-secondary" type="submit">Filter</button>
        <a class="btn btn-ghost" href="barang.php">Reset</a>
    </form>
    <?php if (!$items): ?>
        <div class="empty-state"><span class="material-symbols-outlined">inventory</span><h3>Barang tidak ditemukan</h3><p>Coba ubah kata kunci atau tambahkan data barang baru.</p><a href="tambah_barang.php" class="btn btn-primary">Tambah Barang</a></div>
    <?php else: ?>
    <div class="table-wrap"><table><thead><tr><th>Nama Barang</th><th>Kategori</th><th>Jumlah Stok</th><th>Kondisi</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($items as $item): ?>
    <tr>
        <td><strong><?= htmlspecialchars($item['item_name']) ?></strong><small class="table-sub"><?= htmlspecialchars($item['description'] ?: 'Tidak ada deskripsi') ?></small></td>
        <td><?= htmlspecialchars($item['category']) ?></td><td><?= number_format((int)$item['quantity']) ?></td>
        <td><span class="status status-<?= strtolower(str_replace(' ', '-', $item['condition_status'])) ?>"><?= htmlspecialchars($item['condition_status']) ?></span></td>
        <td><?= date('d M Y', strtotime($item['created_at'])) ?></td>
        <td><div class="actions"><a class="icon-button" href="edit_barang.php?id=<?= (int)$item['id'] ?>" title="Edit"><span class="material-symbols-outlined">edit</span></a><form method="post" action="hapus_barang.php" data-confirm="Yakin ingin menghapus barang ini?"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><button class="icon-button danger" type="submit" title="Hapus"><span class="material-symbols-outlined">delete</span></button></form></div></td>
    </tr>
    <?php endforeach; ?>
    </tbody></table></div>
    <div class="table-bottom">Menampilkan <?= count($items) ?> data barang.</div>
    <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
