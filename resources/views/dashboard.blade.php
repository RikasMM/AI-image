<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold ai-gradient-text flex items-center gap-3">
                    <i class="fas fa-sparkles"></i>
                    Welcome Back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-slate-400 mt-2">Ready to create amazing AI-generated images? Let's get started.</p>
            </div>
        </div>
    </x-slot>

    <!-- Stats Section -->
    <div class="grid md:grid-cols-3 gap-6 mb-12">
        <!-- Total Images -->
        <div class="glass-effect p-8 rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-slate-400 text-sm mb-1">Total Images Created</p>
                    <h3 class="text-4xl font-bold text-white">
                        {{ auth()->user()->images()->count() }}
                    </h3>
                </div>
                <div class="w-16 h-16 bg-indigo-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-images text-2xl text-indigo-400"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500">Keep creating amazing content</p>
        </div>

        <!-- This Month -->
        <div class="glass-effect p-8 rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-slate-400 text-sm mb-1">Generated This Month</p>
                    <h3 class="text-4xl font-bold text-white">
                        {{ auth()->user()->images()->whereDate('created_at', '>=', now()->startOfMonth())->count() }}
                    </h3>
                </div>
                <div class="w-16 h-16 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-2xl text-purple-400"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500">Growing your collection</p>
        </div>

        <!-- Latest Image -->
        <div class="glass-effect p-8 rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-slate-400 text-sm mb-1">Last Generated</p>
                    <h3 class="text-white font-semibold truncate max-w-xs">
                        @php
                            $lastImage = auth()->user()->images()->latest()->first();
                        @endphp
                        @if($lastImage)
                            {{ Str::limit($lastImage->prompt, 20) }}
                        @else
                            No images yet
                        @endif
                    </h3>
                </div>
                <div class="w-16 h-16 bg-pink-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-2xl text-pink-400"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500">
                {{ $lastImage ? $lastImage->created_at->diffForHumans() : 'Start creating now' }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main CTA Section -->
        <div class="lg:col-span-2">
            <div class="glass-effect p-12 rounded-xl border-2 border-indigo-500/50">
                <h2 class="text-3xl font-bold text-white mb-3 flex items-center gap-3">
                    <i class="fas fa-wand-magic-sparkles text-indigo-400"></i>
                    Ready to Create?
                </h2>
                <p class="text-slate-300 mb-8">Describe your imagination and let AI bring it to life. Create stunning,
                    unique images in seconds.</p>

                <div class="grid sm:grid-cols-2 gap-4 mb-8">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Multiple Resolutions</p>
                            <p class="text-sm text-slate-400">256x256, 512x512, 1024x1024</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Fast Processing</p>
                            <p class="text-sm text-slate-400">30-60 seconds per image</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Full Control</p>
                            <p class="text-sm text-slate-400">Manage & download your creations</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-emerald-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-white">AI Powered</p>
                            <p class="text-sm text-slate-400">Advanced generation algorithms</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('images.create') }}"
                    class="btn-primary text-lg py-4 px-8 w-full sm:w-auto inline-flex items-center justify-center gap-3 font-semibold">
                    <i class="fas fa-wand-magic-sparkles"></i>
                    Create Your First Image
                </a>
            </div>

            <!-- Recent Images Preview -->
            @php
                $recentImages = auth()->user()->images()->latest()->take(3)->get();
            @endphp
            @if($recentImages->count())
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-history text-pink-400"></i>
                        Recent Creations
                    </h3>
                    <div class="grid sm:grid-cols-3 gap-4">
                        @foreach($recentImages as $image)
                            <div class="image-card group">
                                <div class="relative h-48 bg-slate-800 overflow-hidden">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->prompt }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <a href="{{ route('images.index') }}"
                                            class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 rounded-lg text-white transition">
                                            <i class="fas fa-arrow-right mr-2"></i>View All
                                        </a>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <p class="text-xs text-slate-300 line-clamp-2">{{ $image->prompt }}</p>
                                    <p class="text-xs text-slate-500 mt-2">{{ $image->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('images.index') }}"
                            class="text-indigo-400 hover:text-indigo-300 transition flex items-center justify-center gap-2">
                            <span>View All {{ auth()->user()->images()->count() }} Images</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @else
                <div class="mt-8 glass-effect p-8 rounded-xl text-center">
                    <i class="fas fa-inbox text-4xl text-slate-600 mb-4"></i>
                    <p class="text-slate-300">No images created yet. Start your creative journey now!</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Start Guide -->
            <div class="glass-effect p-8 rounded-xl">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-rocket text-yellow-400"></i>
                    Quick Start
                </h3>
                <ol class="space-y-3 text-sm">
                    <li class="flex gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xs font-bold flex-shrink-0">1</span>
                        <span class="text-slate-300">Write a detailed prompt</span>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xs font-bold flex-shrink-0">2</span>
                        <span class="text-slate-300">Choose image size</span>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xs font-bold flex-shrink-0">3</span>
                        <span class="text-slate-300">Wait for generation</span>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-xs font-bold flex-shrink-0">4</span>
                        <span class="text-slate-300">Download & share</span>
                    </li>
                </ol>
            </div>

            <!-- Tips -->
            <div class="glass-effect p-8 rounded-xl">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-lightbulb text-amber-400"></i>
                    Pro Tips
                </h3>
                <ul class="space-y-3 text-sm text-slate-300">
                    <li class="flex gap-2">
                        <i class="fas fa-star text-amber-400 mt-1 flex-shrink-0"></i>
                        <span>Be specific in your descriptions</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-star text-amber-400 mt-1 flex-shrink-0"></i>
                        <span>Mention art styles and moods</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-star text-amber-400 mt-1 flex-shrink-0"></i>
                        <span>Use descriptive adjectives</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-star text-amber-400 mt-1 flex-shrink-0"></i>
                        <span>Experiment with different sizes</span>
                    </li>
                </ul>
            </div>

            <!-- Account Info -->
            <div class="glass-effect p-8 rounded-xl">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-user-circle text-blue-400"></i>
                    Account
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-slate-400">Email</p>
                        <p class="text-white font-medium">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Member Since</p>
                        <p class="text-white font-medium">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                        class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 transition mt-2">
                        <i class="fas fa-edit"></i>
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>