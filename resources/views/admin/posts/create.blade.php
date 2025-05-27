<x-app-layout>
    <div class="max-w-2xl mx-auto py-12">
        <h1 class="text-2xl font-bold mb-6">New Blog Post</h1>
        <form method="POST" action="{{ route('admin.posts.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Excerpt</label>
                <textarea name="excerpt" required rows="2" class="w-full p-2 border rounded"></textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Body</label>
                <input id="body" type="hidden" name="body">
                <trix-editor input="body"></trix-editor>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Publish</button>
        </form>
    </div>
</x-app-layout>
