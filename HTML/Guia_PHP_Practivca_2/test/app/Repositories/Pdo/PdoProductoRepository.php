<?php

namespace App\Repositories\Pdo;

use App\Models\Producto;
use App\Repositories\Contracts\ProductoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class PdoProductoRepository implements ProductoRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::getPdo();
        
        // Configurar los atributos de PDO requeridos por la rúbrica
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function findAll(array $filters = []): LengthAwarePaginator
    {
        try {
            $sql = "SELECT * FROM productos WHERE 1=1";
            $countSql = "SELECT COUNT(*) as total FROM productos WHERE 1=1";
            $params = [];

            if (!empty($filters['categoria_id'])) {
                $sql .= " AND categoria_id = :categoria_id";
                $countSql .= " AND categoria_id = :categoria_id";
                $params['categoria_id'] = (int) $filters['categoria_id'];
            }

            if (!empty($filters['en_stock'])) {
                $sql .= " AND stock > 0";
                $countSql .= " AND stock > 0";
            }

            if (!empty($filters['busqueda'])) {
                $sql .= " AND nombre ILIKE :busqueda";
                $countSql .= " AND nombre ILIKE :busqueda";
                $params['busqueda'] = '%' . $filters['busqueda'] . '%';
            }

            // Paginación
            $perPage = 15;
            $page = Paginator::resolveCurrentPage();
            $offset = ($page - 1) * $perPage;

            // 1. Ejecutar query de conteo (para el paginador)
            $countStmt = $this->pdo->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = (int) ($countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

            // 2. Ejecutar query de selección paginada
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);

            // Enlazar parámetros normales
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            // Enlazar LIMIT y OFFSET explícitamente como enteros (requerido cuando emulate prepares es false)
            $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue('offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Hydrate a modelos Eloquent para mantener compatibilidad con las vistas y controladores de Laravel
            $items = Producto::hydrate($rows);

            return new Paginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => Paginator::resolveCurrentPath()]
            );

        } catch (PDOException $e) {
            logger()->error("Error en PdoProductoRepository::findAll: " . $e->getMessage());
            throw $e;
        }
    }

    public function findById(int $id): ?Producto
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id = :id LIMIT 1");
            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null;
            }

            $hydrated = Producto::hydrate([$row]);
            return $hydrated->first();

        } catch (PDOException $e) {
            logger()->error("Error en PdoProductoRepository::findById: " . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data): Producto
    {
        try {
            $now = now()->toDateTimeString();
            $sql = "INSERT INTO productos (nombre, precio, stock, categoria_id, created_at, updated_at) 
                    VALUES (:nombre, :precio, :stock, :categoria_id, :created_at, :updated_at)";

            $stmt = $this->pdo->prepare($sql);
            
            // bindValue para enlazar tipos específicos de forma segura
            $stmt->bindValue('nombre', $data['nombre']);
            $stmt->bindValue('precio', $data['precio']);
            $stmt->bindValue('stock', (int) $data['stock'], PDO::PARAM_INT);
            $stmt->bindValue('categoria_id', (int) $data['categoria_id'], PDO::PARAM_INT);
            $stmt->bindValue('created_at', $now);
            $stmt->bindValue('updated_at', $now);

            $stmt->execute();

            $id = (int) $this->pdo->lastInsertId();

            return $this->findById($id);

        } catch (PDOException $e) {
            logger()->error("Error en PdoProductoRepository::create: " . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, array $data): Producto
    {
        try {
            $now = now()->toDateTimeString();
            
            // Obtener el producto actual para rellenar campos faltantes en caso de actualización parcial
            $current = $this->findById($id);
            if (!$current) {
                throw new PDOException("Producto con ID {$id} no encontrado para actualizar.");
            }

            $nombre = $data['nombre'] ?? $current->nombre;
            $precio = $data['precio'] ?? $current->precio;
            $stock = $data['stock'] ?? $current->stock;
            $categoriaId = $data['categoria_id'] ?? $current->categoria_id;

            $sql = "UPDATE productos 
                    SET nombre = :nombre, precio = :precio, stock = :stock, categoria_id = :categoria_id, updated_at = :updated_at 
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->bindValue('nombre', $nombre);
            $stmt->bindValue('precio', $precio);
            $stmt->bindValue('stock', (int) $stock, PDO::PARAM_INT);
            $stmt->bindValue('categoria_id', (int) $categoriaId, PDO::PARAM_INT);
            $stmt->bindValue('updated_at', $now);

            $stmt->execute();

            return $this->findById($id);

        } catch (PDOException $e) {
            logger()->error("Error en PdoProductoRepository::update: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id = :id");
            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            logger()->error("Error en PdoProductoRepository::delete: " . $e->getMessage());
            throw $e;
        }
    }
}
