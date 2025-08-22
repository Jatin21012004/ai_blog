<?php
session_start();
include("../config/db.php");

// Handle signup form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Signup successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-xl font-bold mb-4">Signup</h2>
        <?php if (!empty($error)) echo "<p class='text-red-500'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required class="w-full mb-3 p-2 border rounded">
            <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded">
            <input type="password" name="password" placeholder="Password" required class="w-full mb-3 p-2 border rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Signup</button>
        </form>
        <p class="mt-3 text-sm">Already have an account? <a href="login.php" class="text-blue-500">Login</a></p>
    </div>
</body>
</html>
