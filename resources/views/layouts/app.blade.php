<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="AI Image Generator - Create stunning images with advanced AI technology">

        <title>@yield('title') - AI Image Generator</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary: #6366f1;
                --secondary: #8b5cf6;
                --accent: #ec4899;
            }
            
            html {
                scroll-behavior: smooth;
            }
            
            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                color: #e2e8f0;
                min-height: 100vh;
            }
            
            .ai-gradient {
                background: linear-gradient(135deg, var(--primary), var(--secondary), var(--accent));
            }
            
            .ai-gradient-text {
                background: linear-gradient(135deg, var(--primary), var(--secondary), var(--accent));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .glass-effect {
                background: rgba(15, 23, 42, 0.8);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(100, 102, 241, 0.3);
                border-radius: 12px;
            }
            
            .image-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1.5rem;
            }
            
            .image-card {
                background: rgba(15, 23, 42, 0.8);
                border: 1px solid rgba(100, 102, 241, 0.3);
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.3s ease;
            }
            
            .image-card:hover {
                border-color: var(--primary);
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
                transform: translateY(-4px);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                color: white;
                padding: 10px 24px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
            }
            
            .btn-primary:hover {
                box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
                transform: translateY(-2px);
            }
            
            .nav-link {
                position: relative;
                color: #cbd5e1;
                text-decoration: none;
                padding: 8px 16px;
                border-radius: 6px;
                transition: all 0.3s ease;
            }
            
            .nav-link:hover {
                color: var(--primary);
                background: rgba(99, 102, 241, 0.1);
            }
            
            .nav-link.active {
                color: var(--primary);
                background: rgba(99, 102, 241, 0.2);
            }
            
            .loading-spinner {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid rgba(99, 102, 241, 0.3);
                border-radius: 50%;
                border-top-color: var(--primary);
                animation: spin 1s ease-in-out infinite;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav class="border-b border-slate-700 bg-slate-900/50 backdrop-blur-sm sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="/" class="flex items-center gap-2">
                                <div class="w-10 h-10 ai-gradient rounded-lg flex items-center justify-center">
                                    <i class="fas fa-wand-magic-sparkles text-white text-lg"></i>
                                </div>
                                <span class="text-xl font-bold ai-gradient-text">AI Generator</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden md:flex items-center gap-1">
                            @auth
                                <a href="{{ route('dashboard') }}" class="nav-link">
                                    <i class="fas fa-gauge-high mr-2"></i>Dashboard
                                </a>
                                <a href="{{ route('images.create') }}" class="nav-link">
                                    <i class="fas fa-plus-circle mr-2"></i>Create
                                </a>
                                <a href="{{ route('images.index') }}" class="nav-link">
                                    <i class="fas fa-images mr-2"></i>Gallery
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="nav-link">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                                </a>
                                <a href="{{ route('register') }}" class="nav-link">
                                    <i class="fas fa-user-plus mr-2"></i>Register
                                </a>
                            @endauth
                        </div>

                        <!-- User Menu / Mobile Menu -->
                        <div class="flex items-center gap-4">
                            @auth
                                <div class="relative group">
                                    <button class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-slate-800 transition">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ auth()->user()->id }}" 
                                             alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full">
                                        <span class="hidden sm:inline text-sm font-medium">{{ auth()->user()->name }}</span>
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div class="absolute right-0 mt-2 w-48 bg-slate-900 border border-slate-700 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-slate-800 rounded-t-lg">
                                            <i class="fas fa-user-circle mr-2"></i>Profile
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="block">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-slate-800 rounded-b-lg">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-slate-700 bg-gradient-to-b from-slate-900/50 to-transparent py-12">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Success Message -->
                    @if($message = Session::get('success'))
                        <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-lg text-emerald-200 flex items-center gap-3">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ $message }}</span>
                            <button onclick="this.parentElement.style.display='none'" class="ml-auto">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-circle text-red-400 mt-1"></i>
                                <div>
                                    <h3 class="font-semibold text-red-200 mb-2">Errors occurred:</h3>
                                    <ul class="text-red-200 text-sm space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="border-t border-slate-700 bg-slate-900/50 backdrop-blur-sm mt-16">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 ai-gradient rounded-lg flex items-center justify-center">
                                    <i class="fas fa-wand-magic-sparkles text-white text-sm"></i>
                                </div>
                                AI Generator
                            </h3>
                            <p class="text-slate-400 text-sm">Create stunning, unique images powered by advanced artificial intelligence technology.</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-white mb-4">Quick Links</h4>
                            <ul class="space-y-2 text-slate-400 text-sm">
                                <li><a href="{{ route('images.create') }}" class="hover:text-indigo-400 transition">Generate Images</a></li>
                                <li><a href="{{ route('images.index') }}" class="hover:text-indigo-400 transition">My Gallery</a></li>
                                <li><a href="/" class="hover:text-indigo-400 transition">Home</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-white mb-4">Support</h4>
                            <ul class="space-y-2 text-slate-400 text-sm">
                                <li><a href="#" class="hover:text-indigo-400 transition">Documentation</a></li>
                                <li><a href="#" class="hover:text-indigo-400 transition">FAQ</a></li>
                                <li><a href="#" class="hover:text-indigo-400 transition">Contact</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-white mb-4">Connect</h4>
                            <div class="flex gap-4 text-slate-400">
                                <a href="#" class="hover:text-indigo-400 transition" title="Twitter">
                                    <i class="fab fa-twitter text-lg"></i>
                                </a>
                                <a href="#" class="hover:text-indigo-400 transition" title="GitHub">
                                    <i class="fab fa-github text-lg"></i>
                                </a>
                                <a href="#" class="hover:text-indigo-400 transition" title="Discord">
                                    <i class="fab fa-discord text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-slate-700 pt-8">
                        <div class="flex flex-col sm:flex-row justify-between items-center text-slate-400 text-sm">
                            <p>&copy; {{ date('Y') }} AI Image Generator. All rights reserved.</p>
                            <div class="flex gap-6 mt-4 sm:mt-0">
                                <a href="#" class="hover:text-indigo-400 transition">Privacy Policy</a>
                                <a href="#" class="hover:text-indigo-400 transition">Terms of Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
