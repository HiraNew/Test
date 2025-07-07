@section('cdn-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        footer {
            background-color: #52021a;
        }

        .footer-heading {
            color: #ffc107;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-link-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-link {
            display: block;
            color: #ffffffcc;
            margin-bottom: 8px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .footer-social a {
            color: #ffc107;
            font-size: 1.2rem;
            margin-right: 12px;
            transition: transform 0.2s ease;
        }

        .footer-social a:hover {
            transform: scale(1.2);
            color: #ffffff;
        }

        .footer-bottom a {
            color: #ffc107;
            text-decoration: none;
            margin: 0 6px;
            transition: color 0.3s ease;
        }

        .footer-bottom a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .footer-heading {
                font-size: 1rem;
            }
            .footer-social a {
                font-size: 1rem;
                margin: 0 6px;
            }
        }
    </style>
@endsection

<footer class="text-white pt-5 border-top w-100">
    <div class="container">
        <div class="row text-center text-lg-start">
            <!-- Shop -->
            <div class="col-lg-3 col-sm-6 mb-4">
                <h6 class="footer-heading">Shop</h6>
                <ul class="footer-link-list">
                    <li><a href="#" class="footer-link">All Products</a></li>
                    <li><a href="#" class="footer-link">Categories</a></li>
                    <li><a href="#" class="footer-link">Deals & Offers</a></li>
                    <li><a href="#" class="footer-link">Wishlist</a></li>
                </ul>
            </div>

            <!-- Study Resources -->
            <div class="col-lg-3 col-sm-6 mb-4">
                <h6 class="footer-heading">Study Resources</h6>
                <ul class="footer-link-list">
                    <li><a href="#" class="footer-link">Notes</a></li>
                    <li><a href="#" class="footer-link">E-books</a></li>
                    <li><a href="#" class="footer-link">Mock Tests</a></li>
                    <li><a href="#" class="footer-link">Quiz</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-3 col-sm-6 mb-4">
                <h6 class="footer-heading">Support</h6>
                <ul class="footer-link-list">
                    <li><a href="#" class="footer-link">FAQs</a></li>
                    <li><a href="#" class="footer-link">Contact Us</a></li>
                    <li><a href="#" class="footer-link">Returns</a></li>
                    <li><a href="#" class="footer-link">Help Center</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div class="col-lg-3 col-sm-6 mb-4">
                <h6 class="footer-heading">Company</h6>
                <ul class="footer-link-list">
                    <li><a href="#" class="footer-link">About Us</a></li>
                    <li><a href="#" class="footer-link">Blog</a></li>
                    <li><a href="#" class="footer-link">Terms</a></li>
                    <li><a href="#" class="footer-link">Privacy</a></li>
                </ul>
                <div class="footer-social mt-3">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom text-center pt-3 mt-4 border-top border-light">
            <small class="d-block mb-2">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
            <div>
                <a href="{{ route('partner.login') }}">Join Us</a> |
                <a href="{{ route('vendor.login') }}">Join As Seller</a>
            </div>
        </div>
    </div>
</footer>
