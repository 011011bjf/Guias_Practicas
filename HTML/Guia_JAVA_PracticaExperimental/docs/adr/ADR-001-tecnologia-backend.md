# ADR-001: Elección de Spring Boot sobre Jakarta EE

## Estado
Aceptado

## Contexto
El sistema requiere el desarrollo de un backend REST seguro y escalable, con soporte de persistencia de datos (JPA/Hibernate) y control de acceso robusto (Spring Security, JWT).

## Decisión
Se decide adoptar Spring Boot en lugar de Jakarta EE puro.

## Justificación
1. **Ecosistema y Productividad**: Spring Boot ofrece configuraciones automáticas (auto-configurations) y componentes modulares (Starters) altamente probados, reduciendo significativamente el tiempo de desarrollo inicial.
2. **Seguridad y JWT**: Spring Security proporciona un framework maduro para la integración de filtros, autorización a nivel de métodos (con `@PreAuthorize`) y personalización stateless compatible con tokens JWT.
3. **Soporte de la Comunidad**: Cuenta con mayor documentación y herramientas de integración moderna en comparación con los servidores de aplicaciones Jakarta EE tradicionales.

## Consecuencias
- **Positivo**: Desarrollo rápido, menor boilerplate, fácil integración con OpenAPI (Springdoc).
- **Negativo**: Dependencia de un framework específico (vendor lock-in en el ecosistema Spring).
