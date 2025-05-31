<x-admin-layout>
    <div class="container py-5">
        <h1 class="display-5 fw-bold mb-4">New Blog Post</h1>
        <form method="POST" action="{{ route('admin.posts.store') }}">
            @csrf

            <!-- Title Field -->
            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Title</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    required
                    class="form-control"
                    placeholder="Enter post title"
                >
            </div>

            <!-- Excerpt Field -->
            <div class="mb-3">
                <label for="excerpt" class="form-label fw-semibold">Excerpt</label>
                <textarea
                    name="excerpt"
                    id="excerpt"
                    rows="3"
                    required
                    class="form-control"
                    placeholder="Enter a short excerpt"
                ></textarea>
            </div>

            <!-- Body Field (Trix Editor) -->
            <div class="mb-4">
                <label for="body" class="form-label fw-semibold">Body</label>
                <input id="body" type="hidden" name="body">
                <trix-editor input="body" class="form-control" style="min-height: 200px;"></trix-editor>
            </div>

            <!-- Publish Button -->
            <button type="submit" class="btn btn-primary btn-lg">
                Publish
            </button>
        </form>
    </div>
</x-admin-layout>
