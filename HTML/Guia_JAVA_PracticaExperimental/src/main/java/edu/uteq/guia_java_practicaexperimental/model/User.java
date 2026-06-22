package edu.uteq.guia_java_practicaexperimental.model;

import jakarta.persistence.*;
import lombok.*;
import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.UpdateTimestamp;

import java.time.LocalDateTime;

@Entity
@Table(name = "users")
@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
public class User {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false, length = 255)
    private String name;

    @Column(nullable = false, unique = true, length = 100)
    private String email;

    @Column(name = "email_verified_at")
    private LocalDateTime emailVerifiedAt;

    @Column(nullable = false, length = 255)
    private String password;

    @Column(name = "remember_token", length = 100)
    private String rememberToken;

    @Column(nullable = false, length = 50)
    private Roles rol; // Stores "ROLE_USER" or "ROLE_ADMIN" (Note: Not in SQL dump, but required for app security)

    @CreationTimestamp
    @Column(name = "created_at", nullable = false, updatable = false)
    private LocalDateTime createdAt;

    @UpdateTimestamp
    @Column(name = "updated_at")
    private LocalDateTime updatedAt;
}
