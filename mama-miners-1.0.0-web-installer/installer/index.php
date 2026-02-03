<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['db'] = $_POST;
    header("Location: install.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Mama Miners Installer</title>
<style>
body { font-family: Arial; background:#111; color:#eee; }
.container { width:400px; margin:80px auto; }
input,button { width:100%; padding:10px; margin:8px 0; }
</style>
</head>
<body>
<div class="container">
<h2>Mama Miners Setup</h2>
<form method="post">
<input name="host" placeholder="DB Host" required>
<input name="name" placeholder="DB Name" required>
<input name="user" placeholder="DB User" required>
<input name="pass" placeholder="DB Password" type="password">
<button>Install</button>
</form>
</div>
</body>
</html>
