package edu.uteq.guia_java_practicaexperimental.service.impl;

import edu.uteq.guia_java_practicaexperimental.dto.request.ProductoRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.PagedResponse;
import edu.uteq.guia_java_practicaexperimental.dto.response.ProductoResponse;
import edu.uteq.guia_java_practicaexperimental.exception.ResourceNotFoundException;
import edu.uteq.guia_java_practicaexperimental.model.Categoria;
import edu.uteq.guia_java_practicaexperimental.model.Producto;
import edu.uteq.guia_java_practicaexperimental.repository.CategoriaRepository;
import edu.uteq.guia_java_practicaexperimental.repository.ProductoRepository;
import edu.uteq.guia_java_practicaexperimental.service.ProductoService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;
import org.springframework.data.domain.Sort;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.stream.Collectors;

@Service
public class ProductoServiceImpl implements ProductoService {

    @Autowired
    private ProductoRepository productoRepository;

    @Autowired
    private CategoriaRepository categoriaRepository;

    private ProductoResponse mapToResponse(Producto producto) {
        return ProductoResponse.builder()
                .id(producto.getId())
                .nombre(producto.getNombre())
                .precio(producto.getPrecio())
                .stock(producto.getStock())
                .categoriaId(producto.getCategoria().getId())
                .categoriaNombre(producto.getCategoria().getNombre())
                .createdAt(producto.getCreatedAt())
                .updatedAt(producto.getUpdatedAt())
                .build();
    }

    private PagedResponse<ProductoResponse> mapToPagedResponse(Page<Producto> pageResult) {
        List<ProductoResponse> content = pageResult.getContent().stream()
                .map(this::mapToResponse)
                .collect(Collectors.toList());

        return PagedResponse.<ProductoResponse>builder()
                .content(content)
                .page(pageResult.getNumber())
                .size(pageResult.getSize())
                .totalElements(pageResult.getTotalElements())
                .totalPages(pageResult.getTotalPages())
                .last(pageResult.isLast())
                .build();
    }

    @Override
    @Transactional(readOnly = true)
    public PagedResponse<ProductoResponse> findAll(int page, int size, String sortBy) {
        Pageable pageable = PageRequest.of(page, size, Sort.by(sortBy).ascending());
        Page<Producto> pageResult = productoRepository.findAll(pageable);
        return mapToPagedResponse(pageResult);
    }

    @Override
    @Transactional(readOnly = true)
    public ProductoResponse findById(Long id) {
        Producto producto = productoRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Producto", "id", id));
        return mapToResponse(producto);
    }

    @Override
    @Transactional
    public ProductoResponse save(ProductoRequest request) {
        Categoria categoria = categoriaRepository.findById(request.getCategoriaId())
                .orElseThrow(() -> new ResourceNotFoundException("Categoria", "id", request.getCategoriaId()));

        Producto producto = Producto.builder()
                .nombre(request.getNombre())
                .precio(request.getPrecio())
                .stock(request.getStock())
                .categoria(categoria)
                .build();

        Producto savedProducto = productoRepository.save(producto);
        return mapToResponse(savedProducto);
    }

    @Override
    @Transactional
    public ProductoResponse update(Long id, ProductoRequest request) {
        Producto producto = productoRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Producto", "id", id));

        Categoria categoria = categoriaRepository.findById(request.getCategoriaId())
                .orElseThrow(() -> new ResourceNotFoundException("Categoria", "id", request.getCategoriaId()));

        producto.setNombre(request.getNombre());
        producto.setPrecio(request.getPrecio());
        producto.setStock(request.getStock());
        producto.setCategoria(categoria);

        Producto updatedProducto = productoRepository.save(producto);
        return mapToResponse(updatedProducto);
    }

    @Override
    @Transactional
    public void delete(Long id) {
        Producto producto = productoRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Producto", "id", id));
        productoRepository.delete(producto);
    }

    @Override
    @Transactional(readOnly = true)
    public PagedResponse<ProductoResponse> buscarPorNombre(String nombre, int page, int size) {
        Pageable pageable = PageRequest.of(page, size);
        Page<Producto> pageResult = productoRepository.buscarPorNombre(nombre, pageable);
        return mapToPagedResponse(pageResult);
    }

    @Override
    @Transactional(readOnly = true)
    public PagedResponse<ProductoResponse> findByCategoria(Long categoriaId, int page, int size) {
        if (!categoriaRepository.existsById(categoriaId)) {
            throw new ResourceNotFoundException("Categoria", "id", categoriaId);
        }
        Pageable pageable = PageRequest.of(page, size);
        Page<Producto> pageResult = productoRepository.findByCategoriaId(categoriaId, pageable);
        return mapToPagedResponse(pageResult);
    }
}
