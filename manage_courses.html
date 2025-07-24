<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Check if user is admin
requireAdmin();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $department_id = (int)$_POST['department_id'];
                $course_name = trim($_POST['course_name']);
                $course_code = trim($_POST['course_code']);
                
                if (!empty($course_name) && !empty($course_code)) {
                    $stmt = $con->prepare("INSERT INTO courses (department_id, course_name, course_code) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $department_id, $course_name, $course_code);
                    $stmt->execute();
                    $_SESSION['message'] = "Course added successfully!";
                }
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $department_id = (int)$_POST['department_id'];
                $course_name = trim($_POST['course_name']);
                $course_code = trim($_POST['course_code']);
                
                if (!empty($course_name) && !empty($course_code)) {
                    $stmt = $con->prepare("UPDATE courses SET department_id = ?, course_name = ?, course_code = ? WHERE course_id = ?");
                    $stmt->bind_param("issi", $department_id, $course_name, $course_code, $id);
                    $stmt->execute();
                    $_SESSION['message'] = "Course updated successfully!";
                }
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                // Check if course has resources
                $check = $con->prepare("SELECT COUNT(*) FROM resources WHERE course_id = ?");
                $check->bind_param("i", $id);
                $check->execute();
                $result = $check->get_result()->fetch_row();
                
                if ($result[0] == 0) {
                    $stmt = $con->prepare("DELETE FROM courses WHERE course_id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $_SESSION['message'] = "Course deleted successfully!";
                } else {
                    $_SESSION['error'] = "Cannot delete course. It has associated resources.";
                }
                break;
        }
        header("Location: manage_courses.php");
        exit();
    }
}

// Get all departments for dropdown
$departments = $con->query("SELECT * FROM departments ORDER BY name")->fetch_all(MYSQLI_ASSOC);

// Get all courses with department names
$courses = $con->query("
    SELECT c.*, d.name as department_name 
    FROM courses c 
    JOIN departments d ON c.department_id = d.department_id 
    ORDER BY d.name, c.course_name
")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Manage Courses</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                        <i class="bi bi-plus-circle"></i> Add Course
                    </button>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Department</th>
                                    <th>Course Name</th>
                                    <th>Course Code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course): ?>
                                    <tr>
                                        <td><?php echo $course['course_id']; ?></td>
                                        <td><?php echo h($course['department_name']); ?></td>
                                        <td><?php echo h($course['course_name']); ?></td>
                                        <td><?php echo h($course['course_code']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editCourseModal"
                                                    data-id="<?php echo $course['course_id']; ?>"
                                                    data-department-id="<?php echo $course['department_id']; ?>"
                                                    data-course-name="<?php echo h($course['course_name']); ?>"
                                                    data-course-code="<?php echo h($course['course_code']); ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteCourseModal"
                                                    data-id="<?php echo $course['course_id']; ?>"
                                                    data-name="<?php echo h($course['course_name']); ?>">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo $department['department_id']; ?>">
                                    <?php echo h($department['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="course_code" name="course_code" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_department_id" class="form-label">Department</label>
                        <select class="form-select" id="edit_department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo $department['department_id']; ?>">
                                    <?php echo h($department['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_course_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="edit_course_name" name="course_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="edit_course_code" name="course_code" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Course Modal -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Are you sure you want to delete the course "<span id="delete_name"></span>"?</p>
                    <p class="text-danger">Note: This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit modal
    const editModal = document.getElementById('editCourseModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const departmentId = button.getAttribute('data-department-id');
        const courseName = button.getAttribute('data-course-name');
        const courseCode = button.getAttribute('data-course-code');
        
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_department_id').value = departmentId;
        document.getElementById('edit_course_name').value = courseName;
        document.getElementById('edit_course_code').value = courseCode;
    });
    
    // Delete modal
    const deleteModal = document.getElementById('deleteCourseModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        
        document.getElementById('delete_id').value = id;
        document.getElementById('delete_name').textContent = name;
    });
});
</script>

<?php require_once 'includes/footer.php'; ?> 