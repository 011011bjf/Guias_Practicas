<?php

namespace Tests\Unit\Repositories;

use App\Models\Categoria;
use App\Models\Producto;
use App\Repositories\Contracts\ProductoRepositoryInterface;
use App\Repositories\Eloquent\ProductoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductoRepositoryInterface $repository;
    private Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(ProductoRepositoryInterface::class);
        $this->categoria = Categoria::create([
            'nombre' => 'Electro',
            'descripcion' => 'Electrónica',
        ]);
    }

    public function test_can_store_product_via_repository(): void
    {
        $data = [
            'nombre' => 'Teclado Corsair',
            'precio' => 89.99,
            'stock' => 10,
            'categoria_id' => $this->categoria->id,
        ];

        $producto = $this->repository->create($data);

        $this->assertInstanceOf(Producto::class, $producto);
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Teclado Corsair',
        ]);
    }

    public function test_can_find_product_via_repository(): void
    {
        $producto = Producto::create([
            'nombre' => 'Teclado Corsair',
            'precio' => 89.99,
            'stock' => 10,
            'categoria_id' => $this->categoria->id,
        ]);

        $found = $this->repository->findById($producto->id);

        $this->assertNotNull($found);
        $this->assertEquals($producto->id, $found->id);
    }
}
