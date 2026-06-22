# ADR-002: Elección de JPA/Hibernate sobre JDBC Directo

## Estado
Aceptado

## Contexto
El backend requiere interactuar de forma continua con una base de datos relacional (PostgreSQL) para gestionar las entidades User, Producto y Categoria.

## Decisión
Se decide utilizar la especificación JPA junto a Hibernate como ORM e implementar Spring Data JPA para la abstracción de repositorios.

## Justificación
1. **Abstracción del Modelo Relacional**: Permite mapear directamente clases de Java (POJOs) a tablas de base de datos, facilitando el mantenimiento y legibilidad.
2. **Spring Data Repositories**: Ofrece generación automática de consultas por convención de nombres y soporte integrado para paginación y ordenamiento.
3. **Seguridad**: Previene de manera nativa vulnerabilidades de inyección SQL mediante el uso parametrizado en JPQL.

## Consecuencias
- **Positivo**: Aceleración del desarrollo de operaciones CRUD, paginación nativa y transaccionalidad declarativa con `@Transactional`.
- **Negativo**: Mayor consumo de recursos de memoria y overhead en consultas complejas (riesgo de problemas tipo N+1 si no se gestionan adecuadamente).
