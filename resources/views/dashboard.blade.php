<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Prospect Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Today’s Follow-ups --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Today’s Follow-ups</h3>
                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                    @forelse($todayTasks as $task)
                        <li>{{ $task->name }} – {{ ucfirst($task->status) }}</li>
                    @empty
                        <li>No follow-ups for today.</li>
                    @endforelse
                </ul>
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
                            <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $prospect->notes }}</p>
                            <small class="block mt-1 text-xs text-gray-500">Follow up: {{ $prospect->follow_up_date ?? 'None' }}</small>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-300">No prospects found.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
