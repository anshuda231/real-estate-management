<?php
require_once 'includes/auth.php';
require_once 'config/db.php';

requireLogin();

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);

if($id <= 0) {
    redirect('my-properties.php');
}

// Fetch property
$stmt = $pdo->prepare("
    SELECT * FROM properties
    WHERE property_id = ?
    LIMIT 1
");
$stmt->execute([$id]);
$property = $stmt->fetch();

if(!$property) {
    setFlash('error', 'Property not found.');
    redirect('my-properties.php');
}

// Check permission
if(!canEditProperty($property)) {
    setFlash('error', 'You do not have permission to delete this property.');
    redirect('my-properties.php');
}

// Delete image file if exists
$uploadDir = __DIR__ . '/assets/images/properties/';
if(
    !empty($property['image']) &&
    $property['image'] !== 'default.jpg' &&
    file_exists($uploadDir . $property['image'])
) {
    unlink($uploadDir . $property['image']);
}

// Delete from database
$stmt = $pdo->prepare("
    DELETE FROM properties
    WHERE property_id = ?
");
$stmt->execute([$id]);

setFlash('success', 'Property deleted successfully.');

// Redirect based on role
if(hasRole('admin')) {
    redirect('admin-properties.php');
} else {
    redirect('my-properties.php');
}