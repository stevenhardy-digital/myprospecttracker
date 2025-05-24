<?php

// app/Http/Controllers/WaitlistController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaitlistController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        $response = Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/contacts', [
            'email' => $email,
            'listIds' => [intval(env('BREVO_LIST_ID'))],
            'updateEnabled' => true
        ]);

        if ($response->successful()) {
            return back()->with('success', 'You’ve joined the waitlist!');
        }

        return back()->withErrors(['email' => 'There was an issue adding you.']);
    }
}
