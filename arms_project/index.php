<?php
require_once 'includes/header.php';
?>

<div class="row align-items-center min-vh-75">
    <div class="col-md-6">
        <h1 class="display-4 fw-bold mb-4">Welcome to ARMS</h1>
        <p class="lead mb-4">Your one-stop platform for accessing and sharing academic resources. Browse study materials, upload your own, and collaborate with fellow students.</p>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="signup.php" class="btn btn-primary btn-lg px-4 me-md-2">Get Started</a>
                <a href="login.php" class="btn btn-outline-primary btn-lg px-4">Login</a>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <img src="assets/images/hero-image.svg" alt="ARMS Hero Image" class="img-fluid">
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-book fs-1 text-primary mb-3"></i>
                <h3 class="card-title">Study Materials</h3>
                <p class="card-text">Access a vast collection of study materials including notes, assignments, and slides from various courses.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-share fs-1 text-primary mb-3"></i>
                <h3 class="card-title">Share Resources</h3>
                <p class="card-text">Upload and share your study materials with fellow students to help them in their academic journey.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-search fs-1 text-primary mb-3"></i>
                <h3 class="card-title">Easy Navigation</h3>
                <p class="card-text">Browse resources by department and course, making it easy to find what you need.</p>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?> 