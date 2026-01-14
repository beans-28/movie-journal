<?php
require_once 'config.php';
require_once 'auth.php';

// Require login
requireLogin();

$userId = getCurrentUserId();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?deleted=error");
    exit();
}

$reviewId = intval($_GET['id']);

// Using stord procedure
$stmt = $conn->prepare("CALL sp_delete_review(?, ?)");

if ($stmt === false) {
    header("Location: index.php?deleted=error");
    exit();
}

$stmt->bind_param("ii", $reviewId, $userId);

if ($stmt->execute()) {
    header("Location: index.php?deleted=success");
} else {
    header("Location: index.php?deleted=error");
}

$stmt->close();
$conn->close();
exit();
?>