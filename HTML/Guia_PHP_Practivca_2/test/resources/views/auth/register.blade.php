@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md glass glow p-8 rounded-2xl relative overflow-hidden">
        <!-- Accent light -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl"></div>
        
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-white mb-2 bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
                Crea tu Cuenta
            </h2>
            <p class="text-sm text-slate-400">Regístrate para gestionar inventarios de forma óptima</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nombre Completo</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" 
                    placeholder="Tu nombre completo"
                    required
                    autofocus
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

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
                >
                @error('email')
                    <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Contraseña</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" 
                    placeholder="Mínimo 8 caracteres"
                    required
                >
                @error('password')
                    <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">Confirmar Contraseña</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" 
                    placeholder="Repite la contraseña"
                    required
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-indigo-500/20 active:scale-[0.98] transition-all cursor-pointer"
            >
                Crear Cuenta
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-800/80 pt-6">
            <p class="text-sm text-slate-400">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
