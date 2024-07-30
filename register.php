<?php
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
$pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Check if username already exists
$check_sql = $conn->prepare("SELECT id FROM users WHERE username = ?");
if ($check_sql === false) {
    die("Prepare failed: " . $conn->error);
}
$check_sql->bind_param("s", $user);
$check_sql->execute();
$check_result = $check_sql->get_result();

if ($check_result->num_rows > 0) {
    echo "Username already exists. Please choose another.";
} else {
    // Prepare and execute SQL query
    $sql = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($sql === false) {
        die("Prepare failed: " . $conn->error);
    }
    $sql->bind_param("ss", $user, $pass);

    if ($sql->execute()) {
        echo "Registration successful. <a href='login.html'>Login</a>";
    } else {
        echo "Error: " . $sql->error;
    }
    $sql->close();
}

$check_sql->close();
$conn->close();
?>
