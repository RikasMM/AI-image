<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Services\ImageGenerationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ImageController extends Controller
{
    protected ImageGenerationService $imageService;

    public function __construct(ImageGenerationService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(): View
    {
        $images = auth()->user()->images()->latest()->paginate(12);
        return view('images.index', compact('images'));
    }

    public function create(): View
    {
        return view('images.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prompt' => 'required|string|min:5|max:1000',
            'size' => 'required|in:256x256,512x512,1024x1024',
        ]);

        try {
            // Generate image using the service
            $imageUrl = $this->imageService->generateImage($validated['prompt'], $validated['size']);

            // Save to database
            auth()->user()->images()->create([
                'prompt' => $validated['prompt'],
                'image_url' => $imageUrl,
                'size' => $validated['size'],
                'generation_time' => now()->timestamp,
            ]);

            return redirect()->route('images.index')->with('success', 'Image generated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to generate image: ' . $e->getMessage()]);
        }
    }

    public function destroy(Image $image): RedirectResponse
    {
        $this->authorize('delete', $image);
        $image->delete();

        return back()->with('success', 'Image deleted successfully!');
    }
}
