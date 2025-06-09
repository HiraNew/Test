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
        //for mobile footer sticky
        .icon-container i {
            font-size: 20px;
        }
        .icon-container small {
            font-size: 12px;
            line-height: 1;
        }
        .badge-count {
            position: absolute;
            top: 4px;
            right: 12px;
            font-size: 10px;
            background: red;
            color: white;
            padding: 2px 5px;
            border-radius: 50%;
        }
        .badge-count-custom {
            position: absolute;
            bottom: 0px;   /* Moves the badge below */
            right: 1px;     /* Moves the badge to the left */
            background-color: red;
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 50%;
            /* min-width: 16px;
            text-align: center;
            line-height: 1; */
        }
       @media (max-width: 575.98px) {
            .navbar .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.4rem;
            }

            .navbar-brand {
                font-size: 0.95rem;
            }
        }
        /* Ensure both mobile and desktop navbars are sticky */
        .sticky-navbar {
            position: sticky;
            top: 0;
            z-index: 1040;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }

        /* Prevent body overlap under navbar */
        body {
            padding-top: 0px; /* Height of navbar */
        }

        /* Adjust padding for smaller screen navbar height */
        @media (max-width: 767.98px) {
            body {
                padding-top: 0px; /* Slightly taller mobile navbar */
            }
        }


    </style>
</head>
<body>
    <div id="app">
       {{-- Mobile Header --}}
        <nav class="navbar navbar-light bg-white shadow-sm d-md-none px-2 py-2" style="position: sticky; top: 0; z-index: 1040;">
            <div class="d-flex align-items-center w-100 gap-2">

                {{-- Brand --}}
                <a class="navbar-brand fw-bold text-primary mb-0 me-1 flex-shrink-0" href="{{ url('/') }}" style="font-size: 1rem;">
                    {{ config('app.name', 'DLS') }}
                </a>

                {{-- Searchbar (smaller) --}}
                <form method="GET" action="{{ route('products') }}" class="position-relative flex-grow-1">
                    <input type="search" name="query" id="live-search-mobile" class="form-control form-control-sm px-2 py-1" placeholder="Search..." autocomplete="off" style="font-size: 0.8rem;">
                    <div id="live-search-results-mobile" class="list-group position-absolute w-100 d-none" style="z-index: 999;"></div>
                </form>

                {{-- Wishlist Icon --}}
                <div class="text-center flex-shrink-0">
                    {{-- {{ route('wishlist.index') }} --}}
                    <a href="{{ route('wishlist.index') }}" class="d-flex flex-column align-items-center text-decoration-none text-dark">
                        <i class="fas fa-heart text-danger" style="font-size: 1rem;"></i>
                        <small style="font-size: 0.65rem;">Wishlist</small>
                    </a>
                </div>

                {{-- Auth Buttons --}}
                @guest
                    <div class="d-flex flex-shrink-0 gap-1">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-2 py-1">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-2 py-1">Register</a>
                    </div>
                @endguest
            </div>
        </nav>




        {{-- Desktop Navbar --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm desktop-navbar sticky-navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="collapse navbar-collapse show">
                    <ul class="navbar-nav me-auto">
                        {{-- @auth --}}
                            <li class="nav-item desktop-icons">
                                {{-- Search --}}
                                {{-- <div class="icon-container">
                                    <i class="fas fa-search icon search-desktop"></i>
                                </div> --}}
                               {{-- Desktop Searchbar --}}
                                <form method="GET" action="{{ route('products') }}" class="position-relative">
                                    <input type="search" name="query" id="live-search" class="form-control searchbar" placeholder="Search..." autocomplete="off">
                                    <div id="live-search-results" class="list-group position-absolute w-100 d-none" style="z-index: 999;"></div>
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
                                {{-- {{ route('wishlist.index') }} --}}
                                <div class="icon-container">
                                    {{-- {{ route('profile') }} --}}
                                    <a href="{{ route('wishlist.index') }}">Wishlist</a>
                                </div>
                            </li>
                        {{-- @endauth --}}
                        
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
        {{-- @auth --}}
        <footer class="mobile-footer d-md-none">
            <div class="d-flex justify-content-around align-items-center py-2 text-center">
                {{-- Home --}}
                <a href="{{ url('/') }}" class="icon-container d-flex flex-column align-items-center text-decoration-none text-dark">
                    <i class="fas fa-home icon text-primary"></i>
                    <small>Home</small>
                </a>

                {{-- Cart --}}
                <a href="{{ route('cartView') }}" class="icon-container d-flex flex-column align-items-center position-relative text-decoration-none text-dark">
                    <div class="icon-container position-relative">
                        <i class="fas fa-shopping-cart icon text-primary"></i>
                        <span class="badge-count-custom" id="cart-count-mobile">
                            {{ Session::get('key') ?? 0 }}
                        </span>
                    </div>
                    <small>Cart</small>
                </a>

                {{-- Notifications --}}
                <a href="{{ route('notificationView') }}" class="icon-container d-flex flex-column align-items-center position-relative text-decoration-none text-dark">
                    <i class="fas fa-bell icon text-primary"></i>
                    <span class="badge-count clickNotification">0</span>
                    <small>Notifications</small>
                </a>

                {{-- Logout --}}
                @auth
                <a href="#" class="icon-container d-flex flex-column align-items-center text-decoration-none text-dark" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt icon text-danger"></i>
                    <small>Logout</small>
                </a>
                @endauth

                {{-- Profile --}}
                <a href="{{route('user.account')}}" class="icon-container d-flex flex-column align-items-center text-decoration-none text-dark">
                    <i class="fas fa-user icon text-primary"></i>
                    <small>Account</small>
                </a>
            </div>

            
        </footer>
        {{-- @endauth --}}
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script>
        $(document).ready(function () {
            // Toggle search bar (desktop)
            $(".searchbar").show();
            $(".search-desktop").click(function () {
                $(".searchbar").fadeToggle("fast").focus();
            });

            // Toggle search bar (mobile)
            $(".searchbar-mobile").show();
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
         $(document).ready(function () {
        // Desktop live search
        $('#live-search').on('input', function () {
            let query = $(this).val();

            if (query.length >= 2) {
                $.ajax({
                    url: "{{ route('products.liveSearch') }}",
                    type: 'GET',
                    data: { query: query },
                    success: function (response) {
                        let resultBox = $('#live-search-results');
                        resultBox.empty().removeClass('d-none');

                        if (response.length === 0) {
                            resultBox.append('<div class="list-group-item">No results found</div>');
                        } else {
                            response.forEach(function (product) {
                                resultBox.append(`
                                    <a href="/product/${product.id}" class="list-group-item list-group-item-action">
                                        ${product.name}
                                    </a>
                                `);
                            });
                        }
                    }
                });
            } else {
                $('#live-search-results').addClass('d-none').empty();
            }
        });

        // Mobile live search
        $('#live-search-mobile').on('input', function () {
            let query = $(this).val();

            if (query.length >= 2) {
                $.ajax({
                    url: "{{ route('products.liveSearch') }}",
                    type: 'GET',
                    data: { query: query },
                    success: function (response) {
                        let resultBox = $('#live-search-results-mobile');
                        resultBox.empty().removeClass('d-none');

                        if (response.length === 0) {
                            resultBox.append('<div class="list-group-item">No results found</div>');
                        } else {
                            response.forEach(function (product) {
                                resultBox.append(`
                                    <a href="/product/${product.id}" class="list-group-item list-group-item-action">
                                        ${product.name}
                                    </a>
                                `);
                            });
                        }
                    }
                });
            } else {
                $('#live-search-results-mobile').addClass('d-none').empty();
            }
        });
    });
        
        
    </script>
    @stack('scripts')
</body>
</html>
