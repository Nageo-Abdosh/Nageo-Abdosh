<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
$conn = db();
$rows = $conn->query("SELECT DATE(created_at) d, IFNULL(SUM(amount),0) total FROM earnings WHERE status='approved' GROUP BY d ORDER BY d ASC");
$dates = []; $vals = [];
while ($r = $rows->fetch_assoc()) { $dates[] = $r['d']; $vals[] = $r['total']; }
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Charts</title><link href="../assets/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container py-4"><h3>Analytics</h3><div class="card"><div class="card-body"><canvas id="earningsChart"></canvas></div></div><a href="dashboard.php" class="btn btn-light mt-3">Back</a></div>
<script src="../assets/js/jquery.min.js"></script><script src="../assets/js/chart.min.js"></script>
<script>
const labels = <?= json.dumps($dates) ?>;
const data = <?= json.dumps($vals) ?>;
const ctx = document.getElementById('earningsChart').getContext('2d');
new Chart(ctx, { type: 'line', data: { labels: labels, datasets: [{ label: 'Approved Earnings', data: data, fill: false, tension: 0.3 }] } });
</script>
</body></html>
