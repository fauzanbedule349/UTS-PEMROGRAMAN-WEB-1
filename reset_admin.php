
<?php
require_once __DIR__ . '/config/database.php';

$username = 'admin';
$newPassword = 'password';

$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "UPDATE admins SET password = :password
     WHERE username = :username"
);

$stmt->execute([
    'password' => $newHash,
    'username' => $username
]);

if ($stmt->rowCount() > 0) {
    echo "Password admin berhasil diperbarui!";
} else {
    echo "Tidak ada data admin yang diperbarui. Periksa username admin.";
}
