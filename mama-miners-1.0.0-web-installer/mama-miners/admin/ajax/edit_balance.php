<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) exit(json_encode(['success'=>false,'msg'=>'Not authorized']));
$conn = db();
$id = intval($_POST['id'] ?? 0);
$balance = floatval($_POST['balance'] ?? 0);
if (!$id) exit(json_encode(['success'=>false,'msg'=>'Missing id']));
$conn->query("UPDATE users SET balance = $balance WHERE id=$id AND role!='admin'");
echo json_encode(['success'=>true,'newBalance'=>$balance]);
?>