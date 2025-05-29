<x-admin-layout>
    <x-slot name="header">
        <h1 class="fw-semibold fs-4 text-dark">
            {{ __('Prospect Dashboard') }}
        </h1>
        <p class="small text-muted mt-1">
            Role: <strong>{{ ucfirst(Auth::user()->role) }}</strong> |
            Plan: <strong>{{ ucfirst(Auth::user()->plan) }}</strong>
        </p>
    </x-slot>

    <div class="py-4">
        <div class="container">
            @if(Auth::user()->plan === 'free' && !Auth::user()->isAdmin())
                <div class="alert alert-danger border border-danger-subtle mb-4">
                    <strong>Your Pro Plan has ended.</strong>
                    You’re now on the free plan.
                    <a href="{{ route('billing') }}" class="fw-semibold text-decoration-underline">View billing</a> or
                    <a href="{{ route('pricing') }}" class="fw-semibold text-decoration-underline">upgrade again</a>.
                </div>
            @endif

            @if(Auth::user()->inGracePeriod())
                <div class="alert alert-warning border border-warning-subtle mb-4">
                    <div class="fw-semibold mb-1">Your subscription has been cancelled.</div>
                    <p class="mb-2 small text-warning">
                        You have <strong>{{ Auth::user()->daysLeftInGrace() }}</strong> day{{ Auth::user()->daysLeftInGrace() > 1 ? 's' : '' }} left in your grace period.
                    </p>
                    <a href="{{ route('pricing') }}" class="text-decoration-underline fw-semibold">
                        Re-subscribe now
                    </a> to keep Pro access.
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

            {{-- Add New Prospect --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="h5 fw-semibold mb-3">Add New Prospect</h3>
                    <form action="{{ route('prospects.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" placeholder="Name" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="phone" placeholder="Phone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" placeholder="Email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <select name="status" class="form-select">
                                    <option value="new">New</option>
                                    <option value="contacted">Contacted</option>
                                    <option value="invited">Invited</option>
                                    <option value="presented">Presented</option>
                                    <option value="followed_up">Followed Up</option>
                                    <option value="signed_up">Signed Up</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="date" name="follow_up_date" class="form-control">
                            </div>
                            <div class="col-12">
                                <textarea name="notes" placeholder="Notes" class="form-control"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                    </form>
                </div>
            </div>

            {{-- All Prospects --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="h5 fw-semibold mb-3">All Prospects</h3>
                    @forelse($prospects as $prospect)
                        <div class="border rounded p-3 mb-3">
                            <div class="fw-bold">{{ $prospect->name }}</div>
                            <div class="text-muted">Status: {{ ucfirst($prospect->status) }}</div>
                            <p class="mb-1">
                                Next Action:
                                @switch($p->stage)
                                    @case('expand_network') Reach out and connect. @break
                                    @case('relationship_building') Build rapport. @break
                                    @case('ask_question') Ask about their goals. @break
                                    @case('qualify_pain') Find pain points. @break
                                    @case('expose_tool') Share video/landing page. @break
                                    @case('follow_up') Follow up & answer objections. @break
                                    @case('close') Ask for decision. @break
                                @endswitch
                            </p>
                            <p class="mb-0">{{ $prospect->notes }}</p>
                            <small class="text-muted">Follow up: {{ $prospect->follow_up_date ?? 'None' }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No prospects found.</p>
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
                <div class="col-md-6">
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

                <div class="col-md-6">
                    <div class="alert alert-warning p-4 rounded">
                        <p class="fw-semibold">Focus Zone</p>
                        <p class="mb-1">
                            You have the most people stuck in:
                            <strong>{{ ucwords(str_replace('_', ' ', $analytics['focus_zone']['stage'] ?? 'unknown')) }}</strong>
                        </p>
                        <p class="small">Try moving a few of them forward today.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info p-4 rounded text-center">
                        <h5 class="fw-bold mb-2">🔥 Your Streak</h5>
                        <p class="display-6 mb-2">{{ Auth::user()->streak }} day{{ Auth::user()->streak == 1 ? '' : 's' }}</p>

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
        </div>
    </div>
</x-admin-layout>
