# AI Image Generator - API Integration Guide

## Overview
Your application now supports multiple image generation APIs. By default, it uses **Pollinations AI** (free, no API key required), but you can easily switch to other providers.

---

## 🚀 Quick Start (Default - Pollinations AI)

**Good news!** The default configuration already works without any API key:

1. **No setup needed** - Pollinations API is completely free
2. **Just start using** - Go to `/images/create` and start generating images
3. **No limitations** - All features work out of the box

---

## 📋 Supported Providers

### 1. **Pollinations AI** (FREE - DEFAULT ✅)
- **Cost**: Free
- **Quality**: Good
- **Speed**: 5-10 seconds per image
- **Setup**: No API key needed
- **Status**: Already configured and working

#### Features:
- Multiple models (Flux, etc.)
- Supports all sizes (256x256, 512x512, 1024x1024)
- No registration required
- Unlimited generations

---

### 2. **Stability AI** (PAID)
- **Cost**: Pay-as-you-go (~$0.04-0.08 per image)
- **Quality**: Excellent
- **Speed**: 10-20 seconds per image
- **Setup**: Requires API key

#### How to Setup:

1. **Get API Key**:
   - Go to https://platform.stability.ai
   - Create an account
   - Generate API key
   - Add credits to your account

2. **Update `.env` file**:
```env
IMAGE_GENERATION_PROVIDER=stability
STABILITY_API_KEY=sk-xxxxxxxxxxxxxxxxxxxx
```

3. **Save and test** - Your app will now use Stability AI

---

### 3. **OpenAI DALL-E 3** (PAID - BEST QUALITY)
- **Cost**: $0.04-0.08 per image
- **Quality**: Exceptional
- **Speed**: 10-15 seconds per image
- **Setup**: Requires API key

#### How to Setup:

1. **Get API Key**:
   - Go to https://platform.openai.com
   - Create an account
   - Go to API Keys section
   - Create new API key
   - Add credits to your account

2. **Update `.env` file**:
```env
IMAGE_GENERATION_PROVIDER=openai
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxx
```

3. **Save and test** - Your app will now use DALL-E 3

---

## 🔧 Configuration

### Environment Variables (`.env`)

```env
# Image Generation Provider
# Options: 'pollinations', 'stability', 'openai'
IMAGE_GENERATION_PROVIDER=pollinations

# Gemini API (for future text generation)
GEMINI_API_KEY=your-api-key-here

# Stability AI
STABILITY_API_KEY=your-api-key-here

# OpenAI
OPENAI_API_KEY=your-api-key-here
```

### Switch Providers Anytime

Just change one line in `.env`:
```env
# Use Pollinations (FREE)
IMAGE_GENERATION_PROVIDER=pollinations

# OR use Stability (PAID)
IMAGE_GENERATION_PROVIDER=stability

# OR use OpenAI (PAID - Best)
IMAGE_GENERATION_PROVIDER=openai
```

---

## 📁 Code Structure

### Service Class
**File**: `app/Services/ImageGenerationService.php`

This class handles:
- Routing requests to the correct provider
- Error handling and fallbacks
- Image size mapping
- Base64 image storage

### Controller
**File**: `app/Http/Controllers/ImageController.php`

Updated to:
- Use dependency injection for the service
- Handle image generation cleanly
- Better error handling

### Configuration
**File**: `config/services.php`

Stores all API configuration centrally

---

## 🎯 How It Works

### Image Generation Flow

```
User submits prompt
         ↓
ImageController.store()
         ↓
ImageGenerationService.generateImage()
         ↓
Check which provider is configured
         ↓
Call appropriate API method:
- Pollinations.generateWithPollinationsAI()
- Stability.generateWithStabilityAI()
- OpenAI.generateWithOpenAI()
         ↓
Get image URL
         ↓
Save to database
         ↓
Display to user
```

---

## 💡 Integration for Gemini (Future)

When you want to use **Gemini for text generation** (prompt enhancement):

### Add to `ImageGenerationService.php`:

```php
private function enhancePromptWithGemini(string $prompt): string
{
    try {
        $apiKey = config('services.gemini.api_key');
        
        if (!$apiKey) {
            return $prompt; // Return original if no API key
        }

        $response = Http::withHeaders([
            'x-goog-api-key' => $apiKey,
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Enhance this image prompt for better results: {$prompt}",
                        ],
                    ],
                ],
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? $prompt;
        }

        return $prompt;
    } catch (\Exception $e) {
        Log::error('Gemini enhancement failed', ['error' => $e->getMessage()]);
        return $prompt;
    }
}
```

Then use it in `generateImage()`:
```php
$enhancedPrompt = $this->enhancePromptWithGemini($prompt);
// Use $enhancedPrompt instead of $prompt
```

---

## 🧪 Testing

### Test in Tinker:

```bash
php artisan tinker
```

```php
$service = app(\App\Services\ImageGenerationService::class);
$url = $service->generateImage('A beautiful sunset over mountains', '512x512');
echo $url;
```

### Test API Calls:

Use the web interface:
1. Go to `/images/create`
2. Enter a prompt
3. Select size
4. Click "Generate Image"

---

## 🚨 Troubleshooting

### Images not generating?

**Check 1**: Which provider is configured?
```bash
php artisan tinker
Config::get('services.image_generation.provider')
```

**Check 2**: Are API keys set correctly?
```bash
Config::get('services.openai.api_key')
Config::get('services.stability.api_key')
```

**Check 3**: Check logs:
```bash
tail -f storage/logs/laravel.log
```

### Pollinations API not working?
- It's free and no key needed
- Check if `pollinations` is set in `.env`
- It usually works, if not temporarily switch to another provider

### Paid APIs failing?
- Verify API key is correct
- Check if account has sufficient credits/balance
- Ensure API key has proper permissions

---

## 📊 Comparison Table

| Feature | Pollinations | Stability AI | OpenAI DALL-E |
|---------|-------------|-------------|---------------|
| Cost | Free | Paid | Paid |
| API Key | Not needed | Required | Required |
| Quality | Good | Excellent | Exceptional |
| Speed | 5-10s | 10-20s | 10-15s |
| Models | Flux | SD 3.5, XL | DALL-E 3 |
| Reliability | Very Good | Excellent | Excellent |
| Setup | 0 minutes | 5 minutes | 5 minutes |

---

## 🔐 Security Notes

1. **Never commit `.env` file** - It contains secrets
2. **Use strong API keys** - Regenerate if compromised
3. **Monitor API usage** - Set spending limits if possible
4. **Rate limiting** - Consider adding to prevent abuse

---

## 📈 Next Steps

1. ✅ **Current**: Using Pollinations (free, working)
2. 📝 **Optional**: Add Stability AI for better quality
3. ⭐ **Optional**: Switch to DALL-E 3 for best results
4. 🤖 **Future**: Add Gemini for prompt enhancement
5. 🎨 **Advanced**: Add support for multiple models

---

## Support

If you need help:
1. Check the logs: `storage/logs/laravel.log`
2. Run tests in tinker
3. Verify API keys and provider configuration
4. Check internet connection (for API calls)

**Happy generating! 🎨**
