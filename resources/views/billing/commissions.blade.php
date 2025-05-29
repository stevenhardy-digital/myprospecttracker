<x-admin-layout>
    <div class="container py-5">
        <x-slot name="header">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('My Commissions') }}
            </h2>
            <p class="small text-muted mt-1">
                Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong> |
                Plan: <strong>{{ ucfirst(Auth::user()->plan) }}</strong>
            </p>
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
