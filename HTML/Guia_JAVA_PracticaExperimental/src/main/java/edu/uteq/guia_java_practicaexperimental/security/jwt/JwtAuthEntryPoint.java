package edu.uteq.guia_java_practicaexperimental.security.jwt;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.http.MediaType;
import org.springframework.security.core.AuthenticationException;
import org.springframework.security.web.AuthenticationEntryPoint;
import org.springframework.stereotype.Component;

import java.io.IOException;
import java.time.LocalDateTime;

@Component
public class JwtAuthEntryPoint implements AuthenticationEntryPoint {
    private static final Logger logger = LoggerFactory.getLogger(JwtAuthEntryPoint.class);

    @Override
    public void commence(HttpServletRequest request, HttpServletResponse response, AuthenticationException authException)
            throws IOException, ServletException {
        logger.error("Unauthorized error: {}", authException.getMessage());

        response.setContentType(MediaType.APPLICATION_JSON_VALUE);
        response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);

        String json = String.format(
            "{\"status\":%d,\"message\":\"%s\",\"path\":\"%s\",\"timestamp\":\"%s\"}",
            HttpServletResponse.SC_UNAUTHORIZED,
            "No autorizado: " + authException.getMessage().replace("\"", "\\\""),
            request.getRequestURI(),
            LocalDateTime.now().toString()
        );
        response.getWriter().write(json);
    }
}
