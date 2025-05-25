<?php

// app/Http/Controllers/WaitlistController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaitlistController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $response = Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/contacts/doubleOptinConfirmation', [
            'email' => $request->email,
            'attributes' => [
                'FIRSTNAME' => $request->input('first_name', 'User'), // Optional
            ],
            'includeListIds' => [(int) env('BREVO_LIST_ID')],
            'templateId' => (int) env('BREVO_DOI_TEMPLATE_ID'),
            'redirectionUrl' => 'https://myprospecttracker.com/thank-you',
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Please check your email to confirm subscription.');
        }

        return back()->withErrors(['email' => 'There was an issue with the subscription.']);
    }
}
