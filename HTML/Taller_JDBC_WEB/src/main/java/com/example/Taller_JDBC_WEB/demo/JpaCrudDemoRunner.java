package com.example.Taller_JDBC_WEB.demo;

import com.example.Taller_JDBC_WEB.model.Producto;
import com.example.Taller_JDBC_WEB.repository.ProductoRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;
import org.springframework.util.StopWatch;

import java.math.BigDecimal;
import java.util.List;

@Component
@RequiredArgsConstructor
public class JpaCrudDemoRunner implements CommandLineRunner {

    private final ProductoRepository productoRepository;

    @Override
    public void run(String... args) throws Exception {
        System.out.println("\n======================================================================");
        System.out.println("  INICIANDO DEMOSTRACIÓN TALLER JPA (EQUIVALENTE A JDBC PURO)");
        System.out.println("======================================================================");

        // ------------------------------------------------------------
        // 1) Demo: Seguridad inherente de JPA contra Inyección SQL
        // ------------------------------------------------------------
        String ataque = "' OR '1'='1";
        System.out.println("\n--- 1. DEMO INYECCIÓN SQL SOBRE MÉTODO JPA (PreparedStatement) ---");
        System.out.println("Buscando con parámetro atacante: " + ataque);
        List<Producto> filasSegura = productoRepository.findByNombre(ataque);
        System.out.println("Filas devueltas al atacante con JPA: " + filasSegura.size() + " (correcto: 0)");

        // ------------------------------------------------------------
        // 2) Medición del listar() con StopWatch de Spring
        // ------------------------------------------------------------
        System.out.println("\n--- 2. MEDICIÓN DE RENDIMIENTO findAll() CON StopWatch ---");
        StopWatch stopWatch = new StopWatch("Medicion JPA findAll()");
        stopWatch.start("productoRepository.findAll()");
        List<Producto> lista = productoRepository.findAll();
        stopWatch.stop();
        System.out.println("Total de productos recuperados con JPA: " + lista.size());
        System.out.println(stopWatch.prettyPrint());

        // ------------------------------------------------------------
        // 3) Prueba de Crear y Eliminar Producto
        // ------------------------------------------------------------
        System.out.println("--- 3. PRUEBA CRUD: CREAR Y ELIMINAR ---");
        Producto nuevo = new Producto("Monitor Gamer JPA 144Hz", new BigDecimal("249.99"), 25);
        Producto guardado = productoRepository.save(nuevo);
        System.out.println("Producto guardado correctamente en BD con ID generado: " + guardado.getId());

        boolean existeAntes = productoRepository.existsById(guardado.getId());
        System.out.println("¿Existe producto en BD antes de eliminar?: " + existeAntes);

        productoRepository.deleteById(guardado.getId());
        boolean existeDespues = productoRepository.existsById(guardado.getId());
        System.out.println("¿Existe producto en BD después de eliminar?: " + existeDespues);
        System.out.println("======================================================================\n");
    }
}
