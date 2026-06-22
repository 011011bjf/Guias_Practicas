package edu.uteq.guia_java_practicaexperimental.dto.response;

import lombok.*;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class CategoriaResponse {
    private Long id;
    private String nombre;
    private String descripcion;
}
