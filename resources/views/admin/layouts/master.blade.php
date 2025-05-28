<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts & Icons --}}
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            z-index: 1050;
            transition: left 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar .nav-link {
            color: #ccc;
            padding: 12px 20px;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }

        .sidebar-header {
            font-size: 1.25rem;
            background-color: #23272b;
            padding: 1rem;
            text-align: center;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid #495057;
            text-align: center;
        }

        .content-wrapper {
            margin-left: 0;
            padding-top: 4.5rem;
            transition: margin-left 0.3s;
        }

        @media (min-width: 768px) {
            .sidebar {
                left: 0;
            }

            .content-wrapper {
                margin-left: 250px;
            }

            #sidebarToggle {
                display: none;
            }
        }
    </style>
</head>
<body>
<div id="app">
    {{-- Navbar --}}
    @auth
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
        <div class="container-fluid">
            {{-- Sidebar Toggle Button for small screens --}}
            <button class="btn btn-outline-dark d-md-none me-2" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <a class="navbar-brand" href="{{ url('/admin/dashboard') }}">
                <span class="">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
            </a>

            <div class="ms-auto d-none d-md-block">
                <span class="fw-bold">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </nav>
    @endauth

    {{-- Sidebar --}}
    @php
        $menuItems = $menuItems ?? [
            ['title' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'route' => 'admin.dashboard'],
            ['title' => 'Users', 'icon' => 'fas fa-users', 'route' => 'admin.dashboard'],
            ['title' => 'Orders', 'icon' => 'fas fa-shopping-cart', 'route' => 'admin.dashboard'],
            ['title' => 'Products', 'icon' => 'fas fa-box', 'route' => 'admin.dashboard'],
            ['title' => 'Reports', 'icon' => 'fas fa-chart-line', 'route' => 'admin.dashboard'],
            ['title' => 'Vendor List', 'icon' => 'fas fa-store', 'route' => 'admin.dashboard'],         // Icon for vendors/businesses
            ['title' => 'Live Users', 'icon' => 'fas fa-users', 'route' => 'admin.dashboard'],       // Icon for multiple users
            ['title' => 'Searched Items', 'icon' => 'fas fa-search', 'route' => 'admin.dashboard'],    // Icon for search activity
            ['title' => 'Portfolio Users', 'icon' => 'fas fa-briefcase', 'route' => 'admin.dashboard'], // Icon for portfolios/business profiles
        ]
    @endphp
   @auth
    

    <div class="sidebar bg-dark text-white" id="sidebar">
        <div class="sidebar-header">
            Admin Panel
        </div>

        <ul class="nav flex-column mt-2">
            @foreach ($menuItems as $item)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                       href="{{ route($item['route']) }}">
                        <i class="{{ $item['icon'] }} me-2"></i>{{ $item['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="sidebar-footer">
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
    @endauth

    {{-- Main Content --}}
    <div class="content-wrapper">
        <main class="container-fluid mt-3">
            @yield('content')
        </main>
    </div>
</div>
{{-- JS for Sidebar Toggle and Outside Click --}}
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    document.addEventListener('click', function (event) {
        if (window.innerWidth < 768) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isToggleClick = toggleBtn?.contains(event.target);

            if (!isClickInsideSidebar && !isToggleClick && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        }
    });
</script>

@stack('scripts')  {{-- Add this line here --}}

</body>
</html>

