<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

// Only accept POST requests
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('properties.php');
}

$pdo = getDB();

// Get & sanitize inputs
$property_id = (int)($_POST['property_id'] ?? 0);
$name        = trim($_POST['inq_name']    ?? '');
$email       = trim($_POST['inq_email']   ?? '');
$phone       = trim($_POST['inq_phone']   ?? '');
$message     = trim($_POST['inq_message'] ?? '');
$user_id     = isLoggedIn() ? (int)$_SESSION['user_id'] : null;

// Validate property exists
if($property_id <= 0) {
    redirect('properties.php');
}

$stmt = $pdo->prepare("
    SELECT property_id, title
    FROM properties
    WHERE property_id = ?
    AND status = 'active'
    LIMIT 1
");
$stmt->execute([$property_id]);
$property = $stmt->fetch();

if(!$property) {
    redirect('properties.php');
}

$errors = [];

// Validate inputs
if(empty($name) || strlen($name) < 2) {
    $errors[] = 'Please enter your full name.';
}
if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if(!empty($phone) && !preg_match('/^\d{10}$/', $phone)) {
    $errors[] = 'Please enter a valid 10-digit phone number.';
}
if(empty($message) || strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters.';
}

// If errors — redirect back with error
if(!empty($errors)) {
    setFlash('inquiry_error', implode(' ', $errors));
    redirect('property-details.php?id=' . $property_id);
}

// Insert inquiry
$stmt = $pdo->prepare("
    INSERT INTO inquiries
        (property_id, user_id, name, email, phone, message)
    VALUES
        (?, ?, ?, ?, ?, ?)
");
$stmt->execute([
    $property_id,
    $user_id,
    $name,
    $email,
    !empty($phone) ? $phone : null,
    $message
]);

// Success — redirect back
setFlash(
    'inquiry_success',
    'Your inquiry has been sent successfully! The owner will contact you soon.'
);
redirect('property-details.php?id=' . $property_id);