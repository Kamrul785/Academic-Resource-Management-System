<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Check if user is admin
requireAdmin();

// Get user ID
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$user_id) {
    $_SESSION['error_message'] = "Invalid user ID";
    header("Location: manage_users.php");
    exit();
}

// Check if user exists and is not an admin
$stmt = $con->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "User not found";
    header("Location: manage_users.php");
    exit();
}

$user = $result->fetch_assoc();
if ($user['role'] === 'admin') {
    $_SESSION['error_message'] = "Cannot delete admin users";
    header("Location: manage_users.php");
    exit();
}

// Delete user's resources first
$stmt = $con->prepare("SELECT file_path FROM resources WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resources = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Delete physical files
foreach ($resources as $resource) {
    if (file_exists($resource['file_path'])) {
        unlink($resource['file_path']);
    }
}

// Delete resource records
$stmt = $con->prepare("DELETE FROM resources WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Delete user's votes and comments
$stmt = $con->prepare("DELETE FROM votes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$stmt = $con->prepare("DELETE FROM comments WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Finally, delete the user
$stmt = $con->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "User deleted successfully";
} else {
    $_SESSION['error_message'] = "Failed to delete user";
}

header("Location: manage_users.php");
exit();
?> 