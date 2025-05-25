@extends('layouts.guest')

@section('title', 'Thank You – My Prospect Tracker')

@section('content')
    <section class="py-5 bg-light text-center">
        <div class="container">
            <div class="mx-auto" style="max-width: 600px;">
                <img src="{{ asset('images/mpt-logo.png') }}" alt="My Prospect Tracker" class="mb-4" style="width: 80px;">

                <h1 class="mb-3 text-primary fw-bold">You're on the list!</h1>
                <p class="text-muted mb-4">
                    Thanks for joining the waitlist. Check your email to confirm your subscription — we’ll let you know as soon as we launch!
                </p>

                <a href="/" class="btn btn-outline-primary rounded-pill px-4">
                    Back to Home
                </a>
            </div>
        </div>
    </section>
@endsection
