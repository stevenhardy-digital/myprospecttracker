<x-admin-layout>
    <x-slot name="header">
        <h1 class="fw-semibold fs-4 text-dark">{{ __('Billing History') }}</h1>
        <p class="small text-muted mt-1">
            Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong> |
            Plan: <strong>{{ ucfirst(Auth::user()->plan) }}</strong>
        </p>
    </x-slot>

    <div class="py-4">
        <div class="container">

            <div class="card mb-4 shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-primary mb-3">📜 {{ __('Your Invoices') }}</h3>
                    <p class="small text-muted mb-4">
                        {{ __('Below is a list of all invoices generated for your account. You can download each PDF as needed.') }}
                    </p>

                    @if($invoices->isEmpty())
                        <div class="alert alert-secondary mt-3">
                            {{ __('No invoices yet.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ __('Date') }}</th>
                                    <th scope="col">{{ __('Amount') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Download') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                                        <td>{{ $invoice->total() }}</td>
                                        <td>
                                            @if($invoice->paid)
                                                <span class="badge bg-success text-white">{{ __('Paid') }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ __('Unpaid') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a
                                                href="{{ $invoice->asStripeInvoice()->invoice_pdf }}"
                                                class="btn btn-sm btn-outline-primary"
                                                target="_blank"
                                                rel="noopener"
                                            >
                                                {{ __('Download') }}
                                            </a>
                                        </td>
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
