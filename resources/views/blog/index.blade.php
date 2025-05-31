<x-guest-layout>
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-4">Blog</h1>

        @foreach($posts as $post)
            <div class="mb-5 pb-4 border-bottom">
                <h2 class="h4 fw-semibold mb-1">
                    <a
                        href="{{ route('blog.show', $post->slug) }}"
                        class="link-primary text-decoration-none"
                    >
                        {{ $post->title }}
                    </a>
                </h2>
                <p class="text-muted small mb-2">
                    Posted {{ $post->created_at->diffForHumans() }}
                </p>
                <p class="text-secondary">
                    {{ $post->excerpt }}
                </p>
            </div>
        @endforeach

        <div class="d-flex justify-content-center">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-guest-layout>
