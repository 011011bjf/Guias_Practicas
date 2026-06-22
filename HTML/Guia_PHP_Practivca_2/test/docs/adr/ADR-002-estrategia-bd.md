# ADR-002: Estrategia de Persistencia y Acceso a Base de Datos (PDO + Patrón Repository vs Eloquent Puro)

## Fecha
2026-06-20

## Estado
Aceptado

## Contexto
El backend necesita interactuar con una base de datos MySQL de forma segura y eficiente. Debemos garantizar la prevención absoluta de vulnerabilidades de Inyección SQL (OWASP A03) y proveer una arquitectura desacoplada donde el cambio de motor de base de datos o de framework no afecte la lógica de negocio del controlador o servicio.

## Decisión
Se adopta el **Patrón Repository con PDO nativo (Prepared Statements reales)** en lugar de usar **Eloquent ORM puro** directamente en los controladores.

Razones técnicas para esta elección:
1. **Prepared Statements Reales (PDO):** PDO permite configurar `PDO::ATTR_EMULATE_PREPARES => false`, garantizando que la base de datos realice la compilación previa de la consulta. Esto ofrece inmunidad total contra Inyecciones SQL frente a las abstracciones del ORM que pueden emular preparaciones.
2. **Cumplimiento de la Rúbrica:** La guía de la práctica exige el uso de PDO explícito con prepared statements y binding de parámetros (`bindValue` / `bindParam`).
3. **Desacoplamiento Estricto:** La capa de servicios (`AuthService`, `ProductoService`) y controladores solo interactúan con interfaces de repositorio (`ProductoRepositoryInterface`), aislando completamente la infraestructura SQL.
4. **Hidratación Gradual:** Se aprovecha el método `hydrate()` de Eloquent para transformar los resultados del fetch nativo de PDO a objetos Model en la capa final del repositorio, manteniendo la compatibilidad con las vistas Blade y enrutadores sin romper la separación de responsabilidades.

## Alternativas Rechazadas
1. **Eloquent ORM Puro:** Aunque simplifica el desarrollo con sintaxis ActiveRecord (ej. `Producto::all()`), acopla directamente los controladores a la base de datos de Laravel, dificultando los tests unitarios independientes y el cumplimiento estricto de uso de PDO puro exigido.
2. **SQL Puro sin Patrón Repository:** Escribir consultas SQL PDO directamente dentro de los controladores viola el principio de responsabilidad única (SRP), dificultando la mantenibilidad del código.

## Consecuencias
* **Positivas:**
  * **Inmunidad contra inyección SQL:** Al usar prepared statements reales con tipos de datos estrictos en PDO.
  * **Testabilidad:** Permite mockear las interfaces del repositorio en pruebas unitarias de servicios sin requerir una base de datos activa.
  * **Flexibilidad:** Capacidad de cambiar el adaptador concreto (ej. migrar de PDO MySQL a Eloquent o MongoDB) implementando la misma interfaz.
* **Negativas:**
  * Incremento de código repetitivo (Boilerplate) al tener que definir interfaces y clases concretas de implementación para cada entidad.
