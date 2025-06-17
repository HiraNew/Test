<style>
    .footer-link {
    display: block;
    color: white;
    text-decoration: none;
    padding: 4px 0;
    transition: color 0.3s ease, transform 0.3s ease;
}
.footer-link:hover {
    color: #FFD700;
    transform: translateX(5px);
}
   
</style>
<footer class="bg-black text-white pt-5 border-top w-100">
    <div class="row g-0 px-4 px-md-5">
        <!-- Shop -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <h6 class="fw-bold text-uppercase border-bottom pb-2">Shop</h6>
            <ul class="list-unstyled">
                <li><a href="#" class="footer-link">All Products</a></li>
                <li><a href="#" class="footer-link">Categories</a></li>
                <li><a href="#" class="footer-link">Deals & Offers</a></li>
                <li><a href="#" class="footer-link">Wishlist</a></li>
            </ul>
        </div>

        <!-- Study Resources -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <h6 class="fw-bold text-uppercase border-bottom pb-2">Study Resources</h6>
            <ul class="list-unstyled">
                <li><a href="#" class="footer-link">Notes</a></li>
                <li><a href="#" class="footer-link">E-books</a></li>
                <li><a href="#" class="footer-link">Mock Tests</a></li>
                <li><a href="#" class="footer-link">Quiz</a></li>
            </ul>
        </div>

        <!-- Support -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <h6 class="fw-bold text-uppercase border-bottom pb-2">Support</h6>
            <ul class="list-unstyled">
                <li><a href="#" class="footer-link">FAQs</a></li>
                <li><a href="#" class="footer-link">Contact Us</a></li>
                <li><a href="#" class="footer-link">Returns</a></li>
                <li><a href="#" class="footer-link">Help Center</a></li>
            </ul>
        </div>

        <!-- Company & Social -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <h6 class="fw-bold text-uppercase border-bottom pb-2">Company</h6>
            <ul class="list-unstyled">
                <li><a href="#" class="footer-link">About Us</a></li>
                <li><a href="#" class="footer-link">Blog</a></li>
                <li><a href="#" class="footer-link">Terms</a></li>
                <li><a href="#" class="footer-link">Privacy</a></li>
            </ul>
            <div class="mt-3">
                <a href="#" class="text-white me-3 fs-5"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white me-3 fs-5"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white me-3 fs-5"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white fs-5"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="col-12">
            <hr class="border-secondary mt-0">
            <div class="text-center pb-3">
                <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
            </div>
            <div class="text-center pb-3">
                <a href="{{route('partner.login')}}" class="footer-link">Join Us</a>
            </div>

        </div>
    </div>
</footer>
