<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "register"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pass = trim($_POST['password']);
    $cpass = trim($_POST['confirm_password']);

    if ($pass !== $cpass) {
        echo "<script>alert('Passwords do not match!'); window.location.href='register.html';</script>";
        exit();
    }

    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    
    $check_email = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already registered! Please login.'); window.location.href='index.html';</script>";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    
    $insert = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $user, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! You can now login.'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Error: Could not register. " . addslashes($stmt->error) . "'); window.location.href='register.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
