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
    @vite([
        'resources/assets/css/bootstrap.css',
        'resources/assets/css/style.css',
        'resources/assets/css/responsive.css',
        'resources/assets/css/font-awesome-all.css',
        'resources/assets/css/animate.css',
        'resources/assets/css/flaticon.css',
        'resources/assets/css/jquery.fancybox.min.css',
        'resources/assets/css/owl.css',
    ])

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
        @include('partials.footer')

        <!-- Scroll to top -->
        <button class="scroll-top scroll-to-target" data-target="html">
            <span class="fa fa-arrow-up"></span>
        </button>

        <!-- Scripts -->
        <script src="{{ mix('js/all.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')

    </body>
</html>
