<?php
require 'config.php';
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "ভুল ইউজারনেম বা পাসওয়ার্ড!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-sm bg-gray-900/70 backdrop-blur-md border border-cyan-500/30 rounded-2xl p-6 shadow-2xl">
        <h2 class="text-2xl font-bold text-center text-cyan-400 mb-6">PRIVATE VIP PANEL</h2>
        <?php if($error): ?>
            <p class="bg-red-500/20 border border-red-500 text-red-300 text-sm p-2 rounded mb-4 text-center"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="text-xs text-gray-400">Username</label>
                <input type="text" name="username" required class="w-full mt-1 p-3 rounded-lg bg-gray-800 border border-gray-700 focus:border-cyan-400 outline-none">
            </div>
            <div>
                <label class="text-xs text-gray-400">Password</label>
                <input type="password" name="password" required class="w-full mt-1 p-3 rounded-lg bg-gray-800 border border-gray-700 focus:border-cyan-400 outline-none">
            </div>
            <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-600 text-gray-950 font-bold py-3 rounded-lg transition">LOGIN</button>
        </form>
    </div>
</body>
</html>
