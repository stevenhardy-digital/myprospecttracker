{{-- resources/views/contact.blade.php --}}
<x-guest-layout title="Contact Us | My Prospect Tracker">
    <div class="container py-5">
        <!-- Hero / Page Header -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 fw-bold mb-3">✉️ Contact Us</h1>
                <p class="text-secondary fs-5">
                    Have a question, feedback, or need assistance?<br>
                    Fill out the form below and we’ll get back to you shortly.
                </p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                {{-- Success Flash Message --}}
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('contact.send') }}" method="POST" novalidate>
                            @csrf

                            {{-- Optional Username Field --}}
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    Username <small class="text-muted">(optional)</small>
                                </label>
                                <input
                                    type="text"
                                    name="username"
                                    id="username"
                                    value="{{ old('username') }}"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Your MyProspectTracker username"
                                >
                                @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            {{-- Name Field --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name') }}"
                                    required
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Your full name"
                                >
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            {{-- Email Field --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    required
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="you@example.com"
                                >
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            {{-- Message Field --}}
                            <div class="mb-4">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea
                                    name="message"
                                    id="message"
                                    rows="6"
                                    required
                                    class="form-control @error('message') is-invalid @enderror"
                                    placeholder="How can we help you?"
                                >{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
