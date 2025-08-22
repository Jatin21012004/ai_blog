<?php
$host = "localhost";
$user = "root"; // default XAMPP MySQL user
$pass = "";     // default password is empty
$dbname = "ai_blog";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
