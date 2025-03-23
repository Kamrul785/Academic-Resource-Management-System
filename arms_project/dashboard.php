<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Check if user is logged in
requireLogin();

// Get user's resources
$resources = getUserResources($_SESSION['user_id'], $con);

// Get departments for dropdown
$departments = $con->query("SELECT * FROM departments ORDER BY name");

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $success = false;

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
    $file = $_FILES['file'] ?? null;

    // Validation
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($course_id)) {
        $errors[] = "Please select a course";
    }
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Please select a file to upload";
    } elseif (!isValidFileType($file['name'])) {
        $errors[] = "Invalid file type. Allowed types: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR";
    }

    if (empty($errors)) {
        // Create uploads directory if it doesn't exist
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $filename = generateUniqueFilename($file['name']);
        $filepath = $upload_dir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Insert into database
            $stmt = $con->prepare("
                INSERT INTO resources (user_id, course_id, title, file_path, description, file_type)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $file_type = getFileExtension($file['name']);
            $stmt->bind_param("iissss", $_SESSION['user_id'], $course_id, $title, $filepath, $description, $file_type);
            
            if ($stmt->execute()) {
                $success = true;
                $_SESSION['success_message'] = "Resource uploaded successfully!";
                header("Location: dashboard.php");
                exit();
            } else {
                $errors[] = "Failed to save resource information";
            }
        } else {
            $errors[] = "Failed to upload file";
        }
    }
}
?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Upload New Resource</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo h($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php while ($dept = $departments->fetch_assoc()): ?>
                                <option value="<?php echo $dept['department_id']; ?>">
                                    <?php echo h($dept['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Select Course</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <div class="form-text">Allowed types: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR</div>
                    </div>

                    <button type="submit" class="btn btn-primary">Upload Resource</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>My Uploaded Resources</h4>
            </div>
            <div class="card-body">
                <?php if (empty($resources)): ?>
                    <p class="text-muted">You haven't uploaded any resources yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Course</th>
                                    <th>Type</th>
                                    <th>Uploaded</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resources as $resource): ?>
                                    <tr>
                                        <td><?php echo h($resource['title']); ?></td>
                                        <td><?php echo h($resource['course_name']); ?> (<?php echo h($resource['course_code']); ?>)</td>
                                        <td><?php echo strtoupper(h($resource['file_type'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($resource['created_at'])); ?></td>
                                        <td>
                                            <a href="<?php echo h($resource['file_path']); ?>" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <a href="edit_resource.php?id=<?php echo $resource['resource_id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="delete_resource.php?id=<?php echo $resource['resource_id']; ?>" class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this resource?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Dynamic course loading based on department selection
document.getElementById('department_id').addEventListener('change', function() {
    const departmentId = this.value;
    const courseSelect = document.getElementById('course_id');
    
    // Clear current options
    courseSelect.innerHTML = '<option value="">Select Course</option>';
    
    if (departmentId) {
        // Fetch courses for selected department
        fetch(`get_courses.php?department_id=${departmentId}`)
            .then(response => response.json())
            .then(courses => {
                courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.course_id;
                    option.textContent = `${course.course_name} (${course.course_code})`;
                    courseSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }
});

// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php
require_once 'includes/footer.php';
?> 