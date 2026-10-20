<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/database.php';

$errors = [];
$itemName = $category = $description = '';
$quantity = '';
$condition = 'Good';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = trim($_POST['item_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $condition = $_POST['condition_status'] ?? 'Good';
    $description = trim($_POST['description'] ?? '');

    if ($itemName === '') $errors[] = 'Nama barang wajib diisi.';
    if ($category === '') $errors[] = 'Kategori wajib diisi.';
    if ($quantity === '' || filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0) $errors[] = 'Jumlah stok harus berupa angka 0 atau lebih.';
    if (!in_array($condition, ['Good', 'Needs Repair', 'Damaged'], true)) $errors[] = 'Kondisi barang tidak valid.';

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO items (item_name, category, quantity, condition_status, description) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$itemName, $category, (int)$quantity, $condition, $description]);
        header('Location: dashboard.php?success=1');
        exit;
    }
}

$pageTitle = 'Tambah Barang';
require 'includes/header.php';
?>
<section class="page-heading"><div><p class="eyebrow">FORM INVENTARIS</p><h1>Tambah Barang</h1><p>Masukkan informasi barang baru ke dalam inventaris.</p></div><a href="barang.php" class="btn btn-ghost"><span class="material-symbols-outlined">arrow_back</span>Kembali</a></section>
<section class="panel form-panel">
    <div class="panel-heading"><div><h2>Informasi Barang</h2><p>Kolom bertanda * wajib diisi.</p></div></div>
    <?php if ($errors): ?><div class="alert alert-error"><ul><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" action="tambah_barang.php" class="form-stack" data-validate>
        <div class="form-grid">
            <div class="form-group"><label for="item_name">Nama Barang *</label><input id="item_name" name="item_name" value="<?= htmlspecialchars($itemName) ?>" placeholder="Contoh: Monitor Dell" required maxlength="150"></div>
            <div class="form-group"><label for="category">Kategori *</label><input id="category" name="category" value="<?= htmlspecialchars($category) ?>" placeholder="Contoh: Elektronik" required maxlength="100"></div>
            <div class="form-group"><label for="quantity">Jumlah Stok *</label><input id="quantity" name="quantity" type="number" min="0" value="<?= htmlspecialchars((string)$quantity) ?>" placeholder="Masukkan jumlah" required></div>
            <div class="form-group"><label for="condition_status">Kondisi Barang *</label><select id="condition_status" name="condition_status" required><option value="Good" <?= $condition === 'Good' ? 'selected' : '' ?>>Good / Baik</option><option value="Needs Repair" <?= $condition === 'Needs Repair' ? 'selected' : '' ?>>Needs Repair / Perlu Perbaikan</option><option value="Damaged" <?= $condition === 'Damaged' ? 'selected' : '' ?>>Damaged / Rusak</option></select></div>
            <div class="form-group form-span"><label for="description">Deskripsi (Opsional)</label><textarea id="description" name="description" rows="4" placeholder="Tambahkan keterangan barang..."><?= htmlspecialchars($description) ?></textarea></div>
        </div>
        <div class="form-actions"><a href="barang.php" class="btn btn-ghost">Batal</a><button class="btn btn-primary" type="submit"><span class="material-symbols-outlined">save</span>Simpan Barang</button></div>
    </form>
</section>
<?php require 'includes/footer.php'; ?>
