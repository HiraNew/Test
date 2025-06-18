<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Share Location</title>
    <style>
        /* Reset and basic styling */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            max-width: 400px;
            width: 100%;
            padding: 30px 40px;
            text-align: center;
            animation: fadeInUp 1s ease forwards;
        }
        h3 {
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 1.4rem;
        }
        p.success {
            background-color: #28a745a8;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
            box-shadow: 0 0 15px #28a745a8;
        }
        button {
            background: linear-gradient(45deg, #ff6a00, #ee0979);
            border: none;
            color: white;
            font-size: 1.1rem;
            padding: 15px 25px;
            border-radius: 50px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 15px rgba(255, 105, 135, 0.6);
            user-select: none;
        }
        button:hover {
            background: linear-gradient(45deg, #ee0979, #ff6a00);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(255, 105, 135, 0.9);
        }
        button:active {
            transform: scale(0.95);
        }
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 20px 25px;
            }
            h3 {
                font-size: 1.1rem;
            }
            button {
                width: 100%;
                padding: 15px 0;
                font-size: 1rem;
            }
        }
    </style>
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;
                    document.getElementById('locationForm').submit();
                }, function(error) {
                    alert('Location access denied or unavailable.');
                });
            } else {
                alert('Geolocation not supported');
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h3>Please share your location for order ID: {{ $orderid }}</h3>

        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        <form id="locationForm" method="POST" action="{{ route('location.store') }}">
            @csrf
            <input type="hidden" name="orderid" value="{{ $orderid }}">
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
            <button type="button" onclick="getLocation()">Share My Location</button>
        </form>
    </div>
</body>
</html>
