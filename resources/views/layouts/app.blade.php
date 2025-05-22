<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>@yield('title', 'Appway - HTML5 Template')</title>
    <meta name="description" content="A clean, intuitive prospect tracker built for modern network marketers. Track conversations, automate follow-ups, and build streaks that drive consistent results." />
    <meta property="og:title" content="My Prospect Tracker" />
    <meta property="og:description" content="Smart follow-up CRM designed for network marketers. Track prospects, build daily consistency, and never miss a follow-up again." />
    <meta property="og:image" content="/og-image.png" />
    <meta name="keywords" content="prospect tracker, follow-up CRM, network marketing app, daily sales tracker, encrypted contact manager" />
    <link rel="canonical" href="https://myprospecttracker.com/" />
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&display=swap" rel="stylesheet">

    <!-- Compiled CSS (Laravel Mix) -->
    <link rel="stylesheet" href="{{ mix('css/all.css') }}">
    
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


<body class="boxed_wrapper">

<!-- preloader -->
<div class="preloader"></div>

<!-- Header -->
@include('partials.header')

<!-- Mobile Menu -->
@include('partials.mobile-menu')

<!-- Page Content -->
@yield('content')

<!-- Footer -->
@include('partials.footer')

<!-- Scroll to top -->
<button class="scroll-top scroll-to-target" data-target="html">
    <span class="fa fa-arrow-up"></span>
</button>

<!-- Compiled JS (Laravel Mix) -->
<script src="{{ mix('js/all.js') }}"></script>

@stack('scripts')

</body>
</html>

