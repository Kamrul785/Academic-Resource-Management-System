<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Check if user is logged in
requireLogin();

// Get resource ID
$resource_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$resource_id) {
    header("Location: dashboard.php");
    exit;
}

// Get resource details
$stmt = $con->prepare("SELECT * FROM resources WHERE id = ?");
$stmt->bind_param("i", $resource_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: dashboard.php");
    exit;
}

$resource = $result->fetch_assoc();

// Check if user owns the resource or is admin
if ($_SESSION['user_id'] != $resource['user_id'] && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Get departments for dropdown
$departments = $con->query("SELECT * FROM departments ORDER BY name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $department_id = filter_input(INPUT_POST, 'department_id', FILTER_VALIDATE_INT);
    $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
    $custom_course = filter_input(INPUT_POST, 'custom_course', FILTER_SANITIZE_STRING);
    
    if (!$title || !$department_id) {
        $error = "Please fill in all required fields.";
    } else {
        // If using custom course, set course_id to NULL
        if (!empty($custom_course)) {
            $course_id = null;
        }
        
        $stmt = $con->prepare("UPDATE resources SET title = ?, description = ?, department_id = ?, course_id = ?, custom_course = ? WHERE id = ?");
        $stmt->bind_param("ssiisi", $title, $description, $department_id, $course_id, $custom_course, $resource_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Resource updated successfully!";
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Error updating resource: " . $stmt->error;
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Resource</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo h($error); ?></div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required
                               value="<?php echo h($resource['title']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php while ($dept = $departments->fetch_assoc()): ?>
                                <option value="<?php echo $dept['department_id']; ?>"
                                        <?php echo $dept['department_id'] == $resource['department_id'] ? 'selected' : ''; ?>>
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
                            <input type="text" class="form-control" id="customCourse" name="custom_course" 
                                   placeholder="Enter custom course name"
                                   value="<?php echo h($resource['custom_course']); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo h($resource['description']); ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Resource</button>
                    </div>
                </form>
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
                        if (course.course_id == <?php echo $resource['course_id'] ?? 'null'; ?>) {
                            option.selected = true;
                        }
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

    // Set custom course checkbox if custom course exists
    if (customCourseInput.value) {
        useCustomCourseCheckbox.checked = true;
        useCustomCourseCheckbox.dispatchEvent(new Event('change'));
    }
});
</script>

<?php
require_once 'includes/footer.php';
?> 