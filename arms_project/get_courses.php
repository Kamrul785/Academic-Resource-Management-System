<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if (!isset($_GET['department_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Department ID is required']);
    exit();
}

$department_id = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);

if (!$department_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid department ID']);
    exit();
}

try {
    $courses = getDepartmentCourses($department_id, $con);
    echo json_encode($courses);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch courses']);
}
?> 