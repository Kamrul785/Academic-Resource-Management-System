<?php
/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 * @return bool
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error_message'] = "Please login to access this page.";
        header("Location: login.php");
        exit();
    }
}

/**
 * Redirect if not admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        $_SESSION['error_message'] = "You don't have permission to access this page.";
        header("Location: index.php");
        exit();
    }
}

/**
 * Sanitize output
 * @param string $str
 * @return string
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Format file size
 * @param int $bytes
 * @return string
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Get file extension
 * @param string $filename
 * @return string
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Validate file type
 * @param string $filename
 * @return bool
 */
function isValidFileType($filename) {
    $allowed_types = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'zip', 'rar'];
    $extension = getFileExtension($filename);
    return in_array($extension, $allowed_types);
}

/**
 * Generate unique filename
 * @param string $original_filename
 * @return string
 */
function generateUniqueFilename($original_filename) {
    $extension = getFileExtension($original_filename);
    return uniqid() . '_' . time() . '.' . $extension;
}

/**
 * Get user's uploaded resources
 * @param int $user_id
 * @param mysqli $con
 * @return array
 */
function getUserResources($user_id, $con) {
    $stmt = $con->prepare("
        SELECT r.*, 
               d.name as department_name,
               r.custom_course as display_course
        FROM resources r
        LEFT JOIN departments d ON r.department_id = d.department_id
        WHERE r.user_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $resources = [];
    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }
    return $resources;
}

/**
 * Get resource details with user info
 * @param int $resource_id
 * @param mysqli $con
 * @return array|null
 */
function getResourceDetails($resource_id, $con) {
    $stmt = $con->prepare("
        SELECT r.*, u.name as uploader_name, c.course_name, c.course_code, d.name as department_name
        FROM resources r
        JOIN users u ON r.user_id = u.user_id
        JOIN courses c ON r.course_id = c.course_id
        JOIN departments d ON c.department_id = d.department_id
        WHERE r.resource_id = ?
    ");
    $stmt->bind_param("i", $resource_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

/**
 * Get course resources
 * @param int $course_id
 * @param mysqli $con
 * @return array
 */
function getCourseResources($course_id, $con) {
    $stmt = $con->prepare("
        SELECT r.*, u.name as uploader_name
        FROM resources r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.course_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get department courses
 * @param int $department_id
 * @param mysqli $con
 * @return array
 */
function getDepartmentCourses($department_id, $con) {
    $stmt = $con->prepare("
        SELECT * FROM courses 
        WHERE department_id = ? 
        ORDER BY course_name
    ");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?> 