<?php

namespace App\Http\Controllers;

use App\Http\Requests\Producto\StoreProductoRequest;
use App\Http\Requests\Producto\UpdateProductoRequest;
use App\Models\Categoria;
use App\Services\ProductoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoViewController extends Controller
{
    public function __construct(
        private readonly ProductoService $productoService
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['categoria_id', 'en_stock', 'busqueda']);
        $productos = $this->productoService->listar($filters);
        $categorias = Categoria::all();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create(): View
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    public function store(StoreProductoRequest $request): RedirectResponse
    {
        $this->productoService->crear($request->validated());

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(int $id): View
    {
        $producto = $this->productoService->obtener($id);
        $categorias = Categoria::all();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(int $id, StoreProductoRequest $request): RedirectResponse
    {
        $this->productoService->actualizar($id, $request->validated());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->productoService->eliminar($id);

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
