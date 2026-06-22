package edu.uteq.guia_java_practicaexperimental.dto.response;

import lombok.*;

import java.math.BigDecimal;
import java.time.LocalDateTime;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class ProductoResponse {
    private Long id;
    private String nombre;
    private BigDecimal precio;
    private Integer stock;
    private Long categoriaId;
    private String categoriaNombre;
    private LocalDateTime createdAt;
    private LocalDateTime updatedAt;
}
