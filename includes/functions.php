<?php
function e($str){ return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8'); }

function slugify($text) {
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  $text = trim($text, '-');
  $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
  $text = strtolower($text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  return $text ?: 'post';
}

function csrf_token() {
  if (session_status() === PHP_SESSION_NONE) session_start();
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf'];
}
function csrf_check($token) {
  if (session_status() === PHP_SESSION_NONE) session_start();
  return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}
