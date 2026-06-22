# ADR-001: Selección de PHP/Laravel como Framework Backend

## Fecha
2026-06-20

## Estado
Aceptado

## Contexto
Se requiere construir un backend robusto para el proyecto de fin de ciclo que proporcione autenticación segura, enrutamiento rápido, manejo de sesiones stateless y stateful, validación estricta de datos y una arquitectura desacoplada. Adicionalmente, el mercado local de desarrollo (Ecuador, provincia de Los Ríos) demanda soluciones que puedan alojarse con un costo mínimo de infraestructura sin sacrificar seguridad.

## Decisión
Se selecciona **PHP 8.2+ con Laravel** como la tecnología y framework backend principal para el desarrollo.

Esta decisión se fundamenta en los siguientes criterios técnicos objetivos:
1. **Velocidad de Desarrollo (Time-to-Market):** Laravel proporciona scaffolding nativo para enrutamiento, validación y gestión de migraciones, agilizando enormemente los tiempos de desarrollo.
2. **Costo de Alojamiento en Ecuador:** Las aplicaciones PHP se despliegan en hostings compartidos de bajo costo ($3-$10 al mes), a diferencia de frameworks como Java/Spring Boot o ASP.NET Core que requieren servidores VPS dedicados con mayor memoria RAM ($15-$25 al mes).
3. **Análisis Estático Nativo:** Capacidad de integrar **PHPStan** (a través de Larastan) a nivel de rigor 5 para garantizar la solidez de tipos y prevenir errores comunes en tiempo de compilación/desarrollo.

## Alternativas Rechazadas
1. **PHP Puro (sin framework):** Ofrece máxima velocidad bruta de ejecución, pero requiere escribir manualmente toda la infraestructura de enrutamiento, inyección de dependencias y controles de seguridad (CSRF, XSS, Timing Attacks), lo que eleva el riesgo de vulnerabilidades y los tiempos de entrega.
2. **Java / Spring Boot:** Proporciona un tipado estático muy fuerte y excelente rendimiento, pero su curva de aprendizaje es más elevada y el costo del hosting (VPS dedicado de mínimo 1GB RAM) es prohibitivo para las PYMEs de la región de Los Ríos.
3. **ASP.NET Core (C#):** Altamente estructurado y eficiente, pero la disponibilidad de desarrolladores locales y el costo de infraestructura especializada superan la viabilidad comercial del proyecto.

## Consecuencias
* **Positivas:**
  * Reducción drástica en tiempos de entrega y prototipado rápido.
  * Mitigación nativa de riesgos de seguridad (cabeceras de seguridad automáticas, encriptación nativa).
  * Compatibilidad con hostings compartidos de bajo costo en el mercado ecuatoriano.
* **Negativas:**
  * Mayor acoplamiento a las convenciones y helpers de Laravel.
