<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Check if user is admin
requireAdmin();

// Get statistics
$stats = [
    'users' => $con->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'")->fetch_assoc()['count'],
    'resources' => $con->query("SELECT COUNT(*) as count FROM resources")->fetch_assoc()['count'],
    'departments' => $con->query("SELECT COUNT(*) as count FROM departments")->fetch_assoc()['count'],
    'courses' => $con->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count']
];

// Get recent resources
$recent_resources = $con->query("
    SELECT r.*, u.name as uploader_name, c.course_name, c.course_code
    FROM resources r
    JOIN users u ON r.user_id = u.user_id
    JOIN courses c ON r.course_id = c.course_id
    ORDER BY r.created_at DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Get recent users
$recent_users = $con->query("
    SELECT * FROM users 
    WHERE role = 'student' 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Students</h5>
                <h2 class="card-text"><?php echo $stats['users']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Resources</h5>
                <h2 class="card-text"><?php echo $stats['resources']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Departments</h5>
                <h2 class="card-text"><?php echo $stats['departments']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Courses</h5>
                <h2 class="card-text"><?php echo $stats['courses']; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Resources</h5>
                <a href="browse.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_resources as $resource): ?>
                                <tr>
                                    <td><?php echo h($resource['title']); ?></td>
                                    <td><?php echo h($resource['course_name']); ?></td>
                                    <td><?php echo h($resource['uploader_name']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($resource['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo h($resource['file_path']); ?>" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <a href="delete_resource.php?id=<?php echo $resource['resource_id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this resource?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Users</h5>
                <a href="manage_users.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_users as $user): ?>
                                <tr>
                                    <td><?php echo h($user['name']); ?></td>
                                    <td><?php echo h($user['email']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="manage_departments.php" class="btn btn-outline-primary w-100 mb-3">
                            <i class="bi bi-building"></i> Manage Departments
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="manage_courses.php" class="btn btn-outline-primary w-100 mb-3">
                            <i class="bi bi-book"></i> Manage Courses
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="manage_users.php" class="btn btn-outline-primary w-100 mb-3">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="browse.php" class="btn btn-outline-primary w-100 mb-3">
                            <i class="bi bi-files"></i> Browse Resources
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?> 