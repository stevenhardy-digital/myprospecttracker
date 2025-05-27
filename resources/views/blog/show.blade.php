<x-app-layout>
    <div class="max-w-3xl mx-auto py-12">
        <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
        <p class="text-sm text-gray-500 mb-6">Published {{ $post->created_at->format('F j, Y') }}</p>
        <div class="prose max-w-none">
            {!! nl2br(e($post->body)) !!}
        </div>
        <div class="mt-10">
            <a href="{{ route('blog.index') }}" class="text-blue-600 hover:underline">← Back to Blog</a>
        </div>
    </div>
</x-app-layout>
