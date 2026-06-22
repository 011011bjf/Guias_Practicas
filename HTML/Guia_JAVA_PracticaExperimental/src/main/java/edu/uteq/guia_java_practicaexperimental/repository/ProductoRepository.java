package edu.uteq.guia_java_practicaexperimental.repository;

import edu.uteq.guia_java_practicaexperimental.model.Producto;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

@Repository
public interface ProductoRepository extends JpaRepository<Producto, Long> {
    Page<Producto> findByCategoriaId(Long categoriaId, Pageable pageable);

    @Query("SELECT p FROM Producto p WHERE LOWER(p.nombre) LIKE LOWER(CONCAT('%', :nombre, '%'))")
    Page<Producto> buscarPorNombre(@Param("nombre") String nombre, Pageable pageable);
}
