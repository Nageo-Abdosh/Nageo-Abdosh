<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
$conn = db();
$uid = intval($_SESSION['user_id']);
$user = $conn->query("SELECT username,balance,referral_code FROM users WHERE id=$uid")->fetch_assoc();
$earn_total = $conn->query("SELECT IFNULL(SUM(amount),0) t FROM earnings WHERE user_id=$uid AND status='approved'")->fetch_assoc()['t'];
$pending = $conn->query("SELECT IFNULL(SUM(amount),0) t FROM earnings WHERE user_id=$uid AND status='pending'")->fetch_assoc()['t'];
$refs = $conn->query("SELECT username,created_at FROM users WHERE referred_by='".$user['referral_code']."'");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container-fluid"><a class="navbar-brand" href="#"><?=htmlspecialchars(SITE_NAME)?></a><div class="ms-auto"><a class="btn btn-outline-light" href="../auth/logout.php">Logout</a></div></div></nav>
<div class="container py-4">
  <div class="row">
    <div class="col-md-8">
      <h3>Welcome, <?=htmlspecialchars($user['username'])?></h3>
      <div class="row g-3">
        <div class="col-sm-6">
          <div class="card"><div class="card-body"><h5>Balance</h5><p class="display-6">$<?=number_format($user['balance'],2)?></p></div></div>
        </div>
        <div class="col-sm-6">
          <div class="card"><div class="card-body"><h5>Total Earned</h5><p>$<?=number_format($earn_total,2)?></p><small>Pending: $<?=number_format($pending,2)?></small></div></div>
        </div>
      </div>
      <hr>
      <h5>Your Referrals</h5>
      <ul class="list-group">
        <?php while($r = $refs->fetch_assoc()) { echo '<li class="list-group-item">'.htmlspecialchars($r['username']).' <small class="text-muted">('.$r['created_at'].')</small></li>'; } ?>
      </ul>
    </div>
    <div class="col-md-4">
      <div class="card"><div class="card-body"><h6>Your Referral Code</h6><p class="h4"><?=htmlspecialchars($user['referral_code'])?></p><p>Share this code with friends.</p></div></div>
    </div>
  </div>
</div>
</body>
</html>
