package edu.uteq.guia_java_practicaexperimental.dto.request;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.Positive;
import jakarta.validation.constraints.PositiveOrZero;
import lombok.*;

import java.math.BigDecimal;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class ProductoRequest {
    @NotBlank(message = "El nombre del producto es obligatorio")
    private String nombre;

    @NotNull(message = "El precio del producto es obligatorio")
    @Positive(message = "El precio debe ser un número positivo")
    private BigDecimal precio;

    @NotNull(message = "El stock del producto es obligatorio")
    @PositiveOrZero(message = "El stock no puede ser negativo")
    private Integer stock;

    @NotNull(message = "El id de categoría es obligatorio")
    private Long categoriaId;
}
