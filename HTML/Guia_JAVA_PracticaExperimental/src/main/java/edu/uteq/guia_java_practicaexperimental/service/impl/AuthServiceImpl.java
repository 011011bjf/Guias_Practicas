package edu.uteq.guia_java_practicaexperimental.service.impl;

import edu.uteq.guia_java_practicaexperimental.dto.request.LoginRequest;
import edu.uteq.guia_java_practicaexperimental.dto.request.RegisterRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.AuthResponse;
import edu.uteq.guia_java_practicaexperimental.exception.BadRequestException;
import edu.uteq.guia_java_practicaexperimental.model.Role;
import edu.uteq.guia_java_practicaexperimental.model.Roles;
import edu.uteq.guia_java_practicaexperimental.model.User;
import edu.uteq.guia_java_practicaexperimental.repository.UserRepository;
import edu.uteq.guia_java_practicaexperimental.security.jwt.JwtTokenProvider;
import edu.uteq.guia_java_practicaexperimental.security.service.UserDetailsImpl;
import edu.uteq.guia_java_practicaexperimental.service.AuthService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

@Service
public class AuthServiceImpl implements AuthService {

    @Autowired
    private AuthenticationManager authenticationManager;

    @Autowired
    private UserRepository userRepository;

    @Autowired
    private PasswordEncoder passwordEncoder;

    @Autowired
    private JwtTokenProvider tokenProvider;

    @Override
    @Transactional
    public AuthResponse login(LoginRequest request) {
        Authentication authentication = authenticationManager.authenticate(
                new UsernamePasswordAuthenticationToken(request.getEmail(), request.getPassword())
        );

        SecurityContextHolder.getContext().setAuthentication(authentication);
        String jwt = tokenProvider.generateToken(authentication);

        UserDetailsImpl userDetails = (UserDetailsImpl) authentication.getPrincipal();
        String role = userDetails.getAuthorities().stream()
                .map(item -> item.getAuthority())
                .findFirst()
                .orElse(Roles.ADMIN);

        return AuthResponse.builder()
                .token(jwt)
                .email(userDetails.getEmail())
                .nombre(userDetails.getName())
                .role(role)
                .build();
    }

    @Override
    @Transactional
    public AuthResponse register(RegisterRequest request) {
        if (userRepository.existsByEmail(request.getEmail())) {
            throw new BadRequestException("Error: El email ya está registrado");
        }

        // Si es el primer usuario, se crea como ADMIN para facilitar pruebas
        String role = userRepository.count() == 0 ? Role.ROLE_ADMIN : Role.ROLE_USER;

        User user = User.builder()
                .name(request.getNombre())
                .email(request.getEmail())
                .password(passwordEncoder.encode(request.getPassword()))
                .role(role)
                .build();

        userRepository.save(user);

        // Autenticar automáticamente tras registro
        Authentication authentication = authenticationManager.authenticate(
                new UsernamePasswordAuthenticationToken(request.getEmail(), request.getPassword())
        );

        SecurityContextHolder.getContext().setAuthentication(authentication);
        String jwt = tokenProvider.generateToken(authentication);

        return AuthResponse.builder()
                .token(jwt)
                .email(user.getEmail())
                .nombre(user.getName())
                .role(user.getRole())
                .build();
    }
}
