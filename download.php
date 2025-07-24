<?php
require_once 'config/db.php';
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid resource ID');
}

$id = intval($_GET['id']);

// Get resource information
$stmt = $con->prepare("SELECT * FROM resources WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Resource not found');
}

$resource = $result->fetch_assoc();
$file_path = $resource['file_path'];

// Verify file exists and is readable
if (!file_exists($file_path) || !is_readable($file_path)) {
    die('File not accessible');
}

// Get file information
$file_size = filesize($file_path);
$file_name = basename($file_path);

// Get MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $file_path);
finfo_close($finfo);

// Set headers for download
header('Content-Type: ' . $mime_type);
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Content-Length: ' . $file_size);
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: public');
header('Expires: 0');

// Clear output buffer
ob_clean();
flush();

// Read file and output in chunks
if ($handle = fopen($file_path, 'rb')) {
    while (!feof($handle) && (connection_status() == 0)) {
        echo fread($handle, 8192);
        flush();
    }
    fclose($handle);
}

exit(); 