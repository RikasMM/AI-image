<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageGenerationService
{
    /**
     * Generate an image using the configured AI service
     *
     * @param string $prompt - The text prompt for image generation
     * @param string $size - Image size (256x256, 512x512, 1024x1024)
     * @return string - URL of the generated image
     */
    public function generateImage(string $prompt, string $size): string
    {
        // Use Pollinations API (Free, no API key needed)
        if (config('services.image_generation.provider') === 'pollinations') {
            return $this->generateWithPollinationsAI($prompt, $size);
        }

        // Use Stability AI
        if (config('services.image_generation.provider') === 'stability') {
            return $this->generateWithStabilityAI($prompt, $size);
        }

        // Use OpenAI DALL-E
        if (config('services.image_generation.provider') === 'openai') {
            return $this->generateWithOpenAI($prompt, $size);
        }

        // Fallback to placeholder
        return $this->getPlaceholderImage($size);
    }

    /**
     * Generate image using Pollinations AI (Free API)
     * No API key required
     */
    private function generateWithPollinationsAI(string $prompt, string $size): string
    {
        try {
            $dimensions = explode('x', $size);
            $width = $dimensions[0];
            $height = $dimensions[1];

            // Pollinations API endpoint
            $url = "https://image.pollinations.ai/prompt/{$prompt}";

            // Add parameters
            $url .= "?width={$width}&height={$height}";
            $url .= "&model=flux&nologo=true";

            Log::info('Generating image with Pollinations AI', [
                'prompt' => $prompt,
                'size' => $size,
                'url' => $url,
            ]);

            return $url;
        } catch (\Exception $e) {
            Log::error('Pollinations API error', [
                'error' => $e->getMessage(),
            ]);
            return $this->getPlaceholderImage($size);
        }
    }

    /**
     * Generate image using Stability AI
     * Requires API key: https://platform.stability.ai
     */
    private function generateWithStabilityAI(string $prompt, string $size): string
    {
        try {
            $apiKey = config('services.stability.api_key');

            if (!$apiKey) {
                throw new \Exception('Stability AI API key not configured');
            }

            $dimensions = explode('x', $size);
            $width = (int) $dimensions[0];
            $height = (int) $dimensions[1];

            // Map image sizes to Stability AI supported sizes
            $stabilitySize = $this->mapToStabilitySize($width, $height);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post('https://api.stability.ai/v1/generation/stable-diffusion-v1-6/text-to-image', [
                        'text_prompts' => [
                            [
                                'text' => $prompt,
                                'weight' => 1,
                            ],
                        ],
                        'cfg_scale' => 7,
                        'height' => $stabilitySize['height'],
                        'width' => $stabilitySize['width'],
                        'samples' => 1,
                        'steps' => 30,
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['artifacts'])) {
                    // Save the base64 image and return a URL
                    return $this->saveBase64Image($data['artifacts'][0]['base64']);
                }
            }

            Log::error('Stability AI error', [
                'response' => $response->body(),
            ]);

            return $this->getPlaceholderImage($size);
        } catch (\Exception $e) {
            Log::error('Stability AI error', [
                'error' => $e->getMessage(),
            ]);
            return $this->getPlaceholderImage($size);
        }
    }

    /**
     * Generate image using OpenAI DALL-E
     * Requires API key: https://platform.openai.com
     */
    private function generateWithOpenAI(string $prompt, string $size): string
    {
        try {
            $apiKey = config('services.openai.api_key');

            if (!$apiKey) {
                throw new \Exception('OpenAI API key not configured');
            }

            // Map sizes to DALL-E format
            $dallSize = $this->mapToDallESize($size);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
            ])->post('https://api.openai.com/v1/images/generations', [
                        'prompt' => $prompt,
                        'n' => 1,
                        'size' => $dallSize,
                        'quality' => 'standard',
                        'model' => 'dall-e-3',
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data'][0]['url'])) {
                    return $data['data'][0]['url'];
                }
            }

            Log::error('OpenAI error', [
                'response' => $response->body(),
            ]);

            return $this->getPlaceholderImage($size);
        } catch (\Exception $e) {
            Log::error('OpenAI error', [
                'error' => $e->getMessage(),
            ]);
            return $this->getPlaceholderImage($size);
        }
    }

    /**
     * Map custom sizes to Stability AI supported sizes
     */
    private function mapToStabilitySize(int $width, int $height): array
    {
        // Stability AI supports specific dimensions
        $supportedSizes = [
            '256x256' => ['width' => 256, 'height' => 256],
            '512x512' => ['width' => 512, 'height' => 512],
            '1024x1024' => ['width' => 1024, 'height' => 1024],
            '512x768' => ['width' => 512, 'height' => 768],
            '768x512' => ['width' => 768, 'height' => 512],
        ];

        $key = "{$width}x{$height}";
        return $supportedSizes[$key] ?? ['width' => 512, 'height' => 512];
    }

    /**
     * Map custom sizes to DALL-E supported sizes
     */
    private function mapToDallESize(string $size): string
    {
        $sizeMap = [
            '256x256' => '256x256',
            '512x512' => '512x512',
            '1024x1024' => '1024x1024',
        ];

        return $sizeMap[$size] ?? '1024x1024';
    }

    /**
     * Save base64 image to storage and return URL
     */
    private function saveBase64Image(string $base64Data): string
    {
        try {
            $imageData = base64_decode($base64Data);
            $filename = 'images/' . uniqid() . '.png';

            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $imageData);

            return \Illuminate\Support\Facades\Storage::disk('public')->url($filename);
        } catch (\Exception $e) {
            Log::error('Error saving base64 image', [
                'error' => $e->getMessage(),
            ]);
            return $this->getPlaceholderImage('512x512');
        }
    }

    /**
     * Get placeholder image (fallback)
     */
    private function getPlaceholderImage(string $size): string
    {
        $dimensions = explode('x', $size);
        $seed = uniqid();

        // Using Picsum Photos as fallback
        return "https://picsum.photos/{$dimensions[0]}/{$dimensions[1]}?random={$seed}";
    }
}
