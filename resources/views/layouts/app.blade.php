<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>NIC Project</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>

    <style>
        body { background: #111827; }
        .card { border-radius: 12px; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark px-4" style="background-color: #1e2a38;">
        <span class="navbar-brand fw-bold text-info">NiC Project</span>
    </nav>

    <!-- Page Content -->
    <div class="container mt-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
