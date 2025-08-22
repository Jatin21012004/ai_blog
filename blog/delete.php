<?php
require __DIR__ . "/../includes/auth_check.php";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/functions.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check($_POST['csrf'] ?? '')) {
  http_response_code(400); die("Bad request");
}
$id = (int)($_POST['id'] ?? 0);

// verify ownership/admin
$stmt = $conn->prepare("SELECT user_id FROM blogs WHERE id=?");
$stmt->bind_param("i",$id); $stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { http_response_code(404); die("Not found"); }

if ($_SESSION['user_id'] !== (int)$row['user_id'] && ($_SESSION['role']??'')!=='admin') {
  http_response_code(403); die("Forbidden");
}

$del = $conn->prepare("DELETE FROM blogs WHERE id=?");
$del->bind_param("i",$id);
$del->execute();

header("Location: /ai_blog/index.php");
exit;
