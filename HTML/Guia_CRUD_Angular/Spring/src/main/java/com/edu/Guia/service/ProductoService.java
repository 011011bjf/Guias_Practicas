package com.edu.Guia.service;

import com.edu.Guia.model.Producto;
import com.edu.Guia.repository.ProductoRepository;
import jakarta.annotation.PostConstruct;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
public class ProductoService {

    private final ProductoRepository repository;

    public ProductoService(ProductoRepository repository) {
        this.repository = repository;
    }

    @PostConstruct
    public void inicializarDatos() {
        // Si la base de datos en PostgreSQL está vacía, insertamos productos de muestra con stock
        if (repository.count() == 0) {
            repository.save(new Producto(null, "Teclado Mecánico RGB", 85.50, 25, true));
            repository.save(new Producto(null, "Monitor 24 pulgadas IPS", 180.00, 12, true));
            repository.save(new Producto(null, "Ratón Ergonómico Inalámbrico", 35.00, 0, false));
        }
    }

    @Transactional(readOnly = true)
    public List<Producto> listarTodos() {
        return repository.findAll();
    }

    @Transactional(readOnly = true)
    public Optional<Producto> buscarPorId(Long id) {
        return repository.findById(id);
    }

    @Transactional
    public Producto guardar(Producto producto) {
        if (producto.getStock() == null) {
            producto.setStock(0);
        }
        return repository.save(producto);
    }

    @Transactional
    public Optional<Producto> actualizar(Long id, Producto productoModificado) {
        return repository.findById(id).map(productoExistente -> {
            productoExistente.setNombre(productoModificado.getNombre());
            productoExistente.setPrecio(productoModificado.getPrecio());
            if (productoModificado.getStock() != null) {
                productoExistente.setStock(productoModificado.getStock());
            }
            productoExistente.setDisponible(productoModificado.getDisponible());
            return repository.save(productoExistente);
        });
    }

    @Transactional
    public boolean eliminar(Long id) {
        if (repository.existsById(id)) {
            repository.deleteById(id);
            return true;
        }
        return false;
    }
}
