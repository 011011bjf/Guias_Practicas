package edu.uteq.guia_java_practicaexperimental.dto.request;

import jakarta.validation.constraints.NotBlank;
import lombok.*;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class CategoriaRequest {
    @NotBlank(message = "El nombre de la categoría es obligatorio")
    private String nombre;

    private String descripcion;
}
