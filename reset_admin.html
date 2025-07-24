<?php
require_once 'config/db.php';

// New admin credentials
$admin_email = 'admin@example.com';
$admin_password = 'admin123';
$admin_name = 'Admin';

// Hash the password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// First, check if admin exists
$check_stmt = $con->prepare("SELECT user_id FROM users WHERE email = ?");
$check_stmt->bind_param("s", $admin_email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing admin
    $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $admin_email);
} else {
    // Create new admin
    $stmt = $con->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt->bind_param("sss", $admin_name, $admin_email, $hashed_password);
}

if ($stmt->execute()) {
    echo "Admin password has been reset successfully!<br>";
    echo "Email: " . $admin_email . "<br>";
    echo "Password: " . $admin_password;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$con->close();
?> 