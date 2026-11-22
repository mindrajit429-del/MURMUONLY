<?php
session_start();

$host = "mysql-2787dd40-mindrajit429-0740.c.aivencloud.com";
$port = 25137;
$user = "avnadmin";
$pass = "AVNS_DG2R5gTVrGnPRELg6Fy";
$dbname = "defaultdb";

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
if (!mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// তোমার অ্যাপের সাথে মিল রেখে Salt সেট করো
define("MOD_PANEL_SALT", "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E");
?>
