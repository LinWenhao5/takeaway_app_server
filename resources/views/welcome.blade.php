<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <div class="text-center">
            <h1 class="display-4 text-primary">Welcome to Admin Panel</h1>
            <p class="lead text-secondary">Manage your products, categories, users, and media with ease.</p>
            <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-box-seam me-2"></i> Go to Dashboard
            </a>
            <a href="/api/documentation" class="btn btn-outline-secondary btn-lg mt-3">
                <i class="bi bi-file-earmark-code me-2"></i> View API Documentation
            </a>
        </div>
    </div>
</body>
</html>