<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "store_admin";
$password = "password1#";
$dbname = "panel_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['username'];
$pass = $_POST['password'];

// Prepare and execute SQL query
$sql = $conn->prepare("SELECT password FROM users WHERE username=?");
if ($sql === false) {
    die("Prepare failed: " . $conn->error);
}

$sql->bind_param("s", $user);

if ($sql->execute()) {
    $result = $sql->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $user;
            header("Location: panel.html");
            exit();
        } else {
            echo "Invalid login credentials";
        }
    } else {
        echo "Invalid login credentials";
    }
} else {
    echo "Error: " . $sql->error;
}

$sql->close();
$conn->close();
?>
