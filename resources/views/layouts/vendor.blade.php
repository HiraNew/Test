<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
            margin: 0;
            overflow-x: hidden;
        }

        .sidebar {
            background-color: #2d3748;
            color: white;
            padding-top: 1rem;
            height: 100vh;
            transition: transform 0.3s ease;
            z-index: 1050;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #4a5568;
        }

        #sidebarToggle {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
        }

        /* Mobile view */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            #main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }

        /* Desktop view */
        @media (min-width: 769px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                transform: translateX(0);
            }

            #main-content {
                margin-left: 250px;
                padding: 2rem;
            }

            #sidebarToggle {
                display: none;
            }
        }
    </style>
</head>
<body>

@if (isset(Auth::guard('vendor')->user()->name))
    <!-- Toggle Button (mobile only) -->
    <button class="btn btn-dark d-md-none" id="sidebarToggle">
        <i class="bi bi-list" id="toggleIcon"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        @include('Vendor.partials.sidebar')
    </div>
@endif

<!-- Main Content -->
<div id="main-content">
    @yield('content')
    @stack('scripts')
</div>

<!-- Bootstrap Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        toggleIcon.classList.toggle('bi-list');
        toggleIcon.classList.toggle('bi-x');
    });

    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('active');
            toggleIcon.classList.remove('bi-x');
            toggleIcon.classList.add('bi-list');
        }
    });
</script>

</body>
</html>
