<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Prospect Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <p class="text-sm text-gray-600">
                Share My Prospect Tracker:
                <span class="font-mono">{{ url('/register?ref=' . Auth::user()->username) }}</span>
            </p>
            {{-- Today’s Follow-ups --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Today’s Follow-ups</h3>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                    @forelse($todayTasks as $task)
                        <li>{{ $task->name }} – {{ ucfirst($task->status) }}</li>
                    @endforelse
                </ul>
                @if($todayTasks->isEmpty())
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded mt-4">
                        You're all caught up! Go find 3 new people to prospect today.
                    </div>
                @endif
            </div>

            {{-- Add New Prospect --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Add New Prospect</h3>
                <form action="{{ route('prospects.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="name" placeholder="Name" required class="form-input w-full">
                        <input type="text" name="phone" placeholder="Phone" class="form-input w-full">
                        <input type="email" name="email" placeholder="Email" class="form-input w-full">
                        <select name="status" class="form-select w-full">
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="invited">Invited</option>
                            <option value="presented">Presented</option>
                            <option value="followed_up">Followed Up</option>
                            <option value="signed_up">Signed Up</option>
                        </select>
                        <input type="date" name="follow_up_date" class="form-input w-full">
                    </div>
                    <textarea name="notes" placeholder="Notes" class="form-textarea w-full mt-2"></textarea>
                    <button type="submit" class="btn btn-primary mt-4">Add</button>
                </form>
            </div>

            {{-- All Prospects --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">All Prospects</h3>
                <div class="space-y-4">
                    @forelse($prospects as $prospect)
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                            <div class="font-bold text-gray-800 dark:text-gray-100">{{ $prospect->name }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Status: {{ ucfirst($prospect->status) }}</div>
                            <p class="text-sm mt-1">
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
                            <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $prospect->notes }}</p>
                            <small class="block mt-1 text-xs text-gray-500">Follow up: {{ $prospect->follow_up_date ?? 'None' }}</small>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-300">No prospects found.</p>
                    @endforelse
                </div>
            </div>
            {{-- Analytics Overview --}}
            <div class="bg-white border p-6 rounded shadow mb-8">
                <h2 class="text-xl font-bold mb-4">Analytics Overview</h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 text-center">
                    <div class="bg-gray-100 p-4 rounded">
                        <p class="text-sm text-gray-600">Total Prospects</p>
                        <p class="text-2xl font-bold">{{ $analytics['total'] }}</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded">
                        <p class="text-sm text-gray-600">Avg. Streak</p>
                        <p class="text-2xl font-bold">{{ $analytics['avg_streak'] }}</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded">
                        <p class="text-sm text-gray-600">Follow-Ups This Week</p>
                        <p class="text-2xl font-bold">{{ $analytics['weekly_followups'] }}</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded">
                        <p class="text-sm text-gray-600">New This Week</p>
                        <p class="text-2xl font-bold">{{ $analytics['new_this_week'] }}</p>
                    </div>
                </div>

                <h3 class="font-semibold mb-2">Stage Distribution</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($analytics['stage_counts'] as $stage => $count)
                        <div class="bg-indigo-100 p-3 rounded text-center">
                            <p class="text-sm text-indigo-800">{{ ucwords(str_replace('_', ' ', $stage)) }}</p>
                            <p class="text-xl font-bold text-indigo-900">{{ $count }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- Motivation Delta --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="bg-green-100 p-4 rounded text-green-800">
                    <p class="font-semibold">This week vs. last week:</p>
                    <ul class="mt-2">
                        <li>
                            {{ $analytics['delta']['followups'] >= 0 ? '+' : '' }}{{ $analytics['delta']['followups'] }}
                            follow-ups this week
                        </li>
                        <li>
                            {{ $analytics['delta']['new'] >= 0 ? '+' : '' }}{{ $analytics['delta']['new'] }}
                            new prospects added
                        </li>
                    </ul>
                    @if($analytics['delta']['followups'] > 0 || $analytics['delta']['new'] > 0)
                        <p class="mt-2 text-sm text-green-700">You're making progress! Keep it up!</p>
                    @else
                        <p class="mt-2 text-sm text-red-700">Momentum dipped. Let’s pick it back up!</p>
                    @endif
                </div>

                {{-- Focus Zone --}}
                <div class="bg-yellow-100 p-4 rounded text-yellow-900">
                    <p class="font-semibold">Focus Zone</p>
                    <p class="mt-2">
                        You have the most people stuck in:
                        <span class="font-bold">{{ ucwords(str_replace('_', ' ', $analytics['focus_zone'])) }}</span>
                    </p>
                    <p class="text-sm mt-1">Try moving a few of them forward today.</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
