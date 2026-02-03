<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
$conn = db();
function showTree($code, $level=0) {
    global $conn;
    $res = $conn->query("SELECT username, referral_code FROM users WHERE referred_by='".$code."'");
    while ($r = $res->fetch_assoc()) {
        echo str_repeat('&nbsp;&nbsp;&nbsp;', $level) . '↳ ' . htmlspecialchars($r['username']) . '<br>';
        showTree($r['referral_code'], $level+1);
    }
}
$roots = $conn->query("SELECT username, referral_code FROM users WHERE referred_by IS NULL");
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Referral Tree</title><link href="../assets/css/bootstrap.min.css" rel="stylesheet"></head>
<body><div class="container py-4"><h3>Referral Tree</h3><?php while($r=$roots->fetch_assoc()) { echo '<b>'.htmlspecialchars($r['username']).'</b><br>'; showTree($r['referral_code']); echo '<hr>'; } ?><a href="dashboard.php" class="btn btn-light">Back</a></div></body></html>
