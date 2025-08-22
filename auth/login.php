<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-xl font-bold mb-4">Login</h2>
        <?php if (!empty($error)) echo "<p class='text-red-500'>$error</p>"; ?>
        <?php if (!empty($_SESSION['success'])) { echo "<p class='text-green-500'>".$_SESSION['success']."</p>"; unset($_SESSION['success']); } ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded">
            <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Login</button>
        </form>
        <p class="mt-3 text-sm">Don’t have an account? <a href="signup.php" class="text-blue-500">Signup</a></p>
    </div>
</body>
</html>
