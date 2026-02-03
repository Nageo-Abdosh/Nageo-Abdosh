<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
$conn = db();
$earnings = $conn->query("SELECT e.id, u.username, e.amount, e.type, e.status FROM earnings e JOIN users u ON u.id=e.user_id ORDER BY e.id DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Earnings</title><link href="../assets/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container py-4"><h3>Earnings</h3><div class="table-responsive"><table class="table table-striped"><thead><tr><th>User</th><th>Amount</th><th>Type</th><th>Status</th><th>Action</th></tr></thead><tbody>
<?php while($e = $earnings->fetch_assoc()) { ?>
<tr>
  <td><?= htmlspecialchars($e['username']) ?></td>
  <td>$<?= number_format($e['amount'],2) ?></td>
  <td><?= htmlspecialchars($e['type']) ?></td>
  <td><?= $e['status'] ?></td>
  <td><?php if($e['status']=='pending') { ?>
    <button class="btn btn-sm btn-success" onclick="location.href='ajax/approve_earning.php?id=<?=$e['id']?>'">Approve</button>
    <button class="btn btn-sm btn-danger" onclick="location.href='ajax/reject_earning.php?id=<?=$e['id']?>'">Reject</button>
  <?php } ?></td>
</tr>
<?php } ?>
</tbody></table></div><a href="dashboard.php" class="btn btn-light">Back</a></div>
</body></html>
