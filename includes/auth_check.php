<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) {
  header("Location: /ai_blog/auth/login.php");
  exit;
}
