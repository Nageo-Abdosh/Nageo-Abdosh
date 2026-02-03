<?php
require_once __DIR__ . '/../config.php'; session_start(); if (!isset($_SESSION['admin_id'])) exit; echo json_encode(['ok'=>1]);
?>