<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tienda Stock') - Premium Control</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        /* Glassmorphism utility */
        .glass {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow {
            box-shadow: 0 0 40px -5px rgba(59, 130, 246, 0.2);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col justify-between selection:bg-blue-600 selection:text-white antialiased">
    <div>
        <!-- Gradient top glow -->
        <div class="absolute top-0 left-1/4 right-1/4 h-80 bg-blue-500/10 rounded-full filter blur-3xl pointer-events-none"></div>

        <!-- Navigation -->
        <nav class="glass sticky top-0 z-50 border-b border-slate-800/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                            <span class="bg-gradient-to-r from-blue-500 to-indigo-500 text-transparent bg-clip-text font-extrabold text-xl tracking-tight">
                                TIENDA STOCK
                            </span>
                            <span class="text-xs bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded-full font-medium border border-blue-500/20 group-hover:border-blue-500/40 transition-colors">
                                v2.0
                            </span>
                        </a>
                        
                        @auth
                        <div class="hidden md:flex items-center gap-6">
                            <a href="{{ route('dashboard') }}" class="text-slate-300 hover:text-white px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-400 border-b-2 border-blue-500' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('productos.index') }}" class="text-slate-300 hover:text-white px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('productos.*') ? 'text-blue-400 border-b-2 border-blue-500' : '' }}">
                                Productos
                            </a>
                        </div>
                        @endauth
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <div class="flex items-center gap-4">
                                <span class="hidden sm:inline-block text-sm text-slate-400">
                                    Hola, <strong class="text-slate-200">{{ auth()->user()->name }}</strong>
                                </span>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-slate-800/60 hover:bg-red-950/40 hover:text-red-400 hover:border-red-500/30 text-slate-300 px-4 py-2 rounded-lg text-sm font-medium border border-slate-700/80 transition-all duration-300 cursor-pointer">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <a href="{{ route('login') }}" class="text-slate-300 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Iniciar Sesión
                                </a>
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-lg hover:shadow-blue-500/20 transition-all duration-300">
                                    Registrarse
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 flex items-center justify-between shadow-lg shadow-emerald-500/5 transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl border border-red-500/20 bg-red-500/10 text-red-400 flex items-center justify-between shadow-lg shadow-red-500/5 transition-all">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950/80 py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Tienda Stock Corp. Todos los derechos reservados. Diseñado con excelencia visual.</p>
    </footer>
</body>
</html>
