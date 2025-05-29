@auth
    <x-admin-layout>
        <div class="container py-5">
            <div class="alert alert-info text-center mb-4">
                <p class="mb-1">
                    You're logged in as <strong>{{ Auth::user()->name }}</strong>.
                </p>

                @php
                    $currentPlan = Auth::user()->plan;
                @endphp

                <p class="text-muted small mb-2">
                    You’re currently on the <strong>{{ ucfirst($currentPlan) }}</strong> plan.
                </p>

                <p class="mb-0">
                    Manage or change your subscription below:
                </p>
            </div>
            <div class="text-center mb-5">
                <form action="{{ route('subscribe.monthly') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-primary rounded-pill px-4 mb-2" type="submit">
                        Switch to Monthly Plan
                    </button>
                </form>

                <form action="{{ route('subscribe.yearly') }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button class="btn btn-outline-success rounded-pill px-4 mb-2" type="submit">
                        Switch to Yearly Plan
                    </button>
                </form>
            </div>
        </div>
    </x-admin-layout>
@else
    <x-guest-layout>
        @include('partials.pricing-section')
    </x-guest-layout>
@endauth
