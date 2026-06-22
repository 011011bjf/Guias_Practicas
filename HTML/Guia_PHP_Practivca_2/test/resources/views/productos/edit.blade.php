@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('productos.index') }}" class="text-slate-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Editar Producto</h1>
            <p class="text-sm text-slate-400">Modifica los detalles del producto seleccionado.</p>
        </div>
    </div>

    <div class="glass p-8 rounded-2xl glow relative overflow-hidden">
        <form action="{{ route('productos.update', $producto->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre" class="block text-sm font-medium text-slate-300 mb-2">Nombre del Producto</label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre" 
                    value="{{ old('nombre', $producto->nombre) }}" 
                    class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" 
                    placeholder="Ej: Teclado Mecánico Corsair"
                    required
                >
                @error('nombre')
                    <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="precio" class="block text-sm font-medium text-slate-300 mb-2">Precio ($)</label>
                    <input 
                        type="number" 
                        name="precio" 
                        id="precio" 
                        step="0.01"
                        value="{{ old('precio', $producto->precio) }}" 
                        class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" 
                        placeholder="0.00"
                        required
                    >
                    @error('precio')
                        <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-slate-300 mb-2">Stock Inicial</label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        value="{{ old('stock', $producto->stock) }}" 
                        class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" 
                        placeholder="0"
                        required
                    >
                    @error('stock')
                        <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="categoria_id" class="block text-sm font-medium text-slate-300 mb-2">Categoría</label>
                <select 
                    name="categoria_id" 
                    id="categoria_id" 
                    class="w-full bg-slate-900/60 border border-slate-700/80 rounded-xl px-4 py-3 text-sm text-slate-300 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                    required
                >
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id')
                    <p class="mt-2 text-xs text-red-400 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('productos.index') }}" class="bg-slate-900 hover:bg-slate-805 text-slate-300 px-5 py-3 rounded-xl text-sm font-semibold border border-slate-850 hover:border-slate-800 transition-all">
                    Cancelar
                </a>
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-505 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg hover:shadow-blue-500/20 active:scale-[0.98] transition-all cursor-pointer"
                >
                    Actualizar Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
