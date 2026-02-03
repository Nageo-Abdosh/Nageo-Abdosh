<?php
// setup.php - Run this ONCE to create DB tables and a default admin account.
// Usage: place on server and open /setup.php in browser, then delete file.
require_once 'config.php';
$conn = db();

// Create tables
$conn->query("CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE,
  email VARCHAR(120),
  password VARCHAR(255),
  role VARCHAR(20) DEFAULT 'user',
  status VARCHAR(20) DEFAULT 'active',
  balance DECIMAL(12,2) DEFAULT 0.00,
  referral_code VARCHAR(20) UNIQUE,
  referred_by VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB CHARSET=utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS earnings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  amount DECIMAL(12,2),
  type VARCHAR(50),
  status VARCHAR(20) DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB CHARSET=utf8mb4");

echo "Tables created or already exist.<br>";

// Create default admin if not exists
$admin = 'admin';
$pass = 'admin123'; // change after first login
$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param('s', $admin);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    // generate referral code
    $ref = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);
    $ins = $conn->prepare("INSERT INTO users (username, email, password, role, referral_code) VALUES (?,?,?,?,?)");
    $email = 'admin@localhost';
    $ins->bind_param('sssss', $admin, $email, $hash, $role = 'admin', $ref);
    $ins->execute();
    echo "Admin user created: <b>admin</b> / <b>admin123</b> — PLEASE change the password immediately.<br>";
} else {
    echo "Admin user already exists.<br>";
}
echo "Setup complete. Delete setup.php after use.";
?>