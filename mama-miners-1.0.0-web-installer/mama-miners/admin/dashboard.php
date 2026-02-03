<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}
$conn = db();
$totalUsers = $conn->query('SELECT COUNT(*) t FROM users')->fetch_assoc()['t'];
$approved = $conn->query("SELECT IFNULL(SUM(amount),0) t FROM earnings WHERE status='approved'")->fetch_assoc()['t'];
$pending = $conn->query("SELECT IFNULL(SUM(amount),0) t FROM earnings WHERE status='pending'")->fetch_assoc()['t'];
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Dashboard</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container-fluid"><a class="navbar-brand" href="#">Admin</a><div class="ms-auto"><a class="btn btn-outline-light" href="../auth/logout.php">Logout</a></div></div></nav>
<div class="container py-4">
  <div class="row g-3">
    <div class="col-sm-4"><div class="card"><div class="card-body"><h5>Total Users</h5><p class="h3"><?= $totalUsers ?></p></div></div></div>
    <div class="col-sm-4"><div class="card"><div class="card-body"><h5>Approved Earnings</h5><p class="h3">$<?=number_format($approved,2)?></p></div></div></div>
    <div class="col-sm-4"><div class="card"><div class="card-body"><h5>Pending Earnings</h5><p class="h3">$<?=number_format($pending,2)?></p></div></div></div>
  </div>
  <hr>
  <div class="mb-3"><a class="btn btn-primary" href="users.php">Manage Users</a> <a class="btn btn-success" href="earnings.php">Earnings</a> <a class="btn btn-info" href="charts.php">Charts</a> <a class="btn btn-secondary" href="referral_tree.php">Referral Tree</a></div>
</div>
</body>
</html>
