<header class="main-header position-sticky top-0 w-100 z-3">
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-3">
        <div class="container d-flex align-items-center justify-content-between">

            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-primary m-0" href="/">
                <img src="/images/mpt-logo.png" alt="My Prospect Tracker" class="img-fluid rounded">
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler border-0 shadow-none p-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="d-flex flex-column justify-content-center" style="width: 24px; height: 18px;">
                    <span style="height: 2px; background-color: #2563eb; width: 100%; margin-bottom: 4px;"></span>
                    <span style="height: 2px; background-color: #2563eb; width: 100%; margin-bottom: 4px;"></span>
                    <span style="height: 2px; background-color: #2563eb; width: 100%;"></span>
                </span>
            </button>

            <!-- Nav & CTA -->
            <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                <ul class="navbar-nav align-items-center mb-2 mb-lg-0">
                    <li class="nav-item px-2"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="{{ route('billing') }}">Billing History</a></li>
                    <li class="nav-item px-2"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>

                <!-- Logout -->
                <div class="mt-3 mt-lg-0 ms-lg-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary rounded-pill px-4 w-100 w-lg-auto">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </nav>
</header>
