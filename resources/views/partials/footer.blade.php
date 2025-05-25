<footer class="main-footer bg-light pt-5 pb-3 border-top mt-0 mt-md-5">
    <div class="container">

        <!-- Flex row to force spacing -->
        <div class="row d-flex justify-content-between align-items-start">

            <!-- Logo & Description -->
            <div class="col-md-6">
                <a href="/" class="navbar-brand fw-bold text-primary h5 m-0">
                    <img src="images/mpt-logo.png" alt="My Prospect Tracker" class="img-fluid rounded">
                </a>
                <p class="small text-muted mt-2 mb-0">
                    A smarter way to manage your follow-ups.<br>
                    Built for focus, flow, and freedom.
                </p>
            </div>

            <!-- Quick Links: fully right-aligned -->
            <div class="col-md-4 text-end mt-4 mt-md-0">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled small">
                    <li><a href="/" class="text-muted text-decoration-none">Home</a></li>
                    <li><a href="#features" class="text-muted text-decoration-none">Features</a></li>
                    <li><a href="#pricing" class="text-muted text-decoration-none">Pricing</a></li>
                    <li><a href="#contact" class="text-muted text-decoration-none">Contact</a></li>
                </ul>
            </div>

        </div>

        <hr class="my-4">

        <div class="text-center small text-muted">
            &copy; 2025 My Prospect Tracker. All rights reserved.
        </div>
    </div>
</footer>

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggler = document.querySelector(".navbar-toggler");
            const menu = document.querySelector("#mainNavbar");

            if (toggler && menu) {
                toggler.addEventListener("click", function () {
                    menu.classList.toggle("show");
                });
            }
        });
    </script>
@endsection
