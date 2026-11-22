<?php
header('Content-Type: application/json');
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => false, "reason" => "Invalid Request"]);
    exit;
}

$game = $_POST['game'] ?? '';
$user_key = $_POST['user_key'] ?? '';
$serial = $_POST['serial'] ?? '';

if (empty($game) || empty($user_key) || empty($serial)) {
    echo json_encode(["status" => false, "reason" => "Missing Parameters"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM license_keys WHERE user_key = ? AND game = ?");
$stmt->bind_param("ss", $user_key, $game);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $now = date("Y-m-d H:i:s");

    // প্রথমবার অ্যাক্টিভেশন হলে HWID লক এবং সময় শুরু হবে
    if ($row['status'] === 'inactive') {
        $hours = (int)$row['duration_hours'];
        $expires = date("Y-m-d H:i:s", strtotime("+$hours hours"));
        
        $upd = $conn->prepare("UPDATE license_keys SET serial_hwid = ?, activated_at = ?, expires_at = ?, status = 'active' WHERE id = ?");
        $upd->bind_param("sssi", $serial, $now, $expires, $row['id']);
        $upd->execute();

        $token = md5($game . $user_key . $serial . MOD_PANEL_SALT);
        echo json_encode(["status" => true, "data" => ["token" => $token, "rng" => time(), "EXP" => $expires]]);
        exit;
    }

    // আগে থেকেই অ্যাক্টিভ থাকলে মেয়াদ ও HWID চেক
    if ($row['status'] === 'active') {
        if (strtotime($now) > strtotime($row['expires_at'])) {
            echo json_encode(["status" => false, "reason" => "Key Expired"]);
            exit;
        }
        if ($row['serial_hwid'] !== $serial) {
            echo json_encode(["status" => false, "reason" => "HWID Mismatch! Registered to another device."]);
            exit;
        }

        $token = md5($game . $user_key . $serial . MOD_PANEL_SALT);
        echo json_encode(["status" => true, "data" => ["token" => $token, "rng" => time(), "EXP" => $row['expires_at']]]);
        exit;
    }
}

echo json_encode(["status" => false, "reason" => "Invalid Key"]);
?>
