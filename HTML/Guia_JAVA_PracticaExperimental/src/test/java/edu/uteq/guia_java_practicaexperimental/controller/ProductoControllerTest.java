package edu.uteq.guia_java_practicaexperimental.controller;

import com.fasterxml.jackson.databind.ObjectMapper;
import edu.uteq.guia_java_practicaexperimental.controller.api.ProductoController;
import edu.uteq.guia_java_practicaexperimental.dto.response.ProductoResponse;
import edu.uteq.guia_java_practicaexperimental.security.jwt.JwtAuthEntryPoint;
import edu.uteq.guia_java_practicaexperimental.security.jwt.JwtTokenProvider;
import edu.uteq.guia_java_practicaexperimental.security.service.UserDetailsServiceImpl;
import edu.uteq.guia_java_practicaexperimental.service.ProductoService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.webmvc.test.autoconfigure.WebMvcTest;
import org.springframework.test.context.bean.override.mockito.MockitoBean;
import org.springframework.http.MediaType;
import org.springframework.security.test.context.support.WithMockUser;
import org.springframework.test.web.servlet.MockMvc;

import java.math.BigDecimal;

import static org.mockito.Mockito.when;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

import org.springframework.context.annotation.Import;
import edu.uteq.guia_java_practicaexperimental.config.security.SecurityConfig;
import edu.uteq.guia_java_practicaexperimental.config.security.PasswordEncoderConfig;

@WebMvcTest(ProductoController.class)
@Import({SecurityConfig.class, PasswordEncoderConfig.class})
public class ProductoControllerTest {

    @Autowired
    private MockMvc mockMvc;

    @MockitoBean
    private ProductoService productoService;

    @MockitoBean
    private JwtTokenProvider jwtTokenProvider;

    @MockitoBean
    private UserDetailsServiceImpl userDetailsService;

    @MockitoBean
    private JwtAuthEntryPoint jwtAuthEntryPoint;

    @org.junit.jupiter.api.BeforeEach
    public void setUp() throws Exception {
        org.mockito.Mockito.doAnswer(invocation -> {
            jakarta.servlet.http.HttpServletResponse response = invocation.getArgument(1);
            response.setStatus(401);
            return null;
        }).when(jwtAuthEntryPoint).commence(org.mockito.ArgumentMatchers.any(), org.mockito.ArgumentMatchers.any(), org.mockito.ArgumentMatchers.any());
    }

    @Test
    @WithMockUser(username = "user@test.com", roles = "USER")
    public void testGetProductoById_Success() throws Exception {
        ProductoResponse response = ProductoResponse.builder()
                .id(1L)
                .nombre("Teclado Mecánico")
                .precio(new BigDecimal("85.50"))
                .stock(5)
                .categoriaId(2L)
                .categoriaNombre("Periféricos")
                .build();

        when(productoService.findById(1L)).thenReturn(response);

        mockMvc.perform(get("/api/productos/1")
                        .contentType(MediaType.APPLICATION_JSON))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$.id").value(1))
                .andExpect(jsonPath("$.nombre").value("Teclado Mecánico"))
                .andExpect(jsonPath("$.precio").value(85.50))
                .andExpect(jsonPath("$.categoriaNombre").value("Periféricos"));
    }

    @Test
    public void testGetProductoById_Unauthorized() throws Exception {
        mockMvc.perform(get("/api/productos/1")
                        .contentType(MediaType.APPLICATION_JSON))
                .andExpect(status().isUnauthorized());
    }
}
