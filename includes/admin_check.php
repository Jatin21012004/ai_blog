<?php
require __DIR__ . "/auth_check.php";
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die("Access denied. Admins only.");
}
