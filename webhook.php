<?php
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

// Paystack webhook secret key
$paystack_secret_key = 'sk_test_725b97d7354238f949e52dca841ee87d568ab3ce'; // Replace with your Paystack secret key

// Retrieve webhook payload
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// Verify Paystack webhook signature
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'];
$expected_signature = hash_hmac('sha512', $payload, $paystack_secret_key);

if ($signature !== $expected_signature) {
    die('Invalid signature');
}

// Check the event type
if ($data['event'] === 'charge.success') {
    $email = $data['data']['customer']['email'];
    
    // Send email notification
    $to = $email;
    $subject = 'Payment Confirmation';
    $message = 'Thank you for your purchase! Your transaction was successful.';
    $headers = 'From: no-reply@example.com';

    mail($to, $subject, $message, $headers);
}

$conn->close();
?>
