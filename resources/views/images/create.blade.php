<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold ai-gradient-text flex items-center gap-3">
                    <i class="fas fa-paintbrush"></i>
                    Create New Image
                </h1>
                <p class="text-slate-400 mt-2">Enter your creative prompt and let AI bring your ideas to life</p>
            </div>
        </div>
    </x-slot>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Form Section -->
        <div class="glass-effect p-8">
            <form method="POST" action="{{ route('images.store') }}" class="space-y-6">
                @csrf

                <!-- Prompt Input -->
                <div>
                    <label for="prompt" class="block text-sm font-semibold text-white mb-3">
                        <i class="fas fa-lightbulb text-indigo-400 mr-2"></i>Your Prompt
                    </label>
                    <textarea
                        name="prompt"
                        id="prompt"
                        rows="6"
                        placeholder="Describe the image you want to generate... Be as detailed as possible!"
                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-white placeholder-slate-500 resize-none transition"
                        required
                    ></textarea>
                    @error('prompt')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-slate-400 text-xs mt-2">Minimum 5 characters, maximum 1000 characters</p>
                </div>

                <!-- Image Size Selection -->
                <div>
                    <label for="size" class="block text-sm font-semibold text-white mb-3">
                        <i class="fas fa-expand text-purple-400 mr-2"></i>Image Size
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative">
                            <input type="radio" name="size" value="256x256" class="sr-only peer" required>
                            <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-500/20 peer-checked:shadow-lg peer-checked:shadow-indigo-500/20 border border-slate-700 rounded-lg p-4 cursor-pointer transition text-center">
                                <div class="text-2xl font-bold ai-gradient-text">Small</div>
                                <div class="text-xs text-slate-400 mt-1">256×256px</div>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="size" value="512x512" class="sr-only peer" checked>
                            <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-500/20 peer-checked:shadow-lg peer-checked:shadow-indigo-500/20 border border-slate-700 rounded-lg p-4 cursor-pointer transition text-center">
                                <div class="text-2xl font-bold ai-gradient-text">Medium</div>
                                <div class="text-xs text-slate-400 mt-1">512×512px</div>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="size" value="1024x1024" class="sr-only peer">
                            <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-500/20 peer-checked:shadow-lg peer-checked:shadow-indigo-500/20 border border-slate-700 rounded-lg p-4 cursor-pointer transition text-center">
                                <div class="text-2xl font-bold ai-gradient-text">Large</div>
                                <div class="text-xs text-slate-400 mt-1">1024×1024px</div>
                            </div>
                        </label>
                    </div>
                    @error('size')
                        <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>


                <!-- Submit Button -->
                <button type="submit" class="w-full btn-primary text-lg font-semibold flex items-center justify-center gap-2 py-3">
                    <i class="fas fa-wand-magic-sparkles"></i>
                    Generate Image
                </button>

                <!-- Quick Examples -->
                <div class="bg-slate-800/50 rounded-lg p-4 border border-slate-700">
                    <p class="text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
                        <i class="fas fa-sparkles text-amber-400"></i>Quick Examples:
                    </p>
                    <div class="space-y-2 text-xs">
                        <button type="button" onclick="document.getElementById('prompt').value = 'A serene landscape with mountains, crystal clear lake at sunset, golden hour lighting'; document.getElementById('prompt').focus()" class="w-full text-left px-3 py-2 bg-slate-700/50 hover:bg-slate-700 rounded text-slate-300 transition">
                            Mountain sunset landscape
                        </button>
                        <button type="button" onclick="document.getElementById('prompt').value = 'A futuristic city skyline with neon lights, cyberpunk aesthetic, flying cars in the sky'; document.getElementById('prompt').focus()" class="w-full text-left px-3 py-2 bg-slate-700/50 hover:bg-slate-700 rounded text-slate-300 transition">
                            Cyberpunk futuristic city
                        </button>
                        <button type="button" onclick="document.getElementById('prompt').value = 'A magical forest with glowing trees, ethereal creatures, mystical atmosphere, fantasy art'; document.getElementById('prompt').focus()" class="w-full text-left px-3 py-2 bg-slate-700/50 hover:bg-slate-700 rounded text-slate-300 transition">
                            Magical fantasy forest
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Preview/Tips Section -->
        <div class="space-y-6">
            <!-- Tips Card -->
            <div class="glass-effect p-8">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-bulb text-yellow-400"></i>Tips for Best Results
                </h3>
                <div class="space-y-3 text-sm text-slate-300">
                    <div class="flex gap-3">
                        <i class="fas fa-check text-emerald-400 mt-1 flex-shrink-0"></i>
                        <p><strong>Be Specific:</strong> Include details about style, lighting, colors, and mood</p>
                    </div>
                    <div class="flex gap-3">
                        <i class="fas fa-check text-emerald-400 mt-1 flex-shrink-0"></i>
                        <p><strong>Use Art Styles:</strong> Mention styles like "oil painting", "digital art", "photography"</p>
                    </div>
                    <div class="flex gap-3">
                        <i class="fas fa-check text-emerald-400 mt-1 flex-shrink-0"></i>
                        <p><strong>Descriptive Adjectives:</strong> Use words like "vibrant", "serene", "dramatic"</p>
                    </div>
                    <div class="flex gap-3">
                        <i class="fas fa-check text-emerald-400 mt-1 flex-shrink-0"></i>
                        <p><strong>Set the Scene:</strong> Describe the environment and context clearly</p>
                    </div>
                    <div class="flex gap-3">
                        <i class="fas fa-check text-emerald-400 mt-1 flex-shrink-0"></i>
                        <p><strong>Resolution Matters:</strong> Larger sizes take more credits but provide better quality</p>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="glass-effect p-8">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-400"></i>About Generation
                </h3>
                <div class="space-y-3 text-sm text-slate-300">
                    <div class="flex justify-between items-center">
                        <span>Processing Time:</span>
                        <span class="font-semibold text-indigo-400">~30-60 seconds</span>
                    </div>
                    <div class="border-t border-slate-700 pt-3">
                        <p class="text-slate-400">Your generated images will be saved to your gallery and accessible anytime.</p>
                    </div>
                </div>
            </div>

            <!-- Recent Images -->
            <div class="glass-effect p-8">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-pink-400"></i>Recent Generations
                </h3>
                @php
                    $recentImages = auth()->user()->images()->latest()->take(3)->get();
                @endphp
                @if($recentImages->count())
                    <div class="space-y-3">
                        @foreach($recentImages as $image)
                            <div class="flex items-start gap-3 text-sm">
                                <i class="fas fa-check-circle text-emerald-400 mt-1"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-slate-300 truncate">{{ Str::limit($image->prompt, 40) }}</p>
                                    <p class="text-slate-500 text-xs">{{ $image->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-400 text-center py-4">No images generated yet. Create your first one!</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
