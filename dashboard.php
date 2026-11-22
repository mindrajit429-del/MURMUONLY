<?php
require 'config.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// নতুন কী জেনারেট লজিক
if (isset($_POST['generate_key'])) {
    $game = $_POST['game'];
    $hours = (int)$_POST['duration'];
    $devices = (int)$_POST['devices'];
    $prefix = !empty($_POST['prefix']) ? trim($_POST['prefix']) : "VIP";
    $random_str = strtoupper(bin2hex(random_bytes(4)));
    $new_key = $prefix . "-" . $random_str;

    $stmt = $conn->prepare("INSERT INTO license_keys (game, user_key, duration_hours, max_devices) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $game, $new_key, $hours, $devices);
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}

// কী ডিলিট লজিক
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    $conn->query("DELETE FROM license_keys WHERE id = $id");
    header("Location: dashboard.php");
    exit;
}

$total_keys = $conn->query("SELECT COUNT(*) as c FROM license_keys")->fetch_assoc()['c'];
$active_keys = $conn->query("SELECT COUNT(*) as c FROM license_keys WHERE status='active'")->fetch_assoc()['c'];
$keys = $conn->query("SELECT * FROM license_keys ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen p-4">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center bg-gray-900/80 border border-cyan-500/30 p-4 rounded-xl">
            <h1 class="text-xl font-bold text-cyan-400">VIP ADMIN PANEL</h1>
            <a href="logout.php" class="bg-red-500/20 text-red-400 border border-red-500/40 px-3 py-1 rounded-lg text-sm">Logout</a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-900/70 border border-gray-800 p-4 rounded-xl">
                <p class="text-xs text-gray-400">Total Keys</p>
                <p class="text-2xl font-bold text-white"><?= $total_keys ?></p>
            </div>
            <div class="bg-gray-900/70 border border-gray-800 p-4 rounded-xl">
                <p class="text-xs text-gray-400">Active Keys</p>
                <p class="text-2xl font-bold text-green-400"><?= $active_keys ?></p>
            </div>
        </div>

        <!-- Generate Key Form -->
        <div class="bg-gray-900/70 border border-cyan-500/30 p-5 rounded-xl">
            <h2 class="text-lg font-semibold text-cyan-300 mb-4">Generate New Key</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="game" value="PUBG" required placeholder="Game Name" class="p-3 rounded-lg bg-gray-800 border border-gray-700">
                <input type="text" name="prefix" placeholder="Key Prefix (e.g. VENOM)" class="p-3 rounded-lg bg-gray-800 border border-gray-700">
                <select name="duration" class="p-3 rounded-lg bg-gray-800 border border-gray-700">
                    <option value="2">2 Hours</option>
                    <option value="5">5 Hours</option>
                    <option value="24">1 Day (24 Hours)</option>
                    <option value="168">7 Days</option>
                    <option value="720">30 Days</option>
                </select>
                <input type="number" name="devices" value="1" min="1" placeholder="Max Devices" class="p-3 rounded-lg bg-gray-800 border border-gray-700">
                <button type="submit" name="generate_key" class="md:col-span-2 bg-cyan-500 hover:bg-cyan-600 text-gray-950 font-bold py-3 rounded-lg">GENERATE KEY</button>
            </form>
        </div>

        <!-- Keys List -->
        <div class="bg-gray-900/70 border border-gray-800 p-4 rounded-xl overflow-x-auto">
            <h2 class="text-lg font-semibold mb-3">Generated Keys</h2>
            <table class="w-full text-left text-sm">
                <thead class="text-gray-400 border-b border-gray-800">
                    <tr>
                        <th class="py-2">Key</th>
                        <th>Game</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <?php while($row = $keys->fetch_assoc()): ?>
                    <tr>
                        <td class="py-3 font-mono text-cyan-400"><?= $row['user_key'] ?></td>
                        <td><?= $row['game'] ?></td>
                        <td><?= $row['duration_hours'] ?>h</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs <?= $row['status']=='active' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' ?>">
                                <?= strtoupper($row['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="?del=<?= $row['id'] ?>" class="text-red-400 hover:underline">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
