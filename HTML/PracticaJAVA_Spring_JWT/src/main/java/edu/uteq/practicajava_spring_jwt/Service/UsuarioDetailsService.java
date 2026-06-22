package edu.uteq.practicajava_spring_jwt.Service;

import edu.uteq.practicajava_spring_jwt.Entity.Usuario;
import edu.uteq.practicajava_spring_jwt.Repository.UserRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.User;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
public class UsuarioDetailsService implements UserDetailsService {

    private final UserRepository userRepository;

    @Override
    public UserDetails loadUserByUsername(String email) throws UsernameNotFoundException {
        Usuario usuario = userRepository.findByCorreo(email)
                .orElseThrow(() -> new UsernameNotFoundException("Usuario no encontrado con el correo: " + email));

        var authorities = usuario.getRoles().stream()
                .map(rol -> new SimpleGrantedAuthority("ROLE_" + rol.getNombreRol()))
                .collect(Collectors.toList());

        return User.builder()
                .username(usuario.getCorreo())
                .password(usuario.getContrasenaHash())
                .authorities(authorities)
                .disabled(!usuario.getEstadoCuenta())
                .build();
    }
}
