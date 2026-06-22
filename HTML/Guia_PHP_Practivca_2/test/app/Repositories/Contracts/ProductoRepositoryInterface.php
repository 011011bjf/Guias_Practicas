<?php

namespace App\Repositories\Contracts;

use App\Models\Producto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductoRepositoryInterface
{
    public function findAll(array $filters = []): LengthAwarePaginator;
    public function findById(int $id): ?Producto;
    public function create(array $data): Producto;
    public function update(int $id, array $data): Producto;
    public function delete(int $id): bool;
}
