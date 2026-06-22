<?php

namespace App\Services;

use App\Models\Producto;
use App\Repositories\Contracts\ProductoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductoService
{
    public function __construct(
        private readonly ProductoRepositoryInterface $productoRepo
    ) {}

    public function listar(array $filters = []): LengthAwarePaginator
    {
        return $this->productoRepo->findAll($filters);
    }

    public function obtener(int $id): Producto
    {
        $producto = $this->productoRepo->findById($id);
        if (!$producto) {
            throw (new ModelNotFoundException())->setModel(Producto::class, [$id]);
        }
        return $producto;
    }

    public function crear(array $data): Producto
    {
        return $this->productoRepo->create($data);
    }

    public function actualizar(int $id, array $data): Producto
    {
        return $this->productoRepo->update($id, $data);
    }

    public function eliminar(int $id): bool
    {
        return $this->productoRepo->delete($id);
    }
}
