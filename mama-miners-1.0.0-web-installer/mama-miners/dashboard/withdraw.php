<?php
require_once __DIR__ . '/../config.php'; session_start(); if(!isset($_SESSION['user_id'])){header('Location: ../auth/login.php');exit;} echo 'Withdraw request placeholder.'; ?>