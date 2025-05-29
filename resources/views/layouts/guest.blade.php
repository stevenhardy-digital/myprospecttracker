@props([
    'title' => config('app.name', 'My Prospect Tracker'),
    'description' => 'A clean, intuitive prospect tracker built for modern network marketers. Track conversations, automate follow-ups, and build streaks that drive consistent results.'
])
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="/og-image.png">
    <meta name="keywords" content="prospect tracker, follow-up CRM, network marketing app, daily sales tracker, encrypted contact manager">
    <link rel="canonical" href="https://myprospecttracker.com/">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])

    <!-- Structured Data -->
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
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<main>
    {{ $slot }}
</main>

<!-- Footer -->
@include('partials.streamline')
@include('partials.footer')

<!-- Scroll to top -->
<button class="scroll-top scroll-to-target" data-target="html">
    <span class="fa fa-arrow-up"></span>
</button>

<!-- JS Assets -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
<script src="{{ asset('js/jquery.js') }}" defer></script>
<script src="{{ asset('js/owl.js') }}" defer></script>
<script src="{{ asset('js/wow.js') }}" defer></script>
<script src="{{ asset('js/jquery.fancybox.js') }}" defer></script>
<script src="{{ asset('js/tilt.jquery.js') }}" defer></script>
<script src="{{ asset('js/validation.js') }}" defer></script>
<script src="{{ asset('js/script.js') }}" defer></script>

@stack('scripts')
</body>
</html>
