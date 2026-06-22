package edu.uteq.guia_java_practicaexperimental.service;

import edu.uteq.guia_java_practicaexperimental.dto.request.ProductoRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.ProductoResponse;
import edu.uteq.guia_java_practicaexperimental.exception.ResourceNotFoundException;
import edu.uteq.guia_java_practicaexperimental.model.Categoria;
import edu.uteq.guia_java_practicaexperimental.model.Producto;
import edu.uteq.guia_java_practicaexperimental.repository.CategoriaRepository;
import edu.uteq.guia_java_practicaexperimental.repository.ProductoRepository;
import edu.uteq.guia_java_practicaexperimental.service.impl.ProductoServiceImpl;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;

import java.math.BigDecimal;
import java.util.Optional;

import static org.assertj.core.api.Assertions.assertThat;
import static org.assertj.core.api.Assertions.assertThatThrownBy;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
public class ProductoServiceTest {

    @Mock
    private ProductoRepository productoRepository;

    @Mock
    private CategoriaRepository categoriaRepository;

    @InjectMocks
    private ProductoServiceImpl productoService;

    @Test
    public void testFindById_Success() {
        Categoria cat = Categoria.builder().id(1L).nombre("Libros").build();
        Producto prod = Producto.builder()
                .id(10L)
                .nombre("Java Book")
                .precio(new BigDecimal("49.99"))
                .stock(100)
                .categoria(cat)
                .build();

        when(productoRepository.findById(10L)).thenReturn(Optional.of(prod));

        ProductoResponse response = productoService.findById(10L);

        assertThat(response.getId()).isEqualTo(10L);
        assertThat(response.getNombre()).isEqualTo("Java Book");
        assertThat(response.getCategoriaNombre()).isEqualTo("Libros");
    }

    @Test
    public void testFindById_NotFound() {
        when(productoRepository.findById(99L)).thenReturn(Optional.empty());

        assertThatThrownBy(() -> productoService.findById(99L))
                .isInstanceOf(ResourceNotFoundException.class);
    }

    @Test
    public void testSave_Success() {
        ProductoRequest request = ProductoRequest.builder()
                .nombre("Mouse")
                .precio(new BigDecimal("25.00"))
                .stock(10)
                .categoriaId(1L)
                .build();

        Categoria cat = Categoria.builder().id(1L).nombre("Periféricos").build();
        Producto prod = Producto.builder()
                .id(1L)
                .nombre("Mouse")
                .precio(new BigDecimal("25.00"))
                .stock(10)
                .categoria(cat)
                .build();

        when(categoriaRepository.findById(1L)).thenReturn(Optional.of(cat));
        when(productoRepository.save(any(Producto.class))).thenReturn(prod);

        ProductoResponse response = productoService.save(request);

        assertThat(response.getNombre()).isEqualTo("Mouse");
        assertThat(response.getCategoriaNombre()).isEqualTo("Periféricos");
        verify(productoRepository, times(1)).save(any(Producto.class));
    }
}
