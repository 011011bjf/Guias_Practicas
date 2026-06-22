<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Seed the productos and categorias tables with test data.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Electrónica', 'descripcion' => 'Dispositivos electrónicos y accesorios'],
            ['nombre' => 'Ropa', 'descripcion' => 'Prendas de vestir para todas las edades'],
            ['nombre' => 'Hogar', 'descripcion' => 'Artículos para el hogar y decoración'],
            ['nombre' => 'Deportes', 'descripcion' => 'Equipamiento deportivo y accesorios'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        $productos = [
            ['nombre' => 'Smartphone Samsung Galaxy', 'precio' => 599.99, 'stock' => 25, 'categoria_id' => 1],
            ['nombre' => 'Laptop HP Pavilion', 'precio' => 899.50, 'stock' => 15, 'categoria_id' => 1],
            ['nombre' => 'Auriculares Bluetooth Sony', 'precio' => 149.99, 'stock' => 50, 'categoria_id' => 1],
            ['nombre' => 'Camiseta Nike Básica', 'precio' => 29.99, 'stock' => 100, 'categoria_id' => 2],
            ['nombre' => 'Jeans Levi\'s 501', 'precio' => 89.99, 'stock' => 40, 'categoria_id' => 2],
            ['nombre' => 'Zapatillas Adidas Ultraboost', 'precio' => 179.99, 'stock' => 30, 'categoria_id' => 2],
            ['nombre' => 'Lámpara de Mesa IKEA', 'precio' => 45.00, 'stock' => 60, 'categoria_id' => 3],
            ['nombre' => 'Juego de Sábanas 300 Hilos', 'precio' => 59.99, 'stock' => 35, 'categoria_id' => 3],
            ['nombre' => 'Balón de Fútbol Adidas', 'precio' => 39.99, 'stock' => 70, 'categoria_id' => 4],
            ['nombre' => 'Mancuernas Ajustables', 'precio' => 129.99, 'stock' => 20, 'categoria_id' => 4],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
