package com.example.Taller_JDBC_WEB.repository;

import com.example.Taller_JDBC_WEB.model.Producto;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface ProductoRepository extends JpaRepository<Producto, Long> {

    // Equivalente en JPA al buscarPorNombreSeguro (PreparedStatement automático generado por Hibernate)
    List<Producto> findByNombre(String nombre);

    // Búsqueda segura por coincidencia parcial insensible a mayúsculas/minúsculas (LIKE ? con PreparedStatement)
    List<Producto> findByNombreContainingIgnoreCase(String nombre);
}
