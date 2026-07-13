package com.example.Taller_JDBC_WEB.controller;

import com.example.Taller_JDBC_WEB.model.Producto;
import com.example.Taller_JDBC_WEB.service.ProductoService;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.math.BigDecimal;
import java.util.List;

@RestController
@RequestMapping("/api/productos")
@RequiredArgsConstructor
public class ProductoController {

    private final ProductoService productoService;

    // 1. LISTAR TODOS -> GET /api/productos
    @GetMapping
    public List<Producto> listar() {
        return productoService.listarTodos();
    }

    // 2. BUSCAR POR ID -> GET /api/productos/{id}
    @GetMapping("/{id}")
    public ResponseEntity<Producto> obtenerPorId(@PathVariable Long id) {
        return productoService.buscarPorId(id)
                .map(ResponseEntity::ok)
                .orElse(ResponseEntity.notFound().build());
    }

    // 3. BUSCAR POR NOMBRE O PATRÓN -> GET /api/productos/buscar?nombre=...
    @GetMapping("/buscar")
    public List<Producto> buscar(@RequestParam String nombre) {
        return productoService.buscarPorNombreSeguro(nombre);
    }

    // 4. CREAR PRODUCTO -> POST /api/productos
    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public Producto crear(@RequestBody Producto producto) {
        return productoService.crearProducto(
                producto.getNombre(),
                producto.getPrecio() != null ? producto.getPrecio() : BigDecimal.ZERO,
                producto.getStock() != null ? producto.getStock() : 0
        );
    }

    // 5. ACTUALIZAR PRODUCTO -> PUT /api/productos/{id}
    @PutMapping("/{id}")
    public ResponseEntity<Producto> actualizar(@PathVariable Long id, @RequestBody Producto producto) {
        return productoService.actualizarProducto(id, producto.getNombre(), producto.getPrecio(), producto.getStock())
                .map(ResponseEntity::ok)
                .orElse(ResponseEntity.notFound().build());
    }

    // 6. ELIMINAR PRODUCTO -> DELETE /api/productos/{id}
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> eliminar(@PathVariable Long id) {
        boolean eliminado = productoService.eliminarProducto(id);
        return eliminado ? ResponseEntity.noContent().build() : ResponseEntity.notFound().build();
    }
}
