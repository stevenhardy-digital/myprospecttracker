<!-- main-footer -->
<footer class="main-footer bg-gray-50 text-gray-700 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-6">

            <!-- Logo & Text -->
            <div class="flex-1">
                <a href="/" class="inline-block mb-2">
                    <img src="/images/footer-logo.png" alt="My Prospect Tracker" class="h-8">
                </a>
                <p class="text-sm max-w-sm mt-2">
                    A smarter way to manage your follow-ups. Built for focus, flow, and freedom.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold text-base mb-2 text-gray-900">Quick Links</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="/" class="hover:text-primary">Home</a></li>
                    <li><a href="#features" class="hover:text-primary">Features</a></li>
                    <li><a href="#pricing" class="hover:text-primary">Pricing</a></li>
                    <li><a href="#contact" class="hover:text-primary">Contact</a></li>
                </ul>
            </div>
        </div>

        <hr class="border-gray-200 my-6" />

        <div class="text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} My Prospect Tracker. All rights reserved.
        </div>
    </div>
</footer>
