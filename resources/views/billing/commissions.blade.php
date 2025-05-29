<x-admin-layout>
    <div class="container py-5">
        <x-slot name="header">
            <h1 class="fw-semibold fs-4 text-dark">
                {{ __('Prospect Dashboard') }}
            </h1>
            <p class="small text-muted mt-1">
                Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong> |
                Plan: <strong>{{ ucfirst(Auth::user()->plan) }}</strong>
            </p>
            @if(Auth::user()->plan === 'pro' && !Auth::user()->stripe_connect_id)
                <div class="alert alert-danger d-flex justify-content-between align-items-center">
                    <div>
                        <strong>🔴 Action Required:</strong> You must complete your Stripe setup to receive referral payouts.
                    </div>
                    <a href="{{ route('stripe.connect') }}" class="btn btn-sm btn-light fw-bold">
                        Complete Onboarding
                    </a>
                </div>
            @endif
            @if(Auth::user()->stripe_requires_verification)
                <div class="alert alert-danger">
                    Your Stripe account requires verification.
                    <a href="{{ route('stripe.onboarding.retry') }}" class="btn btn-sm btn-danger ms-2">Complete Now</a>
                </div>
            @endif
        </x-slot>

        @if($commissions->isEmpty())
            <p>You haven't earned any commissions yet.</p>
        @else
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Referred User</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Earned At</th>
                </tr>
                </thead>
                <tbody>
                @foreach($commissions as $commission)
                    <tr>
                        <td>{{ $commission->referredUser->email }}</td>
                        <td>${{ number_format($commission->amount, 2) }}</td>
                        <td>{{ ucfirst($commission->status) }}</td>
                        <td>{{ $commission->earned_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-admin-layout>
