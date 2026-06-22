@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md glass glow p-8 rounded-2xl relative overflow-hidden">
        <!-- Accent light -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/20 rounded-full blur-2xl"></div>
        
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-white mb-2 bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
                Bienvenido de nuevo
            </h2>
            <p class="text-sm text-slate-400">Introduce tus credenciales para acceder a tu panel</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Correo Electrónico</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" 
                    placeholder="tucorreo@ejemplo.com"
                    required
                    autofocus
                >
                @error('email')
                    <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="text-sm font-medium text-slate-300">Contraseña</label>
                </div>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" 
                    placeholder="••••••••"
                    required
                >
                @error('password')
                    <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-blue-500/20 active:scale-[0.98] transition-all cursor-pointer"
            >
                Entrar al Sistema
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-800/80 pt-6">
            <p class="text-sm text-slate-400">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">
                    Regístrate aquí
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
