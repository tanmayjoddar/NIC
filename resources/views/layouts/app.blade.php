<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>NiC Project</title>

    <!-- Bootstrap CSS (no setup needed) -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />

    <style>
        body { background: #f5f5f5; }
        .card { border-radius: 12px; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark px-4">
        <span class="navbar-brand fw-bold">NiC Project</span>
    </nav>

    <!-- Page Content -->
    <div class="container mt-5">
        @yield('content')  {{-- ← child pages go here --}}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
