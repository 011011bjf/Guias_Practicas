package edu.uteq.guia_java_practicaexperimental.repository;

import edu.uteq.guia_java_practicaexperimental.model.Categoria;
import edu.uteq.guia_java_practicaexperimental.model.Producto;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.jdbc.test.autoconfigure.AutoConfigureTestDatabase;
import org.springframework.boot.data.jpa.test.autoconfigure.DataJpaTest;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;

import java.math.BigDecimal;
import java.util.List;

import static org.assertj.core.api.Assertions.assertThat;

@DataJpaTest
@AutoConfigureTestDatabase(replace = AutoConfigureTestDatabase.Replace.NONE)
public class ProductoRepositoryTest {

    static {
        try {
            java.nio.file.Path envPath = java.nio.file.Paths.get(".env");
            if (java.nio.file.Files.exists(envPath)) {
                java.util.List<String> lines = java.nio.file.Files.readAllLines(envPath);
                for (String line : lines) {
                    line = line.trim();
                    if (line.isEmpty() || line.startsWith("#")) continue;
                    int idx = line.indexOf('=');
                    if (idx > 0) {
                        String key = line.substring(0, idx).trim();
                        String val = line.substring(idx + 1).trim();
                        if (val.startsWith("\"") && val.endsWith("\"")) {
                            val = val.substring(1, val.length() - 1);
                        } else if (val.startsWith("'") && val.endsWith("'")) {
                            val = val.substring(1, val.length() - 1);
                        }
                        System.setProperty(key, val);
                    }
                }
            }
        } catch (Exception e) {
            System.err.println("Could not load .env: " + e.getMessage());
        }
    }

    @Autowired
    private ProductoRepository productoRepository;

    @Autowired
    private CategoriaRepository categoriaRepository;

    private Categoria categoria;

    @BeforeEach
    public void setUp() {
        productoRepository.deleteAll();
        categoriaRepository.deleteAll();

        categoria = Categoria.builder()
                .nombre("Electrónica")
                .descripcion("Dispositivos electrónicos")
                .build();
        categoria = categoriaRepository.save(categoria);

        Producto producto1 = Producto.builder()
                .nombre("Smartphone")
                .precio(new BigDecimal("799.99"))
                .stock(50)
                .categoria(categoria)
                .build();

        Producto producto2 = Producto.builder()
                .nombre("Laptop")
                .precio(new BigDecimal("1200.00"))
                .stock(15)
                .categoria(categoria)
                .build();

        productoRepository.saveAll(List.of(producto1, producto2));
    }

    @Test
    public void testBuscarPorNombre() {
        Pageable pageable = PageRequest.of(0, 10);
        Page<Producto> page = productoRepository.buscarPorNombre("phone", pageable);

        assertThat(page.getContent()).hasSize(1);
        assertThat(page.getContent().get(0).getNombre()).isEqualTo("Smartphone");
    }

    @Test
    public void testFindByCategoriaId() {
        Pageable pageable = PageRequest.of(0, 10);
        Page<Producto> page = productoRepository.findByCategoriaId(categoria.getId(), pageable);

        assertThat(page.getContent()).hasSize(2);
    }
}
