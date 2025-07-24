<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Check if user is logged in
requireLogin();

// Get resource ID
$resource_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$resource_id) {
    $_SESSION['error_message'] = "Invalid resource ID";
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'dashboard.php'));
    exit();
}

// Check if user has permission to delete this resource
$stmt = $con->prepare("
    SELECT r.*, u.user_id as uploader_id 
    FROM resources r 
    JOIN users u ON r.user_id = u.user_id 
    WHERE r.id = ?
");
$stmt->bind_param("i", $resource_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Resource not found";
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'dashboard.php'));
    exit();
}

$resource = $result->fetch_assoc();

// Only allow admin or resource owner to delete
if ($_SESSION['role'] !== 'admin' && $_SESSION['user_id'] !== $resource['uploader_id']) {
    $_SESSION['error_message'] = "You don't have permission to delete this resource";
    header("Location: dashboard.php");
    exit();
}

// Delete physical file
if (file_exists($resource['file_path'])) {
    unlink($resource['file_path']);
}

// Delete votes and comments
$stmt = $con->prepare("DELETE FROM votes WHERE resource_id = ?");
$stmt->bind_param("i", $resource_id);
$stmt->execute();

$stmt = $con->prepare("DELETE FROM comments WHERE resource_id = ?");
$stmt->bind_param("i", $resource_id);
$stmt->execute();

// Delete resource record
$stmt = $con->prepare("DELETE FROM resources WHERE id = ?");
$stmt->bind_param("i", $resource_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Resource deleted successfully";
} else {
    $_SESSION['error_message'] = "Failed to delete resource";
}

header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'dashboard.php'));
exit();
?>