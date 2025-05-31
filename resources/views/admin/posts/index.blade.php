<x-admin-layout>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-5 fw-bold mb-0">Your Blog Posts</h1>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                + New Post
            </a>
        </div>

        <ul class="list-group">
            @foreach($posts as $post)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <a
                            href="{{ route('blog.show', $post->slug) }}"
                            class="link-primary fw-semibold"
                        >
                            {{ $post->title }}
                        </a>
                        <span class="text-muted small">
                            &mdash; {{ $post->created_at->format('M j, Y') }}
                        </span>
                    </div>
                    <a
                        href="{{ route('admin.posts.edit', $post->id) }}"
                        class="btn btn-outline-secondary btn-sm"
                    >
                        Edit
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</x-admin-layout>
