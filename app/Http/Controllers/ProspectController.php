<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = $request->user()->fresh();

        if (! $user->onTrial() && ! $user->isSubscribedAndActive()) {
            return redirect()->route('pricing')->with('error', 'You must subscribe to access the dashboard.');
        }

        $today = now()->toDateString();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();

        $prospects = Prospect::where('user_id', $user->id)->orderBy('next_follow_up')->get();

        $todayFollowUps = $prospects->filter(fn($p) => $p->next_follow_up == $today);
        $overdue = $prospects->filter(fn($p) => $p->next_follow_up < $today && $p->status !== 'signed_up');

        $stageCounts = $prospects->groupBy('stage')->map->count();
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
            'delta' => [],
        ];

        $analytics['delta'] = [
            'followups' => $analytics['weekly_followups'] - $analytics['last_week_followups'],
            'new' => $analytics['new_this_week'] - $analytics['new_last_week'],
        ];

        return view('dashboard', compact('prospects', 'todayFollowUps', 'overdue', 'analytics', 'user'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Prospect::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'notes' => 'nullable|string',
            'stage' => 'required|in:expand_network,relationship_building,ask_question,qualify_pain,expose_tool,follow_up,close',
            'follow_up_date' => 'nullable|date',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['last_contacted'] = now();
        $data['next_follow_up'] = $data['follow_up_date'] ?? now()->addDays(2);

        Prospect::create($data);

        return redirect('/dashboard')->with('success', 'Prospect saved and follow-up scheduled!');
    }

    public function update(Request $request, Prospect $prospect)
    {
        $this->authorize('update', $prospect);

        logger('Incoming update for prospect ID: ' . $prospect->id);
        logger('Request data:', $request->all());

        $data = $request->validate([
            'stage' => 'required|in:expand_network,relationship_building,ask_question,qualify_pain,expose_tool,follow_up,close',
            'last_contacted' => 'required|date',
        ]);

        logger('Validated data:', $data);

        $updated = $prospect->update([
            'stage' => $data['stage'],
            'last_contacted' => $data['last_contacted'],
            'next_follow_up' => $this->calculateNextFollowUp($data['stage']),
        ]);

        logger('Update success: ' . ($updated ? 'yes' : 'no'));

        return redirect()->route('dashboard')->with('success', 'Prospect updated!');
    }

    public function destroy(Request $request, Prospect $prospect)
    {
        $this->authorize('delete', $prospect);

        $prospect->delete();

        return redirect()->route('dashboard')->with('success', 'Prospect deleted.');
    }

    private function calculateNextFollowUp($stage)
    {
        return match ($stage) {
            'expand_network', 'relationship_building', 'qualify_pain', 'expose_tool' => now()->addDays(2),
            'ask_question' => now()->addDay(),
            'follow_up' => now()->addDays(3),
            'close' => null,
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
