package edu.uteq.guia_java_practicaexperimental.dto.response;

import lombok.*;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class AuthResponse {
    private String token;
    private String email;
    private String nombre;
    private String role;
}
