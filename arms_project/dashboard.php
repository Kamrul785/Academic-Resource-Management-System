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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $department_id = filter_input(INPUT_POST, 'department_id', FILTER_VALIDATE_INT);
    $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
    $custom_course = filter_input(INPUT_POST, 'custom_course', FILTER_SANITIZE_STRING);
    
    if (!$title || !$department_id) {
        $error = "Please fill in all required fields.";
    } else {
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_type = $file['type'];
        
        // Validate file type
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                         'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                         'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
        
        if (!in_array($file_type, $allowed_types)) {
            $error = "Invalid file type. Allowed types: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX";
        } else {
            // Create uploads directory if it doesn't exist
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
            $file_path = $upload_dir . $unique_filename;
            
            // Debug information
            error_log("Attempting to upload file to: " . $file_path);
            
            if (move_uploaded_file($file_tmp, $file_path)) {
                // If using custom course, set course_id to NULL
                if (!empty($custom_course)) {
                    $course_id = null;
                }
                
                $stmt = $con->prepare("INSERT INTO resources (title, description, file_path, file_type, file_size, department_id, course_id, custom_course, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssiisii", $title, $description, $file_path, $file_type, $file_size, $department_id, $course_id, $custom_course, $_SESSION['user_id']);
                
                if ($stmt->execute()) {
                    $success = "Resource uploaded successfully!";
                    error_log("Resource uploaded successfully: " . $file_path);
                } else {
                    $error = "Error uploading resource: " . $stmt->error;
                    error_log("Database error: " . $stmt->error);
                }
                $stmt->close();
            } else {
                $error = "Error moving uploaded file. Upload error: " . $file['error'];
                error_log("File upload error: " . $file['error']);
            }
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
                        <label for="course" class="form-label">Course</label>
                        <select class="form-select" id="course" name="course_id">
                            <option value="">Select Course</option>
                        </select>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="useCustomCourse">
                            <label class="form-check-label" for="useCustomCourse">
                                Use Custom Course
                            </label>
                        </div>
                        <div class="mt-2" id="customCourseDiv" style="display: none;">
                            <input type="text" class="form-control" id="customCourse" name="custom_course" placeholder="Enter custom course name">
                        </div>
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
                                        <td>
                                            <?php 
                                            if (!empty($resource['custom_course'])) {
                                                echo h($resource['custom_course']);
                                            } else {
                                                echo h($resource['course_name']) . ' (' . h($resource['course_code']) . ')';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo strtoupper(pathinfo($resource['file_path'], PATHINFO_EXTENSION)); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($resource['created_at'])); ?></td>
                                        <td>
                                            <a href="download.php?id=<?php echo $resource['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <a href="edit_resource.php?id=<?php echo $resource['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="delete_resource.php?id=<?php echo $resource['id']; ?>" class="btn btn-sm btn-danger" 
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
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const courseSelect = document.getElementById('course');
    const useCustomCourseCheckbox = document.getElementById('useCustomCourse');
    const customCourseDiv = document.getElementById('customCourseDiv');
    const customCourseInput = document.getElementById('customCourse');

    // Handle custom course checkbox
    useCustomCourseCheckbox.addEventListener('change', function() {
        if (this.checked) {
            courseSelect.disabled = true;
            customCourseDiv.style.display = 'block';
        } else {
            courseSelect.disabled = false;
            customCourseDiv.style.display = 'none';
            customCourseInput.value = '';
        }
    });

    // Handle department change
    departmentSelect.addEventListener('change', function() {
        const departmentId = this.value;
        courseSelect.innerHTML = '<option value="">Select Course</option>';
        
        if (departmentId) {
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

    // If department is pre-selected, load its courses
    if (departmentSelect.value) {
        departmentSelect.dispatchEvent(new Event('change'));
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