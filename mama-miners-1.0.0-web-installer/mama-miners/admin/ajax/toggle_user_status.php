<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) exit(json_encode(['success'=>false,'msg'=>'Not authorized']));
$conn = db();
$id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
if (!$id) exit(json_encode(['success'=>false,'msg'=>'Missing id']));
$user = $conn->query("SELECT status, role FROM users WHERE id=$id")->fetch_assoc();
if (!$user) exit(json_encode(['success'=>false,'msg'=>'User not found']));
if ($user['role'] === 'admin') exit(json_encode(['success'=>false,'msg'=>'Cannot modify admin']));
$new = $user['status'] === 'active' ? 'banned' : 'active';
$conn->query("UPDATE users SET status='$new' WHERE id=$id");
echo json_encode(['success'=>true,'newStatus'=>$new]);
?>