<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriaController extends Controller
{
    public function index(): JsonResponse
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ]);

        $categoria = Categoria::create($validated);

        return response()->json($categoria, Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $categoria = Categoria::findOrFail($id);
        return response()->json($categoria);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:100'],
            'descripcion' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        $categoria->update($validated);

        return response()->json($categoria);
    }

    public function destroy(int $id): JsonResponse
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
