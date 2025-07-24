<?php
session_start();
require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARMS - Academic Resource Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">ARMS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="browse.php">Browse Resources</a>
                            </li>
                            <?php if($_SESSION['role'] === 'student'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                                </li>
                            <?php endif; ?>
                            <?php if($_SESSION['role'] === 'admin'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="admin_dashboard.php">Admin Panel</a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li class="nav-item">
                                <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="signup.php">Sign Up</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="main-content">
            <div class="container mt-4"> 