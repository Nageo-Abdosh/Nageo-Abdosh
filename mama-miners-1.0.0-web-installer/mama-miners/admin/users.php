<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: index.php'); exit; }
$conn = db();
$users = $conn->query('SELECT id,username,email,role,status,balance FROM users ORDER BY id DESC');
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Manage Users</title><link href="../assets/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container py-4">
  <h3>Users</h3>
  <div class="table-responsive"><table class="table table-striped"><thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Balance</th><th>Status</th><th>Actions</th></tr></thead><tbody>
    <?php while($u = $users->fetch_assoc()) { ?>
      <tr id="user-<?= $u['id'] ?>">
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td>$<?= number_format($u['balance'],2) ?></td>
        <td class="status"><?= $u['status'] ?></td>
        <td>
          <?php if($u['role'] !== 'admin') { ?>
            <button class="btn btn-sm btn-warning toggle-status" data-id="<?= $u['id'] ?>"><?= $u['status']=='active'?'Ban':'Activate' ?></button>
            <button class="btn btn-sm btn-primary edit-balance" data-id="<?= $u['id'] ?>">Edit Balance</button>
          <?php } ?>
        </td>
      </tr>
    <?php } ?>
  </tbody></table></div>
  <a href="dashboard.php" class="btn btn-light">Back</a>
</div>
<script src="../assets/js/jquery.min.js"></script>
<script>
$('.toggle-status').on('click', function(){
  var btn = $(this);
  var id = btn.data('id');
  $.post('ajax/toggle_user_status.php', {id:id}, function(res){
    var d = JSON.parse(res);
    if (d.success) {
      $('#user-'+id+' .status').text(d.newStatus);
      btn.text(d.newStatus == 'active' ? 'Ban' : 'Activate');
    } else alert('Error: '+d.msg);
  });
});
$('.edit-balance').on('click', function(){
  var id = $(this).data('id');
  var val = prompt('Enter new balance:');
  if (val === null) return;
  $.post('ajax/edit_balance.php', {id:id, balance: val}, function(res){
    var d = JSON.parse(res);
    if (d.success) {
      location.reload();
    } else alert('Error: '+d.msg);
  });
});
</script>
</body></html>
