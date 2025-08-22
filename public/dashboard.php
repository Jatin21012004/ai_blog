<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?> 👋</h1>
    <p>You are logged in as <?php echo $_SESSION['role']; ?></p>
    <a href="auth/logout.php">Logout</a>
</body>
</html>
