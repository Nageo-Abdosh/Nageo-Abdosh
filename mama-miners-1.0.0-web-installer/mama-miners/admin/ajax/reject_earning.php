<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../index.php'); exit; }
$conn = db();
$id = intval($_GET['id'] ?? 0);
$conn->query("UPDATE earnings SET status='rejected' WHERE id=$id");
header('Location: ../earnings.php');
?>