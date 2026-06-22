package edu.uteq.guia_java_practicaexperimental.controller.api;

import edu.uteq.guia_java_practicaexperimental.dto.request.ProductoRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.PagedResponse;
import edu.uteq.guia_java_practicaexperimental.dto.response.ProductoResponse;
import edu.uteq.guia_java_practicaexperimental.service.ProductoService;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/productos")
@Tag(name = "Productos", description = "Endpoints para CRUD de productos")
@SecurityRequirement(name = "BearerJWT")
public class ProductoController {

    @Autowired
    private ProductoService productoService;

    @GetMapping
    @Operation(summary = "Listar productos", description = "Retorna una lista paginada de todos los productos")
    public ResponseEntity<PagedResponse<ProductoResponse>> getAllProductos(
            @RequestParam(value = "page", defaultValue = "0") int page,
            @RequestParam(value = "size", defaultValue = "10") int size,
            @RequestParam(value = "sortBy", defaultValue = "id") String sortBy) {
        PagedResponse<ProductoResponse> response = productoService.findAll(page, size, sortBy);
        return ResponseEntity.ok(response);
    }

    @GetMapping("/{id}")
    @Operation(summary = "Obtener producto por ID", description = "Retorna los detalles de un producto específico")
    public ResponseEntity<ProductoResponse> getProductoById(@PathVariable Long id) {
        ProductoResponse response = productoService.findById(id);
        return ResponseEntity.ok(response);
    }

    @PostMapping
    @PreAuthorize("hasRole('ADMIN')")
    @Operation(summary = "Crear producto (Admin)", description = "Permite registrar un nuevo producto en el catálogo. Requiere rol ADMIN.")
    public ResponseEntity<ProductoResponse> createProducto(@Valid @RequestBody ProductoRequest request) {
        ProductoResponse response = productoService.save(request);
        return ResponseEntity.status(HttpStatus.CREATED).body(response);
    }

    @PutMapping("/{id}")
    @PreAuthorize("hasRole('ADMIN')")
    @Operation(summary = "Actualizar producto (Admin)", description = "Permite modificar los datos de un producto existente. Requiere rol ADMIN.")
    public ResponseEntity<ProductoResponse> updateProducto(
            @PathVariable Long id,
            @Valid @RequestBody ProductoRequest request) {
        ProductoResponse response = productoService.update(id, request);
        return ResponseEntity.ok(response);
    }

    @DeleteMapping("/{id}")
    @PreAuthorize("hasRole('ADMIN')")
    @Operation(summary = "Eliminar producto (Admin)", description = "Elimina un producto del catálogo por su ID. Requiere rol ADMIN.")
    public ResponseEntity<Void> deleteProducto(@PathVariable Long id) {
        productoService.delete(id);
        return ResponseEntity.noContent().build();
    }

    @GetMapping("/buscar")
    @Operation(summary = "Buscar productos por nombre", description = "Busca productos que contengan el nombre especificado")
    public ResponseEntity<PagedResponse<ProductoResponse>> buscarPorNombre(
            @RequestParam("nombre") String nombre,
            @RequestParam(value = "page", defaultValue = "0") int page,
            @RequestParam(value = "size", defaultValue = "10") int size) {
        PagedResponse<ProductoResponse> response = productoService.buscarPorNombre(nombre, page, size);
        return ResponseEntity.ok(response);
    }

    @GetMapping("/categoria/{categoriaId}")
    @Operation(summary = "Listar productos por categoría", description = "Retorna una lista paginada de productos de una categoría")
    public ResponseEntity<PagedResponse<ProductoResponse>> getProductosByCategoria(
            @PathVariable Long categoriaId,
            @RequestParam(value = "page", defaultValue = "0") int page,
            @RequestParam(value = "size", defaultValue = "10") int size) {
        PagedResponse<ProductoResponse> response = productoService.findByCategoria(categoriaId, page, size);
        return ResponseEntity.ok(response);
    }
}
