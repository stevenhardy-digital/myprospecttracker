<x-app-layout>
    <div class="max-w-3xl mx-auto py-12">
        <h1 class="text-2xl font-bold mb-6">Your Blog Posts</h1>
        <a href="{{ route('admin.posts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ New Post</a>
        <ul class="mt-6 space-y-3">
            @foreach($posts as $post)
                <li>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-700 hover:underline font-semibold">{{ $post->title }}</a>
                    <span class="text-gray-500 text-sm"> — {{ $post->created_at->format('M j, Y') }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
