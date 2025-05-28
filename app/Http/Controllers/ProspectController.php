<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();

        $prospects = Prospect::orderBy('next_follow_up')->get();
        $todayFollowUps = $prospects->filter(fn($p) => $p->next_follow_up == $today);
        $overdue = $prospects->filter(fn($p) => $p->next_follow_up < $today && $p->status !== 'signed_up');

        $stageCounts = $prospects->groupBy('stage')->map->count();

        // Focus zone: Stage with highest count
        $focusZoneStage = $stageCounts->sortDesc()->keys()->first();
        $focusZoneCount = $stageCounts[$focusZoneStage] ?? 0;

        $analytics = [
            'total' => $prospects->count(),
            'avg_streak' => round($prospects->avg('streak')),
            'weekly_followups' => $prospects->whereBetween('next_follow_up', [$weekStart, $weekEnd])->count(),
            'last_week_followups' => $prospects->whereBetween('next_follow_up', [$lastWeekStart, $lastWeekEnd])->count(),
            'new_this_week' => $prospects->whereBetween('created_at', [$weekStart, $weekEnd])->count(),
            'new_last_week' => $prospects->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count(),
            'stage_counts' => $stageCounts,
            'focus_zone' => [
                'stage' => $focusZoneStage,
                'count' => $focusZoneCount,
            ],
        ];

        $analytics['delta'] = [
            'followups' => $analytics['weekly_followups'] - $analytics['last_week_followups'],
            'new' => $analytics['new_this_week'] - $analytics['new_last_week'],
        ];


        return view('dashboard', compact('prospects', 'todayFollowUps', 'overdue', 'analytics'));
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

    public function update(Request $request, Prospect $prospect)
    {
        $data = $request->validate([
            'status' => 'required',
            'last_contacted' => 'required|date'
        ]);

        $prospect->update([
            'status' => $data['status'],
            'last_contacted' => $data['last_contacted'],
            'next_follow_up' => $this->calculateNextFollowUp($data['status']),
            'stage' => $this->getNextStage($prospect->stage),
            'streak' => $prospect->streak + 1,
        ]);


        return redirect()->route('dashboard')->with('success', 'Prospect updated!');
    }

    private function calculateNextFollowUp($stage)
    {
        return match ($stage) {
            'expand_network', 'relationship_building', 'qualify_pain', 'expose_tool' => now()->addDays(2),
            'ask_question' => now()->addDay(),
            'follow_up' => now()->addDays(3),
            default => null,
        };
    }

    private function getNextStage($currentStage)
    {
        return match ($currentStage) {
            'expand_network' => 'relationship_building',
            'relationship_building' => 'ask_question',
            'ask_question' => 'qualify_pain',
            'qualify_pain' => 'expose_tool',
            'expose_tool' => 'follow_up',
            'follow_up' => 'close',
            default => 'close',
        };
    }

}
