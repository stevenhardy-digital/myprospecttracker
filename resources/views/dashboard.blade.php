<x-admin-layout>
    @php
        function getStageColor($stage) {
            return match ($stage) {
                'expand_network' => 'bg-info text-white',
                'relationship_building' => 'bg-primary text-white',
                'ask_question' => 'bg-warning text-dark',
                'qualify_pain' => 'bg-danger text-white',
                'expose_tool' => 'bg-secondary text-white',
                'follow_up' => 'bg-success text-white',
                'close' => 'bg-dark text-white',
                default => 'bg-light text-dark',
            };
        }
    @endphp
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

    <div class="py-4">
        <div class="container">
            {{-- Trial Notice --}}
            @if(Auth::user()->onTrial())
                <div class="alert alert-info mb-4">
                    You're currently on a <strong>14-day trial</strong>. You have
                    <strong>{{ Auth::user()->daysLeftInTrial() }}</strong>
                    day{{ Auth::user()->daysLeftInTrial() !== 1 ? 's' : '' }} left.
                    <br>
                    After this, your plan will auto-renew unless canceled.
                </div>
            @endif
            @if(Auth::user()->plan === 'free' && !Auth::user()->isAdmin())
                <div class="alert alert-danger border border-danger-subtle mb-4">
                    <strong>Your Pro Plan has ended.</strong>
                    You’re now on the free plan.
                    <a href="{{ route('billing') }}" class="fw-semibold text-decoration-underline">View billing</a> or
                    <a href="{{ route('pricing') }}" class="fw-semibold text-decoration-underline">upgrade again</a>.
                </div>
            @endif

            @if(Auth::user()->inGracePeriod())
                <div
                    class="alert alert-warning d-flex align-items-start gap-3 border border-warning-subtle shadow-sm mb-4">
                    <i class="bi bi-exclamation-triangle-fill fs-3 text-warning mt-1"></i>
                    <div>
                        <div class="fw-bold mb-1">⚠️ Your Pro subscription has been cancelled.</div>
                        <p class="mb-2 small text-warning">
                            You have <strong>{{ Auth::user()->daysLeftInGrace() }}</strong>
                            day{{ Auth::user()->daysLeftInGrace() > 1 ? 's' : '' }} left in your grace period.
                        </p>
                        <a href="{{ route('pricing') }}" class="btn btn-sm btn-warning fw-semibold">
                            Re-subscribe Now
                        </a>
                    </div>
                </div>
            @endif
            <p class="text-muted">
                Share My Prospect Tracker:
                <span class="font-monospace">{{ url('/r/' . Auth::user()->username) }}</span>
            </p>

            {{-- Today’s Follow-ups --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="h5 fw-semibold mb-3">Today’s Follow-ups</h3>
                    <ul class="ps-3">
                        @forelse($todayFollowUps as $task)
                            <li>{{ $task->name }} – {{ ucfirst($task->status) }}</li>
                        @empty
                            {{-- Leave intentionally empty --}}
                        @endforelse
                    </ul>

                    @if($todayFollowUps->isEmpty())
                        <div class="alert alert-warning mt-3">
                            You're all caught up! Go find 3 new people to prospect today.
                        </div>
                    @endif
                </div>
            </div>
            {{-- 🔵 Add New Prospect --}}
            <div class="card mb-4 shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-primary mb-3">➕ Add New Prospect</h3>
                    <form action="{{ route('prospects.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" placeholder="Full Name" required class="form-control form-control-lg">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="phone" placeholder="Phone (optional)" class="form-control form-control-lg">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="social_handle" placeholder="Social Handle (optional)" class="form-control form-control-lg">
                            </div>
                            <div class="col-md-6">
                                <select name="stage" class="form-select form-select-lg" required>
                                    <option value="relationship_building">💬 Relationship Building</option>
                                    <option value="ask_question">❓ Ask Question</option>
                                    <option value="qualify_pain">💢 Qualify Pain</option>
                                    <option value="expose_tool">🛠️ Expose Tool</option>
                                    <option value="follow_up">🔁 Follow Up</option>
                                    <option value="close">✅ Close</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="follow_up_date" class="form-control form-control-lg" placeholder="Next Follow-Up Date (optional)">
                            </div>
                            <div class="col-12">
                                <textarea name="pain_points" rows="3" placeholder="Pain points or notes (optional)" class="form-control"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg mt-3 w-100 fw-semibold">Save Prospect</button>
                    </form>
                </div>
            </div>
            {{-- 🗂️ All Prospects --}}
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-dark mb-3">📋 All Prospects</h3>
                    @forelse($prospects as $prospect)
                        <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="fw-bold fs-5">{{ $prospect->name }}</div>
                                    <span class="badge {{ getStageColor($prospect->stage) }}">
                                        {{ ucfirst(str_replace('_', ' ', $prospect->stage)) }}
                                    </span>
                                </div>
                                <div class="d-flex gap-2">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProspectModal{{ $prospect->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('prospects.destroy', $prospect) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this prospect?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if($prospect->social_handle)
                                <div class="mb-1 text-muted"><i class="bi bi-person-circle me-1"></i> {{ $prospect->social_handle }}</div>
                            @endif

                            @if($prospect->pain_points)
                                <div class="mb-1 text-muted small"><strong>Pain:</strong> {{ $prospect->pain_points }}</div>
                            @endif

                            <div class="small text-muted">
                                <i class="bi bi-calendar-event me-1"></i>
                                Last Contacted: {{ \Carbon\Carbon::parse($prospect->last_contacted)->toFormattedDateString() }}
                                |
                                Next Follow-up: {{ $prospect->next_follow_up ? \Carbon\Carbon::parse($prospect->next_follow_up)->toFormattedDateString() : 'N/A' }}
                            </div>
                        </div>

                        <!-- Modal Edit Form -->
                        <div class="modal fade" id="editProspectModal{{ $prospect->id }}" tabindex="-1" aria-labelledby="editLabel{{ $prospect->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form class="modal-content" method="POST" action="{{ route('prospects.update', $prospect) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editLabel{{ $prospect->id }}">Edit {{ $prospect->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Stage</label>
                                            <select name="stage" class="form-select" required>
                                                <option value="relationship_building" {{ $prospect->stage === 'relationship_building' ? 'selected' : '' }}>💬 Relationship Building</option>
                                                <option value="ask_question" {{ $prospect->stage === 'ask_question' ? 'selected' : '' }}>❓ Ask Question</option>
                                                <option value="qualify_pain" {{ $prospect->stage === 'qualify_pain' ? 'selected' : '' }}>💢 Qualify Pain</option>
                                                <option value="expose_tool" {{ $prospect->stage === 'expose_tool' ? 'selected' : '' }}>🛠️ Expose Tool</option>
                                                <option value="follow_up" {{ $prospect->stage === 'follow_up' ? 'selected' : '' }}>🔁 Follow Up</option>
                                                <option value="close" {{ $prospect->stage === 'close' ? 'selected' : '' }}>✅ Close</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Last Contacted</label>
                                            <input type="date" name="last_contacted" class="form-control"
                                                   value="{{ \Carbon\Carbon::parse($prospect->last_contacted)->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center">
                            No prospects yet. Start adding now!
                        </div>
                    @endforelse
                </div>
            </div>
            {{-- Analytics Overview --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="h5 fw-bold mb-4">Analytics Overview</h2>

                    <div class="row text-center mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="bg-light p-3 rounded">
                                <p class="text-muted mb-1">Total Prospects</p>
                                <p class="h4">{{ $analytics['total'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="bg-light p-3 rounded">
                                <p class="text-muted mb-1">Avg. Streak</p>
                                <p class="h4">{{ $analytics['avg_streak'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="bg-light p-3 rounded">
                                <p class="text-muted mb-1">Follow-Ups This Week</p>
                                <p class="h4">{{ $analytics['weekly_followups'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="bg-light p-3 rounded">
                                <p class="text-muted mb-1">New This Week</p>
                                <p class="h4">{{ $analytics['new_this_week'] }}</p>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-semibold mb-3">Stage Distribution</h5>
                    <div class="row">
                        @foreach($analytics['stage_counts'] as $stage => $count)
                            <div class="col-6 col-md-3 mb-3">
                                <div class="bg-info bg-opacity-10 p-2 rounded text-center">
                                    <p class="mb-1 text-info">{{ ucwords(str_replace('_', ' ', $stage)) }}</p>
                                    <p class="h5 mb-0 text-primary">{{ $count }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Motivation Delta and Focus Zone --}}
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="alert alert-success p-4 rounded">
                        <p class="fw-semibold">This week vs. last week:</p>
                        <ul class="mb-2">
                            @php
                                $followupDelta = $analytics['delta']['followups'] ?? 0;
                                $newDelta = $analytics['delta']['new'] ?? 0;
                            @endphp
                            <li>{{ $followupDelta >= 0 ? '+' : '' }}{{ $followupDelta }} follow-ups this week</li>
                            <li>{{ $newDelta >= 0 ? '+' : '' }}{{ $newDelta }} new prospects added</li>
                        </ul>
                        @if($followupDelta > 0 || $newDelta > 0)
                            <p class="small text-success">You're making progress! Keep it up!</p>
                        @else
                            <p class="small text-danger">Momentum dipped. Let’s pick it back up!</p>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="alert alert-warning p-4 rounded">
                        <p class="fw-semibold">Focus Zone</p>
                        <p class="mb-1">
                            You have the most people stuck in:
                            <strong>{{ ucwords(str_replace('_', ' ', $analytics['focus_zone']['stage'] ?? 'unknown')) }}</strong>
                        </p>
                        <p class="small">Try moving a few of them forward today.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-info p-4 rounded text-center">
                        <h5 class="fw-bold mb-2">🔥 Your Streak</h5>
                        <p class="display-6 mb-2">{{ Auth::user()->streak }}
                            day{{ Auth::user()->streak == 1 ? '' : 's' }}</p>

                        @if(Auth::user()->streak >= 7)
                            <p class="small text-success mb-0">Amazing consistency! You’re building real momentum! 💪</p>
                        @elseif(Auth::user()->streak >= 3)
                            <p class="small text-primary mb-0">Nice streak! Keep showing up! 👏</p>
                        @else
                            <p class="small text-muted mb-0">Just getting started — let’s build that streak! 🚀</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="alert alert-primary p-4 rounded text-center">
                        <h5 class="fw-bold mb-2">💸 Your Commissions</h5>

                        @php
                            $earnedThisWeek = Auth::user()->commissions()
                                ->whereHas('referredUser', fn($q) => $q->where('payment_status', 'paid'))
                                ->whereBetween('earned_at', [now()->startOfWeek(), now()->endOfWeek()])
                                ->sum('amount');

                            $nextPayout = $earnedThisWeek;

                            $nextMonday = now()->next('Monday');

                            $predictedNext = Auth::user()->commissions()
                                ->whereHas('referredUser', fn($q) => $q->where('payment_status', 'paid'))
                                ->whereBetween('earned_at', [now()->startOfWeek()->addWeek(), now()->endOfWeek()->addWeek()])
                                ->sum('amount');
                        @endphp

                        <p class="mb-1">
                            <strong>✅ Paid so far this week:</strong><br>
                            <span class="display-6">${{ number_format($earnedThisWeek, 2) }}</span>
                        </p>

                        <p class="mb-1">
                            <strong>📅 Next payout on {{ $nextMonday->format('M j') }}:</strong><br>
                            <span class="fs-5">${{ number_format($nextPayout, 2) }}</span>
                        </p>

                        <p class="mb-0 small text-muted">
                            🎯 Est. next week’s commission: ${{ number_format($predictedNext, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
