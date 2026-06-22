package edu.uteq.practicajava_spring_jwt.Entity;


import jakarta.persistence.*;
import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.hibernate.annotations.CreationTimestamp;

import java.time.LocalDateTime;

@Entity
@Table(name="usuario")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class Usuario {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Column(name = "id_usuario")
    private Long idUsuario;

    @NotBlank(message = "Los nombres son obligatorios")
    @Size(max = 100, message = "Los nombres no pueden superar los 100 caracteres")
    @Column(name = "nombres", nullable = false, length = 100)
    private String nombres;

    @NotBlank(message = "Los apellidos son obligatorios")
    @Size(max = 100, message = "Los apellidos no pueden superar los 100 caracteres")
    @Column(name = "apellidos", nullable = false, length = 100)
    private String apellidos;

    @NotBlank(message = "El correo es obligatorio")
    @Email(message = "El correo no tiene un formato valido")
    @Size(max = 150, message = "El correo no puede superar los 150 caracteres")
    @Column(name = "correo", nullable = false, unique = true, length = 150)
    private String correo;

    @NotBlank(message = "El hash de la contrasena es obligatorio")
    @Size(max = 255, message = "El hash de la contrasena no puede superar los 255 caracteres")
    @Column(name = "contrasena_hash", nullable = false, length = 255)
    private String contrasenaHash;

    @CreationTimestamp
    @Column(name = "fecha_registro", updatable = false)
    private LocalDateTime fechaRegistro;

    @Builder.Default
    @Column(name = "estado_cuenta", nullable = false)
    private Boolean estadoCuenta = true;

    @ManyToMany(fetch = FetchType.EAGER)
    @JoinTable(
        name = "usuario_rol",
        joinColumns = @JoinColumn(name = "id_usuario"),
        inverseJoinColumns = @JoinColumn(name = "id_rol")
    )
    private java.util.Set<Rol> roles;
}
