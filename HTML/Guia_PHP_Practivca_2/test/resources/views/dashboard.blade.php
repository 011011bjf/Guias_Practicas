@extends('layouts.app')

@section('title', 'Dashboard')

@inject('categoriaModel', 'App\Models\Categoria')
@inject('productoModel', 'App\Models\Producto')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="glass p-8 rounded-2xl glow relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div>
            <span class="text-xs bg-blue-500/10 text-blue-400 px-3 py-1 rounded-full font-medium border border-blue-500/20 mb-3 inline-block">
                Panel General
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                Hola, {{ $user->name }}
            </h1>
            <p class="text-slate-400 mt-2">Bienvenido a tu panel de control de inventario de Tienda Stock.</p>
        </div>
        <a href="{{ route('productos.create') }}" class="bg-blue-600 hover:bg-blue-505 text-white px-5 py-3 rounded-xl text-sm font-semibold shadow-lg hover:shadow-blue-500/20 active:scale-[0.98] transition-all cursor-pointer">
            + Añadir Producto
        </a>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Stat Card 1: Products -->
        <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700/80 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-slate-400">Total Productos</p>
                    <h3 class="text-4xl font-extrabold text-white mt-1 group-hover:scale-105 transition-transform duration-300 origin-left">
                        {{ $productoModel::count() }}
                    </h3>
                </div>
                <div class="p-3 bg-blue-500/10 rounded-xl text-blue-400 border border-blue-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500">Productos actualmente en base de datos.</p>
        </div>

        <!-- Stat Card 2: Categories -->
        <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700/80 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-slate-400">Total Categorías</p>
                    <h3 class="text-4xl font-extrabold text-white mt-1 group-hover:scale-105 transition-transform duration-300 origin-left">
                        {{ $categoriaModel::count() }}
                    </h3>
                </div>
                <div class="p-3 bg-indigo-500/10 rounded-xl text-indigo-400 border border-indigo-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500">Categorías para clasificar productos.</p>
        </div>

        <!-- Stat Card 3: Out of Stock -->
        <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700/80 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-medium text-slate-400">Sin Stock</p>
                    <h3 class="text-4xl font-extrabold text-red-400 mt-1 group-hover:scale-105 transition-transform duration-300 origin-left">
                        {{ $productoModel::where('stock', 0)->count() }}
                    </h3>
                </div>
                <div class="p-3 bg-red-500/10 rounded-xl text-red-400 border border-red-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500">Productos con stock igual a cero.</p>
        </div>
    </div>

    <!-- Quick Navigation Actions -->
    <div class="glass p-6 rounded-2xl">
        <h3 class="text-lg font-bold text-white mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('productos.index') }}" class="flex items-center gap-3 p-4 bg-slate-900/40 hover:bg-slate-900 border border-slate-800 rounded-xl hover:border-slate-700 transition-all group">
                <span class="p-2 bg-blue-500/10 text-blue-400 rounded-lg group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </span>
                <span class="text-sm font-semibold text-slate-200 group-hover:text-white">Ver Catálogo</span>
            </a>

            <a href="{{ route('productos.create') }}" class="flex items-center gap-3 p-4 bg-slate-900/40 hover:bg-slate-900 border border-slate-800 rounded-xl hover:border-slate-700 transition-all group">
                <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </span>
                <span class="text-sm font-semibold text-slate-200 group-hover:text-white">Crear Producto</span>
            </a>

            <div class="flex items-center gap-3 p-4 bg-slate-900/40 border border-slate-800 rounded-xl opacity-60">
                <span class="p-2 bg-purple-500/10 text-purple-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <span class="text-sm font-semibold text-slate-400">API Endpoints (Sanctum)</span>
            </div>

            <div class="flex items-center gap-3 p-4 bg-slate-900/40 border border-slate-800 rounded-xl opacity-60">
                <span class="p-2 bg-amber-500/10 text-amber-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </span>
                <span class="text-sm font-semibold text-slate-400">Configuración</span>
            </div>
        </div>
    </div>
</div>
@endsection
