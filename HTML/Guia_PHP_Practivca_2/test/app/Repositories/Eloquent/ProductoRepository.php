<?php

namespace App\Repositories\Eloquent;

use App\Models\Producto;
use App\Repositories\Contracts\ProductoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductoRepository implements ProductoRepositoryInterface
{
    public function __construct(
        private readonly Producto $model
    ) {}

    public function findAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['categoria_id'])) {
            $query->porCategoria((int) $filters['categoria_id']);
        }

        if (!empty($filters['en_stock'])) {
            $query->enStock();
        }

        if (!empty($filters['busqueda'])) {
            $query->where('nombre', 'ILIKE', '%' . $filters['busqueda'] . '%');
        }

        return $query->paginate(15);
    }

    public function findById(int $id): ?Producto
    {
        return $this->model->find($id);
    }

    public function create(array $data): Producto
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Producto
    {
        $producto = $this->model->findOrFail($id);
        $producto->update($data);
        return $producto;
    }

    public function delete(int $id): bool
    {
        $producto = $this->model->find($id);
        if ($producto) {
            return (bool) $producto->delete();
        }
        return false;
    }
}
