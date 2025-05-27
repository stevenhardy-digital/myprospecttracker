<x-app-layout>
    <div class="max-w-xl mx-auto py-10 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold mb-6">Update Prospect: {{ $prospect->name }}</h1>

        <form method="POST" action="{{ route('prospects.update', $prospect->id) }}" class="space-y-6">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Name</label>
                <input type="text" value="{{ $prospect->name }}" disabled class="w-full p-2 border rounded bg-gray-100">
            </div>

            <div>
                <label class="block font-semibold mb-1">Social Handle</label>
                <input type="text" value="{{ $prospect->social_handle }}" disabled class="w-full p-2 border rounded bg-gray-100">
            </div>

            <div>
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="w-full p-2 border rounded">
                    @foreach(['new', 'contacted', 'invited', 'presented', 'followed_up', 'signed_up'] as $status)
                        <option value="{{ $status }}" @selected($prospect->status === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Last Contacted</label>
                <input type="date" name="last_contacted" value="{{ $prospect->last_contacted ?? now()->toDateString() }}" class="w-full p-2 border rounded">
            </div>

            <div>
                <p class="text-green-700 font-semibold">Current Streak: {{ $prospect->streak }}</p>
                <p class="text-gray-500 text-sm">This will increase by 1 when you update.</p>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 underline">Back to Dashboard</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Prospect</button>
            </div>
        </form>

    </div>
</x-app-layout>
