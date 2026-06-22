package edu.uteq.guia_java_practicaexperimental.service;

import edu.uteq.guia_java_practicaexperimental.dto.request.ProductoRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.PagedResponse;
import edu.uteq.guia_java_practicaexperimental.dto.response.ProductoResponse;

public interface ProductoService {
    PagedResponse<ProductoResponse> findAll(int page, int size, String sortBy);
    ProductoResponse findById(Long id);
    ProductoResponse save(ProductoRequest request);
    ProductoResponse update(Long id, ProductoRequest request);
    void delete(Long id);
    PagedResponse<ProductoResponse> buscarPorNombre(String nombre, int page, int size);
    PagedResponse<ProductoResponse> findByCategoria(Long categoriaId, int page, int size);
}
