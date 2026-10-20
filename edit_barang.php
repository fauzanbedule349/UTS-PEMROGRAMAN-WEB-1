<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: barang.php'); exit; }
$stmt = $pdo->prepare('SELECT * FROM items WHERE id = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();
if (!$item) { http_response_code(404); exit('Barang tidak ditemukan.'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['item_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = $_POST['quantity'] ?? '';
    $condition = $_POST['condition_status'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($name === '') $errors[] = 'Nama barang wajib diisi.';
    if ($category === '') $errors[] = 'Kategori wajib diisi.';
    if ($quantity === '' || filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0) $errors[] = 'Jumlah stok harus berupa angka 0 atau lebih.';
    if (!in_array($condition, ['Good', 'Needs Repair', 'Damaged'], true)) $errors[] = 'Kondisi barang tidak valid.';

    if (!$errors) {
        $update = $pdo->prepare('UPDATE items SET item_name = ?, category = ?, quantity = ?, condition_status = ?, description = ? WHERE id = ?');
        $update->execute([$name, $category, (int)$quantity, $condition, $description, $id]);
        header('Location: barang.php?updated=1');
        exit;
    }
    $item = array_merge($item, ['item_name'=>$name, 'category'=>$category, 'quantity'=>$quantity, 'condition_status'=>$condition, 'description'=>$description]);
}
$pageTitle = 'Edit Barang';
require 'includes/header.php';
?>
<section class="page-heading"><div><p class="eyebrow">FORM INVENTARIS</p><h1>Edit Barang</h1><p>Perbarui informasi barang yang dipilih.</p></div><a href="barang.php" class="btn btn-ghost"><span class="material-symbols-outlined">arrow_back</span>Kembali</a></section>
<section class="panel form-panel">
    <?php if ($errors): ?><div class="alert alert-error"><ul><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" class="form-stack" data-validate>
        <div class="form-grid">
            <div class="form-group"><label for="item_name">Nama Barang *</label><input id="item_name" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required maxlength="150"></div>
            <div class="form-group"><label for="category">Kategori *</label><input id="category" name="category" value="<?= htmlspecialchars($item['category']) ?>" required maxlength="100"></div>
            <div class="form-group"><label for="quantity">Jumlah Stok *</label><input id="quantity" name="quantity" type="number" min="0" value="<?= htmlspecialchars((string)$item['quantity']) ?>" required></div>
            <div class="form-group"><label for="condition_status">Kondisi Barang *</label><select id="condition_status" name="condition_status" required><?php foreach (['Good'=>'Good / Baik','Needs Repair'=>'Needs Repair / Perlu Perbaikan','Damaged'=>'Damaged / Rusak'] as $value=>$label): ?><option value="<?= $value ?>" <?= $item['condition_status'] === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></div>
            <div class="form-group form-span"><label for="description">Deskripsi (Opsional)</label><textarea id="description" name="description" rows="4"><?= htmlspecialchars($item['description'] ?? '') ?></textarea></div>
        </div>
        <div class="form-actions"><a href="barang.php" class="btn btn-ghost">Batal</a><button class="btn btn-primary" type="submit"><span class="material-symbols-outlined">save</span>Simpan Perubahan</button></div>
    </form>
</section>
<?php require 'includes/footer.php'; ?>
