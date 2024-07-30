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

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("User not logged in");
}

$user = $_SESSION['username'];
$panelName = $_POST['panelName'];
$description = $_POST['description'];

// Prepare and execute SQL query
$sql = $conn->prepare("INSERT INTO panel_requests (username, panelName, description) VALUES (?, ?, ?)");
if ($sql === false) {
    die("Prepare failed: " . $conn->error);
}

$sql->bind_param("sss", $user, $panelName, $description);

if ($sql->execute()) {
    echo "Request submitted successfully.";
} else {
    echo "Error: " . $sql->error;
}

$sql->close();
$conn->close();
?>
