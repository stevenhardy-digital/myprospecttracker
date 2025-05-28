<x-guest-layout>
    <div class="max-w-3xl mx-auto py-12">
        <h1 class="text-3xl font-bold mb-6">Blog</h1>

        @foreach($posts as $post)
            <div class="mb-6 border-b pb-4">
                <h2 class="text-xl font-semibold">
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 hover:underline">
                        {{ $post->title }}
                    </a>
                </h2>
                <p class="text-sm text-gray-500">Posted {{ $post->created_at->diffForHumans() }}</p>
                <p class="mt-2 text-gray-700">{{ $post->excerpt }}</p>
            </div>
        @endforeach

        {{ $posts->links() }}
    </div>
</x-guest-layout>
