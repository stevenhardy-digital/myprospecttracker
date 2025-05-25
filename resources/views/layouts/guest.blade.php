<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'My Prospect Tracker'))</title>
    <meta name="description"
          content="A clean, intuitive prospect tracker built for modern network marketers. Track conversations, automate follow-ups, and build streaks that drive consistent results."/>
    <meta property="og:title" content="My Prospect Tracker"/>
    <meta property="og:description"
          content="Smart follow-up CRM designed for network marketers. Track prospects, build daily consistency, and never miss a follow-up again."/>
    <meta property="og:image" content="/og-image.png"/>
    <meta name="keywords"
          content="prospect tracker, follow-up CRM, network marketing app, daily sales tracker, encrypted contact manager"/>
    <link rel="canonical" href="https://myprospecttracker.com/"/>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <!-- SEO Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "My Prospect Tracker",
            "url": "https://myprospecttracker.com",
            "logo": "https://myprospecttracker.com/logo.png",
            "sameAs": [
                "https://facebook.com/myprospecttracker",
                "https://instagram.com/myprospecttracker"
            ]
        }
    </script>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SoftwareApplication",
            "name": "My Prospect Tracker",
            "operatingSystem": "Web",
            "applicationCategory": "BusinessApplication",
            "offers": {
                "@type": "Offer",
                "price": "0.00",
                "priceCurrency": "GBP"
            },
            "aggregateRating": {
                "@type": "AggregateRating",
                "ratingValue": "4.8",
                "reviewCount": "134"
            }
        }
    </script>

    @stack('styles')
</head>
    <body class="font-sans text-gray-900 antialiased">

        <!-- Preloader -->
        <div class="preloader"></div>

        <!-- Header -->
        @include('partials.header')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('partials.streamline')
        @include('partials.footer')

        <!-- Scroll to top -->
        <button class="scroll-top scroll-to-target" data-target="html">
            <span class="fa fa-arrow-up"></span>
        </button>
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

            document.querySelectorAll("#mainNavbar .nav-link").forEach(link => {
                link.addEventListener("click", () => {
                    document.querySelector("#mainNavbar").classList.remove("show");
                });
            });

        </script>
        <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/owl.js') }}"></script>
        <script src="{{ asset('js/wow.js') }}"></script>
        <script src="{{ asset('js/bxslider.js') }}"></script>
        <script src="{{ asset('js/circle-progress.js') }}"></script>
{{--        <script src="{{ asset('js/gmaps.js') }}"></script>--}}
        <script src="{{ asset('js/isotope.js') }}"></script>
        <script src="{{ asset('js/jquery.countTo.js') }}"></script>
        <script src="{{ asset('js/jquery.fancybox.js') }}"></script>
        <script src="{{ asset('js/jquery.paroller.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui.js') }}"></script>
        <script src="{{ asset('js/map-helper.js') }}"></script>
        <script src="{{ asset('js/nav-tool.js') }}"></script>
        <script src="{{ asset('js/scrollbar.js') }}"></script>
        <script src="{{ asset('js/tilt.jquery.js') }}"></script>
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>
        @stack('scripts')

    </body>
</html>
