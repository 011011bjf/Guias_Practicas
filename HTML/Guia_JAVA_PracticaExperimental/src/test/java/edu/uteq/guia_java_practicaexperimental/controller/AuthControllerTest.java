package edu.uteq.guia_java_practicaexperimental.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import edu.uteq.guia_java_practicaexperimental.controller.auth.AuthController;
import edu.uteq.guia_java_practicaexperimental.dto.request.LoginRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.AuthResponse;
import edu.uteq.guia_java_practicaexperimental.security.jwt.JwtAuthEntryPoint;
import edu.uteq.guia_java_practicaexperimental.security.jwt.JwtTokenProvider;
import edu.uteq.guia_java_practicaexperimental.security.service.UserDetailsServiceImpl;
import edu.uteq.guia_java_practicaexperimental.service.AuthService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.webmvc.test.autoconfigure.WebMvcTest;
import org.springframework.test.context.bean.override.mockito.MockitoBean;
import org.springframework.http.MediaType;
import org.springframework.test.web.servlet.MockMvc;

import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.when;
import static org.springframework.security.test.web.servlet.request.SecurityMockMvcRequestPostProcessors.csrf;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.post;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

import org.springframework.context.annotation.Import;
import edu.uteq.guia_java_practicaexperimental.config.security.SecurityConfig;
import edu.uteq.guia_java_practicaexperimental.config.security.PasswordEncoderConfig;

@WebMvcTest(AuthController.class)
@Import({SecurityConfig.class, PasswordEncoderConfig.class})
public class AuthControllerTest {

    @Autowired
    private MockMvc mockMvc;

    private final ObjectMapper objectMapper = new ObjectMapper();

    @MockitoBean
    private AuthService authService;

    @MockitoBean
    private JwtTokenProvider jwtTokenProvider;

    @MockitoBean
    private UserDetailsServiceImpl userDetailsService;

    @MockitoBean
    private JwtAuthEntryPoint jwtAuthEntryPoint;

    @Test
    public void testLogin_Success() throws Exception {
        LoginRequest request = LoginRequest.builder()
                .email("user@test.com")
                .password("password123")
                .build();

        AuthResponse response = AuthResponse.builder()
                .token("jwt.token.value")
                .email("user@test.com")
                .nombre("Test User")
                .role("ROLE_USER")
                .build();

        when(authService.login(any(LoginRequest.class))).thenReturn(response);

        mockMvc.perform(post("/api/auth/login")
                        .with(csrf())
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(objectMapper.writeValueAsString(request)))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.token").value("jwt.token.value"))
                .andExpect(jsonPath("$.email").value("user@test.com"))
                .andExpect(jsonPath("$.nombre").value("Test User"))
                .andExpect(jsonPath("$.role").value("ROLE_USER"));
    }

    @Test
    public void testLogin_ValidationError() throws Exception {
        LoginRequest request = LoginRequest.builder()
                .email("invalid-email")
                .password("")
                .build();

        mockMvc.perform(post("/api/auth/login")
                        .with(csrf())
                        .contentType(MediaType.APPLICATION_JSON)
                        .content(objectMapper.writeValueAsString(request)))
                .andExpect(status().isBadRequest());
    }
}
