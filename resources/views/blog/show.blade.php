<x-guest-layout>
    <div class="container py-5">
        <!-- Post Title -->
        <h1 class="display-4 fw-bold mb-3">{{ $post->title }}</h1>

        <!-- Published Date -->
        <p class="text-muted small mb-4">
            Published {{ $post->created_at->format('F j, Y') }}
        </p>

        <!-- Post Body -->
        <div class="mb-5">
            {!! nl2br(e($post->body)) !!}
        </div>

        <!-- Back Link -->
        <div class="mt-4">
            <a href="{{ route('blog.index') }}" class="link-primary">
                ← Back to Blog
            </a>
        </div>
    </div>
</x-guest-layout>
