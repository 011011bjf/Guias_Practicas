package edu.uteq.guia_java_practicaexperimental.service;

import edu.uteq.guia_java_practicaexperimental.dto.request.LoginRequest;
import edu.uteq.guia_java_practicaexperimental.dto.request.RegisterRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.AuthResponse;
import edu.uteq.guia_java_practicaexperimental.exception.BadRequestException;
import edu.uteq.guia_java_practicaexperimental.model.Role;
import edu.uteq.guia_java_practicaexperimental.model.User;
import edu.uteq.guia_java_practicaexperimental.repository.UserRepository;
import edu.uteq.guia_java_practicaexperimental.security.jwt.JwtTokenProvider;
import edu.uteq.guia_java_practicaexperimental.security.service.UserDetailsImpl;
import edu.uteq.guia_java_practicaexperimental.service.impl.AuthServiceImpl;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.crypto.password.PasswordEncoder;

import java.util.Collections;

import static org.assertj.core.api.Assertions.assertThat;
import static org.assertj.core.api.Assertions.assertThatThrownBy;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
public class AuthServiceTest {

    @Mock
    private AuthenticationManager authenticationManager;

    @Mock
    private UserRepository userRepository;

    @Mock
    private PasswordEncoder passwordEncoder;

    @Mock
    private JwtTokenProvider tokenProvider;

    @InjectMocks
    private AuthServiceImpl authService;

    @Test
    public void testLogin() {
        LoginRequest request = LoginRequest.builder()
                .email("user@test.com")
                .password("password123")
                .build();

        Authentication authentication = mock(Authentication.class);
        UserDetailsImpl userDetails = new UserDetailsImpl(
                1L, "Test User", "user@test.com", "encodedPassword",
                Collections.singletonList(new SimpleGrantedAuthority(Role.ROLE_USER))
        );

        when(authenticationManager.authenticate(any(UsernamePasswordAuthenticationToken.class)))
                .thenReturn(authentication);
        when(authentication.getPrincipal()).thenReturn(userDetails);
        when(tokenProvider.generateToken(authentication)).thenReturn("mockedJwtToken");

        AuthResponse response = authService.login(request);

        assertThat(response.getToken()).isEqualTo("mockedJwtToken");
        assertThat(response.getEmail()).isEqualTo("user@test.com");
        assertThat(response.getNombre()).isEqualTo("Test User");
        assertThat(response.getRole()).isEqualTo(Role.ROLE_USER);
    }

    @Test
    public void testRegister_EmailAlreadyExists() {
        RegisterRequest request = RegisterRequest.builder()
                .nombre("Test")
                .email("exists@test.com")
                .password("password")
                .build();

        when(userRepository.existsByEmail("exists@test.com")).thenReturn(true);

        assertThatThrownBy(() -> authService.register(request))
                .isInstanceOf(BadRequestException.class)
                .hasMessageContaining("El email ya está registrado");

        verify(userRepository, never()).save(any(User.class));
    }
}
