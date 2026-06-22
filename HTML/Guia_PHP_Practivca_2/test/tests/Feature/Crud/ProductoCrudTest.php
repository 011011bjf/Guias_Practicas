<?php

namespace Tests\Feature\Crud;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Categoria $categoria;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->categoria = Categoria::create([
            'nombre' => 'Electro',
            'descripcion' => 'Electrónica y más',
        ]);
    }

    public function test_guest_is_redirected_from_products_index(): void
    {
        $response = $this->get(route('productos.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_products_index(): void
    {
        $producto = Producto::create([
            'nombre' => 'Teclado Corsair',
            'precio' => 89.99,
            'stock' => 10,
            'categoria_id' => $this->categoria->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('productos.index'));

        $response->assertStatus(200);
        $response->assertSee('Teclado Corsair');
    }

    public function test_authenticated_user_can_create_product(): void
    {
        $response = $this->actingAs($this->user)->post(route('productos.store'), [
            'nombre' => 'Mouse Logitech',
            'precio' => 49.99,
            'stock' => 20,
            'categoria_id' => $this->categoria->id,
        ]);

        $response->assertRedirect(route('productos.index'));
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Mouse Logitech',
            'precio' => 49.99,
            'stock' => 20,
        ]);
    }

    public function test_authenticated_user_can_update_product(): void
    {
        $producto = Producto::create([
            'nombre' => 'Teclado Corsair',
            'precio' => 89.99,
            'stock' => 10,
            'categoria_id' => $this->categoria->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('productos.update', $producto->id), [
            'nombre' => 'Teclado Corsair K70',
            'precio' => 99.99,
            'stock' => 5,
            'categoria_id' => $this->categoria->id,
        ]);

        $response->assertRedirect(route('productos.index'));
        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Teclado Corsair K70',
            'precio' => 99.99,
            'stock' => 5,
        ]);
    }

    public function test_authenticated_user_can_delete_product(): void
    {
        $producto = Producto::create([
            'nombre' => 'Teclado Corsair',
            'precio' => 89.99,
            'stock' => 10,
            'categoria_id' => $this->categoria->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('productos.destroy', $producto->id));

        $response->assertRedirect(route('productos.index'));
        $this->assertDatabaseMissing('productos', [
            'id' => $producto->id,
        ]);
    }
}
