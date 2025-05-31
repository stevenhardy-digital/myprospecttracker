<x-admin-layout>
    <x-slot name="header">
        <h1 class="fw-semibold fs-4 text-dark">{{ __('Prospect Dashboard') }}</h1>
        <p class="small text-muted mt-1">
            Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong> |
            Plan: <strong>{{ ucfirst(Auth::user()->plan) }}</strong>
        </p>

        @if(Auth::user()->plan === 'pro' && !Auth::user()->stripe_connect_id)
            <div class="alert alert-danger d-flex justify-content-between align-items-center mt-3">
                <div>
                    <strong>🔴 Action Required:</strong> You must complete your Stripe setup to receive referral payouts.
                </div>
                <a href="{{ route('stripe.connect') }}" class="btn btn-sm btn-light fw-bold">
                    Complete Onboarding
                </a>
            </div>
        @endif

        @if(Auth::user()->stripe_requires_verification)
            <div class="alert alert-danger mt-3">
                Your Stripe account requires verification.
                <a href="{{ route('stripe.onboarding.retry') }}" class="btn btn-sm btn-danger ms-2">Complete Now</a>
            </div>
        @endif
    </x-slot>

    <div class="py-4">
        <div class="container">

            {{-- Commissions Table Card --}}
            <div class="card mb-4 shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-primary mb-3">💰 {{ __('Your Commissions') }}</h3>
                    <p class="small text-muted mb-4">
                        {{ __('Below is a list of commissions you have earned through referrals.') }}
                    </p>

                    @if($commissions->isEmpty())
                        <div class="alert alert-secondary mt-3">
                            {{ __("You haven't earned any commissions yet.") }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('Referred User') }}</th>
                                    <th scope="col">{{ __('Amount') }}</th>
                                    <th scope="col">{{ __('Payment Status') }}</th>
                                    <th scope="col">{{ __('Commission Status') }}</th>
                                    <th scope="col">{{ __('Earned At') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($commissions as $commission)
                                    <tr>
                                        <td>{{ $commission->referredUser->email }}</td>
                                        <td>${{ number_format($commission->amount, 2) }}</td>
                                        <td>
                                            @if($commission->referredUser->payment_status === 'paid')
                                                <span class="badge bg-success text-white">
                                                        {{ __('Paid') }}
                                                    </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                        {{ __('Unpaid') }}
                                                    </span>
                                            @endif
                                        </td>
                                        <td>{{ ucfirst($commission->status) }}</td>
                                        <td>{{ $commission->earned_at->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
