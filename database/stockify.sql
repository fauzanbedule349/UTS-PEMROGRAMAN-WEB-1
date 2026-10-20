CREATE DATABASE IF NOT EXISTS stockify
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE stockify;

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(150) NOT NULL,
    category VARCHAR(100) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 0,
    condition_status ENUM('Good', 'Needs Repair', 'Damaged') NOT NULL DEFAULT 'Good',
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Akun demo: username admin, password password
-- Hash di bawah dibuat untuk password demo "password".
INSERT INTO admins (username, password)
SELECT 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.'
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE username = 'admin');

INSERT INTO items (item_name, category, quantity, condition_status, description)
SELECT 'Monitor Dell', 'Elektronik', 12, 'Good', 'Monitor untuk komputer kantor'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE item_name = 'Monitor Dell');

INSERT INTO items (item_name, category, quantity, condition_status, description)
SELECT 'Keyboard Mechanical', 'Aksesori Komputer', 8, 'Good', 'Keyboard untuk kebutuhan kerja'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE item_name = 'Keyboard Mechanical');

INSERT INTO items (item_name, category, quantity, condition_status, description)
SELECT 'Kursi Kantor', 'Furniture', 3, 'Needs Repair', 'Beberapa kursi perlu diperiksa'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE item_name = 'Kursi Kantor');
