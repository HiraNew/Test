<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    
    <title>{{ config('app.name', 'DLS') }}</title>
    <meta name="user-id" content="{{ auth()->check() ? auth()->id() : '' }}">

    <!-- Fonts & Icons -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @yield('cdn-css')
    <!-- Styles -->
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            /* padding-top: 60px;  Space for sticky navbar */
            padding-bottom: 70px; /* Space for sticky footer */
            margin: 0;
        }

        .icon {
            font-size: 20px;
            transition: transform 0.2s ease;
        }

        .icon:hover {
            color: #fff;
            transform: scale(1.2);
        }

        .icon-container {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .badge-custom,
        .badge-count-custom {
            position: absolute;
            top: -5px;
            right: -6px;
            background-color: red;
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 50%;
            min-width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-custom {
            background-color: #e40046;
            color: white;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link,
        .navbar-custom .icon,
        .navbar-custom .btn {
            color: white !important;
        }

        .navbar-custom .search-input {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 25px;
            padding: 8px 16px;
            font-size: 0.9rem;
        }

        .navbar-custom .search-input:focus {
            box-shadow: none;
            outline: none;
        }

        .sticky-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1050;
            background-color: #52021a;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
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
                z-index: 1050;
                box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            }

            .mobile-footer .icon {
                font-size: 18px;
            }

            .navbar-brand {
                font-size: 1rem;
            }
        }
        .mobile-footer {
            background-color: #52021a !important;
            color: white !important;
        }

    </style>




</head>
<body>
    <div id="app" style="margin-bottom: 80px;">
        <nav class="navbar navbar-expand-md navbar-custom sticky-navbar shadow-sm py-2 px-3">
            <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="navbar-brand fw-bold">
                    <span class="d-inline d-md-none">E-Mart</span>
                    <span class="d-none d-md-inline">{{ config('app.name', 'E-SAKROULI') }}</span>
                </a>

                {{-- Icons --}}
                <div class="d-flex align-items-center gap-3 order-md-3">
                    <a href="{{ route('wishlist.index') }}" class="icon"><i class="fas fa-heart fs-5"></i></a>

                    <a href="{{ route('cartView') }}" class="icon position-relative">
                        <i class="fas fa-shopping-cart fs-5"></i>
                        <span class="badge bg-light text-danger badge-custom position-absolute top-0 start-100 translate-middle">
                            {{ Session::get('key') ?? 0 }}
                        </span>
                    </a>

                    <a href="{{ route('notificationView') }}" class="icon position-relative">
                        <i class="fas fa-bell fs-5"></i>
                        <span class="badge bg-warning text-dark badge-custom position-absolute top-0 start-100 translate-middle clickNotification">
                            0
                        </span>

                    </a>
                </div>

                {{-- Searchbar (stacked only on small screen) --}}
                <div class="w-100 mt-2 d-md-none order-3">
                    <form method="GET" action="{{ route('products') }}" class="mobile-search d-flex align-items-center gap-2">
                        <input type="search" name="query" class="form-control search-input" placeholder="Search for products...">
                        
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-success btn-sm">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary btn-sm text-danger fw-bold">Register</a>
                        @endguest
                    </form>
                </div>


                {{-- Search inline on larger screens --}}
                <div class="d-none d-md-block w-50">
                    <form method="GET" action="{{ route('products') }}">
                        <input type="search" name="query" class="form-control search-input" placeholder="Search for products...">
                    </form>
                </div>

                {{-- Auth Buttons --}}
                <div class="d-none d-md-flex gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-light btn-sm text-danger fw-bold">Register</a>
                    @else
                        <div class="dropdown">
                            <a class="btn btn-outline-success btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Hi, {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>

</div>

    {{-- Recently Viewed Section (Optional)
    <div class="container my-3">
        <h6 class="fw-bold mb-2">Recently viewed products</h6>
        <div class="d-flex align-items-center gap-3 overflow-auto">
            <div class="card border-0 shadow-sm" style="width: 100px;">
                <img src="{{ asset('images/sample-product.jpg') }}" class="card-img-top" alt="Product">
                <div class="card-body p-1 text-center">
                    <small class="text-muted">₹311</small>
                </div>
            </div>
            Repeat product card here
        </div>
    </div> --}}
</div>


        <main class="py-4 container">
            @yield('content')
        </main>

        {{-- Mobile Sticky Footer --}}
        <footer class="mobile-footer d-md-none" style="background-color: #e40046; color: white;">
        <div class="d-flex justify-content-around align-items-center py-2 text-center">

            {{-- 🏠 Home --}}
            <a href="{{ url('/') }}" class="icon-container d-flex flex-column align-items-center text-decoration-none text-white">
                <i class="fas fa-home icon"></i>
                <small>Home</small>
            </a>

            {{-- 👤 Account --}}
            <a href="{{ route('user.account') }}" class="icon-container d-flex flex-column align-items-center text-decoration-none text-white">
                <i class="fas fa-user icon"></i>
                <small>Account</small>
            </a>

            {{-- 🚪 Logout (only for authenticated users) --}}
            @auth
                <a href="#" class="icon-container d-flex flex-column align-items-center text-decoration-none text-white"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt icon text-warning"></i>
                    <small>Logout</small>
                </a>
            @endauth

        </div>
    </footer>



    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script>
        let lastCartCount = null;
        let lastNotificationCount = null;
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

            function fetchCartAndNotifications() {
                // Load notifications
                $.ajax({
                    url: "{{ route('notification') }}",
                    type: "GET",
                    success: function (data) {
                        if (data.status === 'success') {
                            if (data.notification !== lastNotificationCount) {
                                lastNotificationCount = data.notification;
                                $('.clickNotification').text(data.notification);
                            }
                        }
                    }
                });

                // Load cart count
                $.ajax({
                    url: "{{ route('carting') }}",
                    type: "GET",
                    success: function (data) {
                        if (data.status === 'success') {
                            if (data.cart !== lastCartCount) {
                                lastCartCount = data.cart;
                                $('#cart-count, #cart-count-mobile').text(data.cart);
                            }
                        }
                    }
                });
            }
            fetchCartAndNotifications();
            setInterval(fetchCartAndNotifications, 5000);


            
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
