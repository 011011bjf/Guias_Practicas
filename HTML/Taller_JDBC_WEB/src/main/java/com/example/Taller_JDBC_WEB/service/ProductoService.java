package com.example.Taller_JDBC_WEB.service;

import com.example.Taller_JDBC_WEB.model.Producto;
import com.example.Taller_JDBC_WEB.repository.ProductoRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
public class ProductoService {

    private final ProductoRepository productoRepository;

    /**
     * 1. READ / LISTAR: Equivalente al listar() de JDBC.
     * Devuelve todos los productos ordenados por defecto por ID o según consulta.
     */
    @Transactional(readOnly = true)
    public List<Producto> listarTodos() {
        return productoRepository.findAll();
    }

    /**
     * 2. CREATE / CREAR: Equivalente al crear(nombre, precio, stock) de JDBC.
     * Guarda la entidad en BD y recupera automáticamente la clave autogenerada (BIGSERIAL).
     */
    @Transactional
    public Producto crearProducto(String nombre, BigDecimal precio, Integer stock) {
        Producto producto = new Producto(nombre, precio, stock);
        return productoRepository.save(producto);
    }

    /**
     * 3. UPDATE / ACTUALIZAR: Modificación transaccional de un producto existente.
     * En JPA, basta con modificar el objeto dentro de una transacción (@Transactional);
     * Hibernate ejecuta el UPDATE automáticamente por mecanismo de 'dirty checking'.
     */
    @Transactional
    public Optional<Producto> actualizarProducto(Long id, String nombre, BigDecimal precio, Integer stock) {
        return productoRepository.findById(id).map(producto -> {
            if (nombre != null) producto.setNombre(nombre);
            if (precio != null) producto.setPrecio(precio);
            if (stock != null) producto.setStock(stock);
            return productoRepository.save(producto);
        });
    }

    /**
     * 4. DELETE / ELIMINAR: Equivalente al eliminar(long id) de JDBC.
     * Verifica si existe y lo elimina.
     */
    @Transactional
    public boolean eliminarProducto(Long id) {
        if (productoRepository.existsById(id)) {
            productoRepository.deleteById(id);
            return true;
        }
        return false;
    }

    /**
     * 5. BÚSQUEDA SEGURA POR NOMBRE: Equivalente a buscarPorNombreSeguro(String nombreBuscado) del Paso 12.
     * Previene inyección SQL gracias a PreparedStatement de Hibernate.
     */
    @Transactional(readOnly = true)
    public List<Producto> buscarPorNombreSeguro(String nombre) {
        return productoRepository.findByNombre(nombre);
    }

    /**
     * 6. BÚSQUEDA PARCIAL SEGURA: LIKE parametrizado por detrás.
     */
    @Transactional(readOnly = true)
    public List<Producto> buscarPorCoincidencia(String patron) {
        return productoRepository.findByNombreContainingIgnoreCase(patron);
    }

    /**
     * 7. BUSCAR POR ID
     */
    @Transactional(readOnly = true)
    public Optional<Producto> buscarPorId(Long id) {
        return productoRepository.findById(id);
    }
}
