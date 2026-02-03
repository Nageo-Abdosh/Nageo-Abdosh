<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../index.php'); exit; }
$conn = db();
$id = intval($_GET['id'] ?? 0);
$e = $conn->query("SELECT user_id, amount, status FROM earnings WHERE id=$id")->fetch_assoc();
if ($e && $e['status'] === 'pending') {
    $conn->query("UPDATE earnings SET status='approved' WHERE id=$id");
    $conn->query("UPDATE users SET balance = balance + {$e['amount']} WHERE id={$e['user_id']}");
}
header('Location: ../earnings.php');
?>