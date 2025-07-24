<?php
require_once 'config/db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get all resources
$result = $con->query("SELECT * FROM resources ORDER BY id DESC LIMIT 5");

echo "<h2>Recent Resources</h2>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Title</th><th>File Path</th><th>Exists</th><th>Readable</th><th>Size</th></tr>";

while ($resource = $result->fetch_assoc()) {
    $exists = file_exists($resource['file_path']) ? 'Yes' : 'No';
    $readable = is_readable($resource['file_path']) ? 'Yes' : 'No';
    $size = file_exists($resource['file_path']) ? filesize($resource['file_path']) : 'N/A';
    
    echo "<tr>";
    echo "<td>" . $resource['id'] . "</td>";
    echo "<td>" . htmlspecialchars($resource['title']) . "</td>";
    echo "<td>" . htmlspecialchars($resource['file_path']) . "</td>";
    echo "<td>" . $exists . "</td>";
    echo "<td>" . $readable . "</td>";
    echo "<td>" . $size . "</td>";
    echo "</tr>";
}

echo "</table>";

// Check uploads directory
echo "<h2>Uploads Directory</h2>";
echo "Directory exists: " . (file_exists('uploads') ? 'Yes' : 'No') . "<br>";
echo "Directory readable: " . (is_readable('uploads') ? 'Yes' : 'No') . "<br>";
echo "Directory writable: " . (is_writable('uploads') ? 'Yes' : 'No') . "<br>";

// List files in uploads directory
echo "<h2>Files in Uploads Directory</h2>";
if (file_exists('uploads')) {
    $files = scandir('uploads');
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $path = 'uploads/' . $file;
            echo "<li>" . htmlspecialchars($file) . " - Size: " . filesize($path) . " bytes</li>";
        }
    }
    echo "</ul>";
} 