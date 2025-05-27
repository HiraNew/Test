<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DLS') }}</title>

    <!-- Fonts & Icons -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin-bottom: 60px; /* Space for mobile footer */
        }

        .icon {
            font-size: 24px;
            margin: 0 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .icon:hover,
        .icon:active {
            color: #0d6efd;
            transform: scale(1.2);
        }

        .badge-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #dc3545;
            color: white;
            font-size: 11px;
            padding: 3px 6px;
            border-radius: 50%;
        }

        .icon-container {
            position: relative;
            display: inline-block;
        }

        .searchbar,
        .searchbar-mobile {
            display: none;
        }

        .navbar-brand {
            font-weight: bold;
            color: #0d6efd !important;
        }

        @media (min-width: 768px) {
            .searchbar {
                display: none;
                max-width: 300px;
                margin-left: 15px;
            }

            .searchbar-mobile {
                display: none !important;
            }

            .desktop-icons {
                display: flex !important;
                align-items: center;
                gap: 1rem;
            }
        }

        @media (max-width: 767.98px) {
            .desktop-navbar {
                display: none !important;
            }

            .mobile-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                background: white;
                border-top: 1px solid #ddd;
                z-index: 1030;
            }

            .mobile-footer .icon {
                font-size: 20px;
                transition: transform 0.2s ease-in-out;
            }

            .mobile-footer .icon:active {
                transform: scale(1.3);
            }

            .badge-count {
                font-size: 9px;
                top: -4px;
                right: -8px;
            }

            .searchbar-mobile {
                display: none;
                margin: 10px auto;
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div id="app">
       {{-- Mobile Header --}}
        <nav class="navbar navbar-light bg-white shadow-sm d-md-none justify-content-between px-3 py-2">
            <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                {{ config('app.name', 'DLS') }}
            </a>

            <div class="d-flex align-items-center">
                <div class="icon-container me-2">
                    <i class="fas fa-search icon search-mobile"></i>
                </div>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-1">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                @endguest
            </div>
            <form method="GET" action="{{ route('products') }}" class="mb-4">
                <input type="search" name="query" class="form-control searchbar-mobile" placeholder="Search..." value="{{ request('query') }}">
            </form>

        </nav>

        {{-- Desktop Navbar --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm desktop-navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="collapse navbar-collapse show">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item desktop-icons">
                                {{-- Search --}}
                                <div class="icon-container">
                                    <i class="fas fa-search icon search-desktop"></i>
                                </div>
                                <form method="GET" action="{{ route('products') }}">
                                <input type="search" name="query" class="form-control searchbar" placeholder="Search..." aria-label="Search">
                                </form>

                                {{-- Cart --}}
                                <div class="icon-container">
                                    <a href="{{ route('cartView') }}">
                                        <i class="fas fa-shopping-cart icon"></i>
                                        <span class="badge-count" id="cart-count">{{ Session::get('key') ?? 0 }}</span>
                                    </a>
                                </div>

                                {{-- Notifications --}}
                                <div class="icon-container">
                                    <a href="{{ route('notificationView') }}">
                                        <i class="fas fa-bell icon"></i>
                                        <span class="badge-count clickNotification">0</span>
                                    </a>
                                </div>

                                {{-- Coins --}}
                                <div class="icon-container">
                                    <i class="fas fa-coins icon"></i>
                                    <span class="badge-count">{{ Auth::user()->coins ?? 0 }}</span>
                                </div>

                                {{-- Profile --}}
                                <div class="icon-container">
                                    {{-- {{ route('profile') }} --}}
                                    <a href="">
                                        <i class="fas fa-user icon"></i>
                                    </a>
                                </div>
                            </li>
                        @endauth
                        
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Hi, {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')
        </main>

        {{-- Mobile Sticky Footer --}}
        @auth
        <footer class="mobile-footer d-md-none">
            <div class="d-flex justify-content-around align-items-center py-2">
                {{-- Home --}}
                <a href="{{ url('/') }}" class="icon-container">
                    <i class="fas fa-home icon"></i>
                </a>

                {{-- Cart --}}
                <a href="{{ route('cartView') }}" class="icon-container">
                    <i class="fas fa-shopping-cart icon"></i>
                    <span class="badge-count" id="cart-count-mobile">{{ Session::get('key') ?? 0 }}</span>
                </a>

                {{-- Notifications --}}
                <a href="{{ route('notificationView') }}" class="icon-container">
                    <i class="fas fa-bell icon"></i>
                    <span class="badge-count clickNotification">0</span>
                </a>

                {{-- Coins --}}
                <div class="icon-container">
                    <i class="fas fa-coins icon"></i>
                    <span class="badge-count">{{ Auth::user()->coins ?? 0 }}</span>
                </div>

                {{-- Profile --}}
                {{-- {{ route('profile') }} --}}
                <a href="" class="icon-container">
                    <i class="fas fa-user icon"></i>
                </a>
            </div>
        </footer>
        @endauth
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Toggle search bar (desktop)
            $(".searchbar").hide();
            $(".search-desktop").click(function () {
                $(".searchbar").fadeToggle("fast").focus();
            });

            // Toggle search bar (mobile)
            $(".searchbar-mobile").hide();
            $(".search-mobile").click(function () {
                $(".searchbar-mobile").fadeToggle("fast").focus();
            });

            // Load notifications
            $.ajax({
                url: "{{ route('notification') }}",
                type: "GET",
                success: function (data) {
                    if (data.status === 'success') {
                        $('.clickNotification').text(data.notification);
                    }
                }
            });
            // Load cart count
            $.ajax({
                url: "{{ route('carting') }}",
                type: "GET",
                success: function (data) {
                    if (data.status === 'success') {
                        $('#cart-count, #cart-count-mobile').text(data.cart);
                    }
                }
            });
        });
        
    </script>
</body>
</html>
