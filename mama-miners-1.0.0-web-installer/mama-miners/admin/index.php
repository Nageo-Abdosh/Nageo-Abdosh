<?php
require_once __DIR__ . '/../config.php';
session_start();
$conn = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT id,password,role FROM users WHERE username=?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id,$hash,$role);
    if ($stmt->fetch() && password_verify($password,$hash) && $role === 'admin') {
        $_SESSION['admin_id'] = $id;
        $_SESSION['admin_user'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Admin login failed.';
    }
    $stmt->close();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><link href="../assets/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-5"><div class="card"><div class="card-body"><h4>Admin Login</h4><?php if(!empty($error)) echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; ?><form method="post"><div class="mb-3"><input name="username" class="form-control" placeholder="Username" required></div><div class="mb-3"><input name="password" type="password" class="form-control" placeholder="Password" required></div><button class="btn btn-primary w-100">Login</button></form></div></div></div></div></div></body></html>
