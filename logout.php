<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Copy the contents of includes/header.php here -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout | ARMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Optional: Redirect to homepage after 2 seconds
        setTimeout(function() {
            window.location.href = "index.html";
        }, 2000);
    </script>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="alert alert-info text-center">
                    <h4 class="mb-3">You have been logged out.</h4>
                    <p>Redirecting to the <a href="index.html">home page</a>...</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Copy the contents of includes/footer.php here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>