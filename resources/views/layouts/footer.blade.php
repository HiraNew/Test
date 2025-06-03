<footer class="footer bg-light text-dark pt-4 mt-1 border-top">
    <div class="container">
        <div class="row">
            <!-- Shop -->
            <div class="col-md-3 col-6 mb-3">
                <h6 class="fw-bold">Shop</h6>
                <ul class="list-unstyled">
                    {{-- {{ route('products') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">All Products</a></li>
                    {{-- {{ route('categories') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Categories</a></li>
                    {{-- {{ route('offers') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Deals & Offers</a></li>
                    {{-- {{ route('wishlist.index') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Wishlist</a></li>
                </ul>
            </div>

            <!-- Study Materials -->
            <div class="col-md-3 col-6 mb-3">
                <h6 class="fw-bold">Study Resources</h6>
                <ul class="list-unstyled">
                    {{-- {{ route('notes') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Notes</a></li>
                    {{-- {{ route('ebooks') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">E-books</a></li>
                    {{-- {{ route('mocktests') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Mock Tests</a></li>
                    {{-- {{ route('quiz') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Quiz</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-md-3 col-6 mb-3">
                <h6 class="fw-bold">Support</h6>
                <ul class="list-unstyled">
                    {{-- {{ route('faq') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">FAQs</a></li>
                    {{-- {{ route('contact') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Contact Us</a></li>
                    {{-- {{ route('returns') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Returns</a></li>
                    {{-- {{ route('help') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Help Center</a></li>
                </ul>
            </div>

            <!-- About & Social -->
            <div class="col-md-3 col-6 mb-3">
                <h6 class="fw-bold">Company</h6>
                <ul class="list-unstyled">
                    {{-- {{ route('about') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">About Us</a></li>
                    {{-- {{ route('blog') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Blog</a></li>
                    {{-- {{ route('terms') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Terms</a></li>
                    {{-- {{ route('privacy') }} --}}
                    <li><a href="#" class="text-decoration-none text-dark">Privacy</a></li>
                </ul>
                <div class="mt-2">
                    <a href="#" class="me-2 text-dark"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="me-2 text-dark"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="me-2 text-dark"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-dark"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>

        <hr>
        <div class="text-center pb-3">
            <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
        </div>
    </div>
</footer>
