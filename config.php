<?php
session_start();

$host = "sql202.infinityfree.com";
$user = "if0_43503240";
$pass = "HCaB63HWScMTY";
$dbname = "if0_43503240_murmu";


$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed!");
}

// তোমার অ্যাপের সাথে মিল রেখে Salt সেট করো
define("MOD_PANEL_SALT", "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E");
?>


