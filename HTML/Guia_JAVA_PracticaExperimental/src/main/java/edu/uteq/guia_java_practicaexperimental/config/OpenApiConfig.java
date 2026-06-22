package edu.uteq.guia_java_practicaexperimental.config;

import io.swagger.v3.oas.models.Components;
import io.swagger.v3.oas.models.OpenAPI;
import io.swagger.v3.oas.models.info.Info;
import io.swagger.v3.oas.models.security.SecurityRequirement;
import io.swagger.v3.oas.models.security.SecurityScheme;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

@Configuration
public class OpenApiConfig {

    @Bean
    public OpenAPI customOpenAPI() {
        return new OpenAPI()
                .info(new Info()
                        .title("Guía Práctica Experimental API")
                        .version("1.0.0")
                        .description("API REST para la gestión de productos y categorías con autenticación JWT."))
                .addSecurityItem(new SecurityRequirement().addList("BearerJWT"))
                .components(new Components()
                        .addSecuritySchemes("BearerJWT",
                                new SecurityScheme()
                                        .name("BearerJWT")
                                        .type(SecurityScheme.Type.HTTP)
                                        .scheme("bearer")
                                        .bearerFormat("JWT")
                                        .description("Ingrese el token JWT obtenido del endpoint de inicio de sesión.")));
    }
}
