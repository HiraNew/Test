<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }
        .sidebar {
            height: 100vh;
            background-color: #2d3748;
            color: white;
            padding-top: 1rem;
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
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @if (isset(Auth::guard('vendor')->user()->name))
        <div class="col-md-2 sidebar">
            @include('Vendor.partials.sidebar')
        </div>
        {{-- @else
            <h5>Not Loggedin Yet.</h5> --}}
        @endif
        <div class="col-md-10 p-4">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
