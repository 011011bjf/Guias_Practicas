<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Producto\StoreProductoRequest;
use App\Http\Requests\Producto\UpdateProductoRequest;
use App\Services\ProductoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *     title="API de Gestión de Productos",
 *     version="1.0.0",
 *     description="Documentación de la API para la práctica de PHP con PostgreSQL",
 *     @OA\Contact(
 *         email="admin@example.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor Principal"
 * )
 */
class ProductoController extends Controller
{
    public function __construct(
        private readonly ProductoService $productoService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['categoria_id', 'en_stock', 'busqueda']);
        $productos = $this->productoService->listar($filters);

        return response()->json($productos);
    }

    public function store(StoreProductoRequest $request): JsonResponse
    {
        $producto = $this->productoService->crear($request->validated());

        return response()->json($producto, Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $producto = $this->productoService->obtener($id);

        return response()->json($producto);
    }

    public function update(int $id, UpdateProductoRequest $request): JsonResponse
    {
        $producto = $this->productoService->actualizar($id, $request->validated());

        return response()->json($producto);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productoService->eliminar($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
