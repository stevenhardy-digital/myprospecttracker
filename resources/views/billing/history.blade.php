<x-admin-layout>
    <div class="container py-5">
        <x-slot name="header">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Billing History') }}
            </h2>
            <p class="small text-muted mt-1">
                Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong> |
                Plan: <strong>{{ ucfirst(Auth::user()->plan) }}</strong>
            </p>
        </x-slot>

        @if($invoices->isEmpty())
            <div class="alert alert-secondary mt-4">No invoices yet.</div>
        @else
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Download</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                            <td>{{ $invoice->total() }}</td>
                            <td>
                                    <span class="badge {{ $invoice->paid ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $invoice->paid ? 'Paid' : 'Unpaid' }}
                                    </span>
                            </td>
                            <td>
                                <a href="{{ $invoice->download() }}" class="btn btn-sm btn-outline-primary">
                                    Download
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-admin-layout>
