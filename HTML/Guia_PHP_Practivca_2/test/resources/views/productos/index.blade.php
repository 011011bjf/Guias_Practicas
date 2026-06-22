@extends('layouts.app')

@section('title', 'Listado de Productos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Catálogo de Productos</h1>
            <p class="text-sm text-slate-400 mt-1">Gestiona los productos, precios y stocks del almacén.</p>
        </div>
        <a href="{{ route('productos.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-lg hover:shadow-blue-500/20 active:scale-[0.98] transition-all cursor-pointer">
            + Nuevo Producto
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="glass p-4 rounded-2xl">
        <form action="{{ route('productos.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="busqueda" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Búsqueda</label>
                <input 
                    type="text" 
                    name="busqueda" 
                    id="busqueda" 
                    value="{{ request('busqueda') }}" 
                    placeholder="Nombre del producto..." 
                    class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all"
                >
            </div>

            <!-- Category -->
            <div>
                <label for="categoria_id" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Categoría</label>
                <select 
                    name="categoria_id" 
                    id="categoria_id" 
                    class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-300 focus:outline-none focus:border-blue-500 transition-all"
                >
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Stock status -->
            <div>
                <label for="en_stock" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Disponibilidad</label>
                <select 
                    name="en_stock" 
                    id="en_stock" 
                    class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-300 focus:outline-none focus:border-blue-500 transition-all"
                >
                    <option value="">Todos</option>
                    <option value="1" {{ request('en_stock') == '1' ? 'selected' : '' }}>Solo En Stock</option>
                </select>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-700 text-white font-semibold py-2 rounded-xl text-sm transition-all border border-slate-700/80 cursor-pointer">
                    Filtrar
                </button>
                @if(request()->anyFilled(['busqueda', 'categoria_id', 'en_stock']))
                    <a href="{{ route('productos.index') }}" class="px-3 py-2 bg-slate-950 hover:bg-slate-900 text-slate-400 hover:text-white rounded-xl text-sm font-semibold border border-slate-800 hover:border-slate-700 transition-all text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="glass rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800/80 bg-slate-900/30">
                        <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">ID</th>
                        <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Producto</th>
                        <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Categoría</th>
                        <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Precio</th>
                        <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Stock</th>
                        <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($productos as $producto)
                        <tr class="hover:bg-slate-900/20 transition-colors">
                            <td class="p-4 text-sm text-slate-500 font-medium">#{{ $producto->id }}</td>
                            <td class="p-4">
                                <div class="text-sm font-bold text-slate-100">{{ $producto->nombre }}</div>
                                <div class="text-xs text-slate-500 max-w-xs truncate">{{ $producto->descripcion ?? 'Sin descripción' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-slate-800 text-slate-300 border border-slate-750">
                                    {{ $producto->categoria->nombre }}
                                </span>
                            </td>
                            <td class="p-4 text-sm font-bold text-slate-200 text-right">${{ number_format($producto->precio, 2) }}</td>
                            <td class="p-4 text-center">
                                @if($producto->stock > 10)
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        {{ $producto->stock }} unidades
                                    </span>
                                @elseif($producto->stock > 0)
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        {{ $producto->stock }} bajas
                                    </span>
                                @else
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                        Agotado
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="text-slate-400 hover:text-blue-400 text-xs font-semibold px-2.5 py-1 rounded bg-slate-800/80 hover:bg-blue-500/10 border border-slate-700/80 hover:border-blue-500/30 transition-all">
                                        Editar
                                    </a>
                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-400 text-xs font-semibold px-2.5 py-1 rounded bg-slate-800/80 hover:bg-red-500/10 border border-slate-700/80 hover:border-red-500/30 transition-all cursor-pointer">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 text-sm">
                                <svg class="w-10 h-10 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                No se encontraron productos que coincidan con los filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($productos->hasPages())
            <div class="p-4 border-t border-slate-800 bg-slate-900/20">
                {{ $productos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
