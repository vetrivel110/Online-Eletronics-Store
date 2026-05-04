<?php
session_start();
require_once __DIR__ . '/config.php';

$conn = db_connect();

// Process the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        // Set session variables
        $_SESSION['username'] = $username;
        
        // Redirect to the home page
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid username or password";
    }

    $stmt->close();
}

$conn->close();
?>
