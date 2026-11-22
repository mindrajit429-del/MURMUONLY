<?php
require 'config.php';

$sql1 = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin'
)";

$sql2 = "INSERT IGNORE INTO users (username, password, role) VALUES 
('admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe.75r8A/rV/wM.V/sO/qA2C.9s6g/x.G', 'admin')";

$sql3 = "CREATE TABLE IF NOT EXISTS license_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game VARCHAR(50) NOT NULL,
    user_key VARCHAR(100) NOT NULL UNIQUE,
    duration_hours INT NOT NULL,
    max_devices INT DEFAULT 1,
    serial_hwid TEXT DEFAULT NULL,
    activated_at DATETIME DEFAULT NULL,
    expires_at DATETIME DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'inactive'
)";

if (mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2) && mysqli_query($conn, $sql3)) {
    echo "Database Tables Created Successfully! Username: admin | Password: password";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
