<?php
require_once __DIR__ . '/../config.php';
session_start();
$conn = db();

function generateCode($len=6){
    return substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'),0,$len);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $invite = trim($_POST['invitecode'] ?? null);

    if (!$username || !$email || !$password) {
        $error = 'Please fill all fields.';
    } else {
        // check username
        $stmt = $conn->prepare('SELECT id FROM users WHERE username=? OR email=?');
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Username or email already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ref = generateCode();
            $ins = $conn->prepare('INSERT INTO users (username,email,password,referral_code,referred_by) VALUES (?,?,?,?,?)');
            $ins->bind_param('sssss', $username, $email, $hash, $ref, $invite);
            if ($ins->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $error = 'Registration failed.';
            }
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign Up</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Create Account</h4>
          <?php if(!empty($error)) echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; ?>
          <form method="post">
            <div class="mb-2"><input name="username" class="form-control" placeholder="Username" required></div>
            <div class="mb-2"><input name="email" type="email" class="form-control" placeholder="Email" required></div>
            <div class="mb-2"><input name="password" type="password" class="form-control" placeholder="Password" required></div>
            <div class="mb-2"><input name="invitecode" class="form-control" placeholder="Invite Code (optional)"></div>
            <button class="btn btn-success w-100">Sign Up</button>
          </form>
          <hr>
          <p class="text-center"><a href="login.php">Already have an account? Login</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
