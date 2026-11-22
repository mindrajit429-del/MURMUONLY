CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin'
);

-- ডিফল্ট অ্যাডমিন অ্যাকাউন্ট (Username: admin | Password: admin123)
INSERT INTO users (username, password, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

CREATE TABLE license_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game VARCHAR(50) NOT NULL,
    user_key VARCHAR(100) NOT NULL UNIQUE,
    duration_hours INT NOT NULL,
    max_devices INT DEFAULT 1,
    serial_hwid TEXT DEFAULT NULL,
    activated_at DATETIME DEFAULT NULL,
    expires_at DATETIME DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'inactive'
);
