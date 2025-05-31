{{-- resources/views/contact.blade.php --}}
<x-guest-layout title="Contact Us | My Prospect Tracker">
    <section class="pt-20 pb-12 bg-white">
        <div class="max-w-3xl mx-auto text-center px-4">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4 text-gray-900 font-heading">
                📨 Contact Us
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                Have a question or feedback? Fill out the form below and we’ll get back to you as soon as possible.
            </p>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="bg-gray-50 pb-20 px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8">
            @if(session('status'))
                <div class="mb-6 px-4 py-3 bg-green-100 border border-green-300 text-green-700 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Name Field --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        {{ __('Name') }}
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Your full name"
                    />
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('Email') }}
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="you@example.com"
                    />
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Message Field --}}
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">
                        {{ __('Message') }}
                    </label>
                    <textarea
                        name="message"
                        id="message"
                        rows="6"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="How can we help you?"
                    >{{ old('message') }}</textarea>
                    @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div>
                    <button
                        type="submit"
                        class="w-full bg-primary text-white px-6 py-3 rounded text-lg font-semibold hover:bg-primary-dark transition"
                    >
                        {{ __('Send Message') }}
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-guest-layout>
