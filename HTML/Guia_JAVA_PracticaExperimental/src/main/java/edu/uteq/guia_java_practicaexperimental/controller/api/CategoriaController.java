package edu.uteq.guia_java_practicaexperimental.controller.api;

import edu.uteq.guia_java_practicaexperimental.dto.request.CategoriaRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.CategoriaResponse;
import edu.uteq.guia_java_practicaexperimental.service.CategoriaService;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/categorias")
@Tag(name = "Categorías", description = "Endpoints para CRUD de categorías")
@SecurityRequirement(name = "BearerJWT")
public class CategoriaController {

    @Autowired
    private CategoriaService categoriaService;

    @GetMapping
    @Operation(summary = "Listar categorías", description = "Retorna todas las categorías registradas")
    public ResponseEntity<List<CategoriaResponse>> getAllCategorias() {
        List<CategoriaResponse> response = categoriaService.findAll();
        return ResponseEntity.ok(response);
    }

    @GetMapping("/{id}")
    @Operation(summary = "Obtener categoría por ID", description = "Retorna los detalles de un categoría específica")
    public ResponseEntity<CategoriaResponse> getCategoriaById(@PathVariable Long id) {
        CategoriaResponse response = categoriaService.findById(id);
        return ResponseEntity.ok(response);
    }

    @PostMapping
    @PreAuthorize("hasRole('ADMIN')")
    @Operation(summary = "Crear categoría (Admin)", description = "Permite registrar una nueva categoría. Requiere rol ADMIN.")
    public ResponseEntity<CategoriaResponse> createCategoria(@Valid @RequestBody CategoriaRequest request) {
        CategoriaResponse response = categoriaService.save(request);
        return ResponseEntity.status(HttpStatus.CREATED).body(response);
    }

    @PutMapping("/{id}")
    @PreAuthorize("hasRole('ADMIN')")
    @Operation(summary = "Actualizar categoría (Admin)", description = "Permite modificar una categoría existente. Requiere rol ADMIN.")
    public ResponseEntity<CategoriaResponse> updateCategoria(
            @PathVariable Long id,
            @Valid @RequestBody CategoriaRequest request) {
        CategoriaResponse response = categoriaService.update(id, request);
        return ResponseEntity.ok(response);
    }

    @DeleteMapping("/{id}")
    @PreAuthorize("hasRole('ADMIN')")
    @Operation(summary = "Eliminar categoría (Admin)", description = "Elimina una categoría por su ID. Requiere rol ADMIN.")
    public ResponseEntity<Void> deleteCategoria(@PathVariable Long id) {
        categoriaService.delete(id);
        return ResponseEntity.noContent().build();
    }

    @GetMapping("/buscar")
    @Operation(summary = "Buscar categorías por nombre", description = "Busca categorías por coincidencia en su nombre")
    public ResponseEntity<List<CategoriaResponse>> buscarPorNombre(@RequestParam("nombre") String nombre) {
        List<CategoriaResponse> response = categoriaService.buscarPorNombre(nombre);
        return ResponseEntity.ok(response);
    }
}
