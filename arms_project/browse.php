<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Get filter parameters
$department_id = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
$course_id = filter_input(INPUT_GET, 'course_id', FILTER_VALIDATE_INT);
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

// Get departments for filter
$departments = $con->query("SELECT * FROM departments ORDER BY name");

// Build query
$query = "
    SELECT r.*, u.name as uploader_name, c.course_name, c.course_code, d.name as department_name
    FROM resources r
    JOIN users u ON r.user_id = u.user_id
    JOIN courses c ON r.course_id = c.course_id
    JOIN departments d ON c.department_id = d.department_id
    WHERE 1=1
";

$params = [];
$types = "";

if ($department_id) {
    $query .= " AND c.department_id = ?";
    $params[] = $department_id;
    $types .= "i";
}

if ($course_id) {
    $query .= " AND r.course_id = ?";
    $params[] = $course_id;
    $types .= "i";
}

if ($search) {
    $query .= " AND (r.title LIKE ? OR r.description LIKE ? OR c.course_name LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
    $types .= "sss";
}

$query .= " ORDER BY r.created_at DESC";

// Execute query
$stmt = $con->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resources = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id">
                            <option value="">All Departments</option>
                            <?php while ($dept = $departments->fetch_assoc()): ?>
                                <option value="<?php echo $dept['department_id']; ?>"
                                        <?php echo $department_id == $dept['department_id'] ? 'selected' : ''; ?>>
                                    <?php echo h($dept['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course</label>
                        <select class="form-select" id="course_id" name="course_id">
                            <option value="">All Courses</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo h($search ?? ''); ?>" 
                               placeholder="Search resources...">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Resources</h5>
                <span class="badge bg-primary"><?php echo count($resources); ?> results</span>
            </div>
            <div class="card-body">
                <?php if (empty($resources)): ?>
                    <p class="text-muted">No resources found matching your criteria.</p>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($resources as $resource): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo h($resource['title']); ?></h5>
                                        <p class="card-text text-muted">
                                            <small>
                                                <i class="bi bi-book"></i> <?php echo h($resource['course_name']); ?> (<?php echo h($resource['course_code']); ?>)<br>
                                                <i class="bi bi-building"></i> <?php echo h($resource['department_name']); ?><br>
                                                <i class="bi bi-person"></i> <?php echo h($resource['uploader_name']); ?><br>
                                                <i class="bi bi-calendar"></i> <?php echo date('M d, Y', strtotime($resource['created_at'])); ?>
                                            </small>
                                        </p>
                                        <?php if ($resource['description']): ?>
                                            <p class="card-text"><?php echo h($resource['description']); ?></p>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-secondary"><?php echo strtoupper(h($resource['file_type'])); ?></span>
                                            <a href="<?php echo h($resource['file_path']); ?>" class="btn btn-primary btn-sm" target="_blank">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
    courseSelect.innerHTML = '<option value="">All Courses</option>';
    
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

// If department is pre-selected, load its courses
const departmentSelect = document.getElementById('department_id');
if (departmentSelect.value) {
    departmentSelect.dispatchEvent(new Event('change'));
}
</script>

<?php
require_once 'includes/footer.php';
?> 