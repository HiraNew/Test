@section('cdn-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

<footer class="text-white pt-5 border-top w-100" style="background-color: #52021a;">
    <div class="container">
        <div class="row">
            <!-- Shop -->
            <div class="col-lg-3 col-6 mb-4 text-center">
                <h6 class="footer-heading">Shop</h6>
                <ul class="footer-link-list">
                    <li><a href="#" class="footer-link">All Products</a></li>
                    <li><a href="#" class="footer-link">Categories</a></li>
                    <li><a href="#" class="footer-link">Deals & Offers</a></li>
                    <li><a href="#" class="footer-link">Wishlist</a></li>
                </ul>
            </div>

            <!-- Study Resources -->
            <div class="col-lg-3 col-6 mb-4 text-center">
                <h6 class="footer-heading">Study Resources</h6>
                <ul class="footer-link-list">
                    <li><a href="#" class="footer-link">Notes</a></li>
                    <li><a href="#" class="footer-link">E-books</a></li>
                    <li><a href="#" class="footer-link">Mock Tests</a></li>
                    <li><a href="#" class="footer-link">Quiz</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-3 col-6 mb-4 text-center">
                <h6 class="footer-heading">Support</h6>
                <ul class="footer-link-list">
                    <li><a href="#" class="footer-link">FAQs</a></li>
                    <li><a href="#" class="footer-link">Contact Us</a></li>
                    <li><a href="#" class="footer-link">Returns</a></li>
                    <li><a href="#" class="footer-link">Help Center</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div class="col-lg-3 col-6 mb-4 text-center">
                <h6 class="footer-heading">Company</h6>
                <ul class="footer-link-list">
                    <li><a href="#" class="footer-link">About Us</a></li>
                    <li><a href="#" class="footer-link">Blog</a></li>
                    <li><a href="#" class="footer-link">Terms</a></li>
                    <li><a href="#" class="footer-link">Privacy</a></li>
                </ul>
                <div class="footer-social mt-3">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom text-center pt-3">
            <hr class="border-light">
            <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
            <div class="mt-2">
                <a href="{{ route('partner.login') }}">Join Us</a> |
                <a href="{{ route('vendor.login') }}">Join As Seller</a>
            </div>
        </div>
    </div>
</footer>
