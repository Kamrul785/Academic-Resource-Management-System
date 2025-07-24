<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

// Get department ID from request
$department_id = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);

if (!$department_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid department ID']);
    exit;
}

try {
    // Prepare and execute query
    $stmt = $con->prepare("SELECT course_id, course_name, course_code FROM courses WHERE department_id = ? ORDER BY course_name");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch all courses
    $courses = [];
    while ($course = $result->fetch_assoc()) {
        $courses[] = $course;
    }
    
    // Return courses as JSON
    header('Content-Type: application/json');
    echo json_encode($courses);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch courses']);
}
?> 