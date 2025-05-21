<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Prospect Tracker | Smarter Follow-Up CRM</title>
    <meta name="description" content="A clean, intuitive CRM for network marketers. Track prospects, automate follow-ups, and grow with ease." />
    <meta property="og:title" content="My Prospect Tracker" />
    <meta property="og:description" content="Smarter follow-up CRM for independent sellers and network marketers." />
    <meta property="og:image" content="/og-image.png" />
    <link rel="icon" href="/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Poppins', sans-serif; }
        .bg-primary { background-color: #2563eb; }
        .text-primary { color: #2563eb; }
        .hover\\:bg-primary-dark:hover { background-color: #1e40af; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">

<!-- Header -->
<header class="flex justify-between items-center max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-primary">My Prospect Tracker</h1>
    <div class="space-x-4">
        <a href="/login" class="text-primary font-semibold hover:underline">Login</a>
        <a href="/register" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Get Started</a>
    </div>
</header>

<!-- Hero -->
<section class="text-center py-20 px-4">
    <h2 class="text-4xl font-bold mb-4">Follow-Up CRM That Thinks For You</h2>
    <p class="text-lg text-gray-700 max-w-xl mx-auto mb-6">
        Track your prospects. Build streaks. Stay consistent — no spreadsheets, no stress.
    </p>
    <a href="/register" class="bg-primary text-white px-6 py-3 rounded text-lg hover:bg-primary-dark">Start Free</a>
</section>

<!-- Features -->
<section class="max-w-6xl mx-auto py-16 px-4 grid md:grid-cols-3 gap-10 text-center">
    <div>
        <h3 class="text-xl font-semibold mb-2">Smart Follow-Ups</h3>
        <p class="text-gray-600">Every prospect has a stage. Every day has a plan. No guesswork.</p>
    </div>
    <div>
        <h3 class="text-xl font-semibold mb-2">Pipeline at a Glance</h3>
        <p class="text-gray-600">See where prospects get stuck and move them forward faster.</p>
    </div>
    <div>
        <h3 class="text-xl font-semibold mb-2">Build Daily Streaks</h3>
        <p class="text-gray-600">Track your progress with gamified streaks and motivational nudges.</p>
    </div>
</section>

<!-- Showcase -->
<section class="bg-white py-16 px-4 text-center">
    <h3 class="text-2xl font-bold mb-4">Designed to Keep You Moving</h3>
    <p class="text-gray-700 max-w-xl mx-auto mb-6">My Prospect Tracker gives you momentum — without the mental load.</p>
    <img src="/images/streak-preview.png" alt="App preview" class="mx-auto rounded-lg shadow max-w-md">
</section>

<!-- Call to Action -->
<section class="bg-primary text-white py-16 text-center px-4">
    <h3 class="text-3xl font-bold mb-4">Start Tracking Smarter Today</h3>
    <p class="text-lg mb-6">Join thousands building with less stress and more clarity.</p>
    <a href="/register" class="bg-white text-primary font-semibold px-6 py-3 rounded hover:bg-gray-100">Create Free Account</a>
</section>

<!-- Footer -->
<footer class="text-center text-gray-500 py-10 text-sm">
    &copy; {{ date('Y') }} My Prospect Tracker. All rights reserved.
</footer>

</body>
</html>
