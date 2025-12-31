<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold ai-gradient-text flex items-center gap-3">
                    <i class="fas fa-images"></i>
                    My Gallery
                </h1>
                <p class="text-slate-400 mt-2">View and manage all your generated images</p>
            </div>
            <a href="{{ route('images.create') }}" class="btn-primary inline-flex items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Create New
            </a>
        </div>
    </x-slot>

    @if($images->count())
        <!-- Image Grid -->
        <div class="image-grid">
            @foreach($images as $image)
                <div class="image-card group">
                    <!-- Image Container -->
                    <div class="relative h-64 bg-slate-800 overflow-hidden">
                        <img src="{{ $image->image_url }}" alt="{{ $image->prompt }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">

                        <!-- Overlay -->
                        <div
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-300 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100">
                            <a href="{{ $image->image_url }}" download
                                class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center hover:bg-indigo-600 transition"
                                title="Download image">
                                <i class="fas fa-download text-white"></i>
                            </a>
                            <button onclick="deleteImage({{ $image->id }})"
                                class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center hover:bg-red-600 transition"
                                title="Delete image">
                                <i class="fas fa-trash text-white"></i>
                            </button>
                        </div>

                        <!-- Size Badge -->
                        <div
                            class="absolute top-3 right-3 bg-black/60 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-indigo-300">
                            {{ $image->size }}
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="p-4">
                        <p class="text-sm text-slate-300 line-clamp-2 mb-3">
                            {{ $image->prompt }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $image->created_at->format('M d, Y') }}
                            </span>
                            <span>
                                <i class="fas fa-clock mr-1"></i>
                                {{ $image->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($images->hasPages())
            <div class="mt-12">
                {{ $images->links('pagination::tailwind') }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="mb-6">
                <i class="fas fa-inbox text-6xl text-slate-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">No Images Yet</h2>
            <p class="text-slate-400 mb-8">You haven't generated any images yet. Let's create your first masterpiece!</p>
            <a href="{{ route('images.create') }}" class="btn-primary inline-flex items-center gap-2">
                <i class="fas fa-wand-magic-sparkles"></i>
                Create Your First Image
            </a>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="glass-effect p-8 rounded-lg max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
                Delete Image
            </h3>
            <p class="text-slate-300 mb-6">Are you sure you want to delete this image? This action cannot be undone.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let deleteImageId = null;

        function deleteImage(imageId) {
            deleteImageId = imageId;
            const form = document.getElementById('deleteForm');
            form.action = `/images/${imageId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteImageId = null;
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>