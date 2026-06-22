package edu.uteq.guia_java_practicaexperimental.controller;

import edu.uteq.guia_java_practicaexperimental.controller.api.CategoriaController;
import edu.uteq.guia_java_practicaexperimental.dto.response.CategoriaResponse;
import edu.uteq.guia_java_practicaexperimental.security.jwt.JwtAuthEntryPoint;
import edu.uteq.guia_java_practicaexperimental.security.jwt.JwtTokenProvider;
import edu.uteq.guia_java_practicaexperimental.security.service.UserDetailsServiceImpl;
import edu.uteq.guia_java_practicaexperimental.service.CategoriaService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.webmvc.test.autoconfigure.WebMvcTest;
import org.springframework.test.context.bean.override.mockito.MockitoBean;
import org.springframework.http.MediaType;
import org.springframework.security.test.context.support.WithMockUser;
import org.springframework.test.web.servlet.MockMvc;

import java.util.List;

import static org.mockito.Mockito.when;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

import org.springframework.context.annotation.Import;
import edu.uteq.guia_java_practicaexperimental.config.security.SecurityConfig;
import edu.uteq.guia_java_practicaexperimental.config.security.PasswordEncoderConfig;

@WebMvcTest(CategoriaController.class)
@Import({SecurityConfig.class, PasswordEncoderConfig.class})
public class CategoriaControllerTest {

    @Autowired
    private MockMvc mockMvc;

    @MockitoBean
    private CategoriaService categoriaService;

    @MockitoBean
    private JwtTokenProvider jwtTokenProvider;

    @MockitoBean
    private UserDetailsServiceImpl userDetailsService;

    @MockitoBean
    private JwtAuthEntryPoint jwtAuthEntryPoint;

    @Test
    @WithMockUser(username = "user@test.com", roles = "USER")
    public void testGetAllCategorias() throws Exception {
        CategoriaResponse cat = CategoriaResponse.builder()
                .id(1L)
                .nombre("Electrónica")
                .descripcion("Smartphones, laptops, etc")
                .build();

        when(categoriaService.findAll()).thenReturn(List.of(cat));

        mockMvc.perform(get("/api/categorias")
                        .contentType(MediaType.APPLICATION_JSON))
                .andExpect(status().isOk())
                .andExpect(jsonPath("$[0].id").value(1))
                .andExpect(jsonPath("$[0].nombre").value("Electrónica"))
                .andExpect(jsonPath("$[0].descripcion").value("Smartphones, laptops, etc"));
    }
}
