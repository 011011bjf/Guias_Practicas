package edu.uteq.practicajava_spring_jwt.Repository;

import edu.uteq.practicajava_spring_jwt.Entity.Usuario;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface UserRepository  extends JpaRepository<Usuario,Long> {

    Optional<Usuario> findByCorreo(String correo);
    boolean existsByCorreo(String correo);

}
