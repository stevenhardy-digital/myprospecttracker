<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $prospects = Prospect::orderBy('created_at', 'desc')->get();
        $todayTasks = Prospect::whereDate('follow_up_date', $today)->get();

        return view('prospects.index', compact('prospects', 'todayTasks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'social_handle' => 'nullable|string',
            'pain_points' => 'nullable|string',
            'status' => 'required|in:new,contacted,invited,presented,followed_up,signed_up',
        ]);

        $data['last_contacted'] = now();
        $data['next_follow_up'] = $this->calculateNextFollowUp($data['status']);

        Prospect::create($data);

        return redirect('/dashboard')->with('success', 'Prospect saved and follow-up scheduled!');
    }

    private function calculateNextFollowUp($status)
    {
        return match ($status) {
            'invited', 'new' => now()->addDays(2),
            'contacted' => now()->addDays(3),
            'presented' => now()->addDay(),
            'followed_up' => now()->addDays(5),
            default => null,
        };
    }

}
