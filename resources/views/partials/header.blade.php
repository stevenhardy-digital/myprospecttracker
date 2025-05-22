<header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center">
            <img src="/images/logo.svg" alt="My Prospect Tracker" class="h-8">
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex space-x-6 text-sm font-medium text-gray-700">
            <a href="/" class="hover:text-primary">Home</a>
            <a href="#features" class="hover:text-primary">Features</a>
            <a href="#pricing" class="hover:text-primary">Pricing</a>
            <a href="#contact" class="hover:text-primary">Contact</a>
        </nav>

        <!-- CTA -->
        <div class="hidden md:block">
            <a href="/register" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark text-sm font-semibold">
                Get Started
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="mobile-menu-toggle" class="text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Nav -->
    <div id="mobile-menu" class="hidden md:hidden bg-white px-4 pb-4">
        <nav class="flex flex-col space-y-2 text-sm text-gray-700">
            <a href="/" class="hover:text-primary">Home</a>
            <a href="#features" class="hover:text-primary">Features</a>
            <a href="#pricing" class="hover:text-primary">Pricing</a>
            <a href="#contact" class="hover:text-primary">Contact</a>
            <a href="/register" class="mt-2 inline-block bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark text-center font-semibold">Get Started</a>
        </nav>
    </div>
</header>
