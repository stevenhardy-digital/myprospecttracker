<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>@yield('title', 'My Prospect Tracker')</title>
    <meta name="description" content="A clean, intuitive prospect tracker built for modern network marketers. Track conversations, automate follow-ups, and build streaks that drive consistent results." />
    <meta property="og:title" content="My Prospect Tracker" />
    <meta property="og:description" content="Smart follow-up CRM designed for network marketers. Track prospects, build daily consistency, and never miss a follow-up again." />
    <meta property="og:image" content="/og-image.png" />
    <meta name="keywords" content="prospect tracker, follow-up CRM, network marketing app, daily sales tracker, encrypted contact manager" />
    <link rel="canonical" href="https://myprospecttracker.com/" />
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.umd.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])   

    @stack('styles')
</head>


<body class="boxed_wrapper">

<!-- preloader -->
<div class="preloader"></div>

<!-- Header -->
@include('partials.header-logged-in')
@if (isset($header))
    <div class="container py-4">
        {{ $header }}
    </div>
@endif
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
@include('partials.footer')

<!-- Compiled JS (Laravel Mix) -->
<script src="{{ mix('js/all.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>

