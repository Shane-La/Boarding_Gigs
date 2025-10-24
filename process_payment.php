<?php
include 'config.php';

// Simple server-side validation
$gig_id = isset($_POST['gig_id']) ? intval($_POST['gig_id']) : 0;
$name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
$card_number = isset($_POST['card_number']) ? $conn->real_escape_string($_POST['card_number']) : '';
$expiry_date = isset($_POST['expiry_date']) ? $conn->real_escape_string($_POST['expiry_date']) : '';
$cvv = isset($_POST['cvv']) ? $conn->real_escape_string($_POST['cvv']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0.0;

if ($gig_id <= 0 || empty($name) || empty($card_number)) {
  die('Missing required fields.');
}

// Store payment (DEMO ONLY - do not store real card data in production)
$stmt = $conn->prepare("INSERT INTO payments (gig_id, name, card_number, expiry_date, cvv, amount) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param('issssd', $gig_id, $name, $card_number, $expiry_date, $cvv, $amount);
$stmt->execute();

// Update gig status
$upd = $conn->prepare("UPDATE gigs SET status='Active' WHERE id=?");
$upd->bind_param('i', $gig_id);
$upd->execute();

// Redirect to success
header('Location: success.php');
exit;
?>
