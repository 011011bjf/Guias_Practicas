# Reporte de Cumplimiento de Requisitos — Backend Java (Spring Boot)
**Práctica Experimental N.° 2 — Aplicaciones Web [111]**

Este informe presenta un análisis detallado del estado de cumplimiento de los requisitos establecidos en el checklist [REQUISITOS_JAVA_NOTA_100.md](file:///d:/HTML/Guia_JAVA_PracticaExperimental/REQUISITOS_JAVA_NOTA_100.md) para el backend del proyecto.

---

## 📊 1. Resumen Ejecutivo de Cumplimiento

A nivel general, el proyecto posee una estructura arquitectónica sólida y limpia, implementando correctamente los patrones controladores, servicios, repositorios y entidades. Sin embargo, existen **hallazgos críticos** que comprometen la nota perfecta (100%) y algunos errores que se penalizan explícitamente en la rúbrica (como fallos en la ejecución de `mvn test` y dependencias/configuraciones incompletas).

### Resumen Numérico por Bloque

| Bloque | Descripción | Total Ítems | Cumplido | Parcial / Alternativa | No Cumplido | Estado General |
| :--- | :--- | :---: | :---: | :---: | :---: | :--- |
| **A** | Entorno y Configuración | 11 | 8 | 1 | 2 | ⚠️ Aceptable |
| **B** | Arquitectura y SOLID | 16 | 10 | 0 | 6 | ⚠️ Requiere Documentar |
| **C** | Autenticación JWT + Security | 41 | 35 | 4 | 2 | ⚠️ Bueno |
| **D** | CRUD con JPA/Hibernate | 35 | 23 | 2 | 10 | ⚠️ Pendiente Evidencias |
| **E** | Manejo de Excepciones | 10 | 7 | 1 | 2 | ⚠️ Detalle Crítico |
| **F** | Validación Bean Validation | 10 | 9 | 0 | 1 | ✅ Excelente |
| **G** | Seguridad OWASP | 15 | 10 | 2 | 3 | ⚠️ Aceptable |
| **H** | Configuración CORS | 6 | 4 | 1 | 1 | ⚠️ Vulnerabilidad Potencial |
| **I** | Swagger / OpenAPI | 6 | 6 | 0 | 0 | ✅ Completo |
| **J** | Tests Automatizados | 6 | 2 | 2 | 2 | ❌ Crítico (Fallo) |
| **K** | Architecture Decision Records | 7 | 5 | 0 | 2 | ⚠️ Faltan Enlaces |
| **L** | Tabla Comparativa | 5 | 0 | 0 | 5 | ℹ️ Deliberable Escrito |
| **M** | Calidad de Código | 10 | 8 | 0 | 2 | ✅ Bueno |
| **N** | Informe Escrito | 23 | 0 | 0 | 23 | ℹ️ Deliberable Escrito |
| **TOTAL** | | **201** | **127** | **13** | **61** | **63.2% de avance total** |

*Nota: Muchos de los ítems catalogados como "No Cumplido" corresponden a evidencias manuales (capturas de pantalla) o secciones del informe escrito (Bloques L y N).*

---

## 🔍 2. Análisis Detallado por Bloque

### Bloque A — Entorno y Configuración Inicial
* **Cumplido**: A1 (Java 25 >= 21), A2 (Maven via wrapper), A3 (estructura maven pom.xml), A4 (dependencias declaradas), A7 (Exclusiones de `.gitignore`), A8 (arranque sin errores con credenciales configuradas), A10 (Hibernate ddl-auto en dev).
* **Parcialmente Cumplido / Alternativa**:
  * **A6**: Las credenciales de BD no están hardcodeadas en clases Java, sino parametrizadas en `application.properties` consumiendo variables de entorno de `.env`. Sin embargo, la directiva solicita que estén en `application-dev.properties`.
* **No Cumplido**:
  * **A5 (Perfiles separados)**: No se han creado los archivos `application-dev.properties` ni `application-prod.properties`. Toda la configuración reside en el archivo base.
  * **A9 (Endpoint de Health)**: No hay configurado ningún endpoint en `/api/health` ni tampoco se incluye la dependencia `spring-boot-starter-actuator` para `/actuator/health`.
  * **A11 (Captura consola)**: Ítem manual pendiente.

### Bloque B — Arquitectura y Estructura del Proyecto
* **Cumplido**: B1 a B10 (Estructura de paquetes implementada según convención: `controller`, `service`, `service.impl`, `repository`, `model`, `dto.request`, `dto.response`, `security`, `exception`, `config`).
* **No Cumplido (Documentación)**:
  * **B11 a B16**: Requiere plasmar en el informe escrito los ejemplos concretos de la aplicación para cada principio SOLID (SRP, OCP, LSP, ISP, DIP). *El código base sí aplica estos principios mediante el uso de interfaces de servicio, separación de controladores y repositorios.*

### Bloque C — Autenticación con JWT y Spring Security
* **Cumplido**: C1-C2 (JPA User), C4 (UserDetailsImpl), C5-C7 (UserRepository), C8-C10 (JwtTokenProvider y firma con HS256), C12-C15 (Filtro JWT stateless), C16 (JwtAuthEntryPoint 401 en JSON), C17-C24 (SecurityConfig, CSRF disabled, sin WebSecurityConfigurerAdapter, JwtFilter en orden correcto), C25 (BCrypt strength 12), C26 (AuthenticationManager bean), C27-C32 (AuthServiceImpl y UserDetailsServiceImpl), C33-C35 (AuthController), C37 (Controller limpio), C39 (RegisterRequest validations), C41 (Seguridad hash).
* **Parcialmente Cumplido**:
  * **C3 (Enum Role)**: Para cumplir con la restricción del proyecto de no utilizar Enums, se implementó `Role.java` como una clase final de constantes de texto (`ROLE_USER`, `ROLE_ADMIN`).
  * **C11**: El método está nombrado como `getEmailFromToken(String token)` en lugar de `getUsernameFromToken`, aunque cumple exactamente la misma función (el username en este sistema es el email).
  * **C38 (LoginRequest)**: Le falta la validación `@Size(min=8)` en el campo de contraseña. Solo tiene `@NotBlank`.
  * **C40 (AuthResponse)**: Falta el campo de salida estático `type` ("Bearer"). Actualmente contiene `token`, `email`, `nombre` y `role`.
* **No Cumplido**:
  * **C36 (Logout)**: El controlador no expone el endpoint `POST /api/auth/logout`.

### Bloque D — CRUD Completo con JPA/Hibernate
* **Cumplido**: D1-D3 (Producto & Categoria JPA), D6-D10 (Repositorios, JPQL `@Query` parametrizado, sin concatenaciones SQL), D11-D13 (DTOs, PagedResponse), D15-D21 (ProductoServiceImpl, excepciones, mapeo, lógica de negocio en servicios), D22-D27 (ProductoController endpoints CRUD correctos), D28-D29 (Control de acceso en controlador y delegación al servicio).
* **Parcialmente Cumplido / Alternativa**:
  * **D4 (Auditoría JPA)**: En lugar de usar `@CreatedDate`, `@LastModifiedDate` y habilitar `@EnableJpaAuditing` en la configuración, se utilizaron las anotaciones nativas de Hibernate `@CreationTimestamp` y `@UpdateTimestamp`, lo cual es funcionalmente idéntico pero técnicamente diferente.
  * **D14 (Firmas de Servicio)**: Los métodos en `ProductoService` usan tipos primitivos (`int page`, `int size`) en lugar de recibir directamente un objeto `Pageable` en su firma.
* **No Cumplido**:
  * **D5 (Validaciones en Entidad)**: La clase de entidad `Producto.java` no tiene anotaciones de Bean Validation (`@NotNull`, `@NotBlank`) en sus atributos; solo posee las restricciones de base de datos relacional (`@Column(nullable = false)`).
  * **D30 a D35 (Capturas funcionales)**: Requiere recolectar las capturas de Postman del CRUD y del error 401.

### Bloque E — Manejo de Excepciones Centralizado
* **Cumplido**: E1 (GlobalExceptionHandler), E2 (ResourceNotFoundException 404), E3 (BadRequestException 400), E4 (Validation errors 400), E6 (AccessDeniedException 403), E7 (Exception genérica 500), E9 (Ocultación de Stack trace en producción).
* **Parcialmente Cumplido**:
  * **E8 (ErrorResponse)**: No incluye el campo `error`. El objeto contiene: `status` (int), `message` (String), `path` (String) y `timestamp` (LocalDateTime).
* **No Cumplido**:
  * **E5 (Manejo de AuthenticationException)**: `GlobalExceptionHandler` no captura de forma explícita `AuthenticationException`. Por lo tanto, si falla el login por credenciales incorrectas en `AuthController.login()`, la excepción se eleva y cae en el manejador genérico (`Exception.class`) devolviendo un error **500 Internal Server Error** en lugar de **401 Unauthorized**.
  * **E10 (Captura de error en informe)**: Pendiente de realización manual.

### Bloque F — Validación de Entradas (Bean Validation)
* **Cumplido**: F1-F4 (Anotaciones en controladores y DTOs), F6-F7 (validación precio/stock), F8 (mensajes en español), F9 (formato en JSON del error), F10 (validación backend garantizada).
* **No Cumplido**:
  * **F5 (Size en login)**: Faltaba la validación de tamaño en `LoginRequest` (tal como se mencionó en el ítem C38).

### Bloque G — Seguridad OWASP Aplicada
* **Cumplido**: G1 (Control de acceso por roles con `@PreAuthorize`), G3-G4 (criptografía robusta y secretos externos), G6-G7 (parametría SQL), G9 (sin stack traces en errores), G11-G12 (expiración de token y manejo de invalidez), G13 (API REST mitiga XSS por defecto al retornar JSON).
* **Parcialmente Cumplido / Alternativa**:
  * **G8 (Cabeceras de seguridad)**: Spring Security habilita por defecto las cabeceras `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff` y `HSTS`. Sin embargo, no se configuran explícitamente en el código.
  * **G14 (CORS Orígenes)**: Está parametrizado para leer el frontend desde `.env`, pero las cabeceras permiten comodines internos.
* **No Cumplido**:
  * **G2 (Acceso a recursos propios)**: No aplica / no se implementó validación de propiedad porque las entidades (Producto/Categoría) son globales al catálogo y no tienen un propietario `user_id`.
  * **G5 (HTTPS en Producción)**: Al no tener perfiles separados, no está definida la configuración obligatoria de HTTPS (`server.ssl`) para el perfil prod.
  * **G10 (mvn dependency:check)**: No se incluye el plugin de OWASP Dependency Check ni se ha corrido su reporte en el código.
  * **G15 (Documentación OWASP)**: Pendiente redactar esta sección del informe.

### Bloque H — Configuración CORS
* **Cumplido**: H1 (`CorsConfig`), H2 (allowedOrigins desde variables de entorno), H3 (allowedMethods con GET, POST, PUT, DELETE, OPTIONS), H5 (allowCredentials a true).
* **Parcialmente Cumplido**:
  * **H4 (allowedHeaders)**: Utiliza `.allowedHeaders("*")` en lugar de listar explícitamente `Authorization` y `Content-Type`.
* **No Cumplido**:
  * **H6 (CORS en Spring Security)**: La clase `SecurityConfig.java` no tiene configurada la llamada `.cors(cors -> ...)` en la `SecurityFilterChain`. Esto causará que Spring Security bloquee peticiones de tipo `OPTIONS` (preflight) o que no adjunte las cabeceras CORS en respuestas fallidas (como el error 401).

### Bloque I — Documentación con Swagger / OpenAPI
* **Cumplido**: I1 a I6 (Dependencias en `pom.xml`, Swagger UI accesible en ruta local, OpenAPIConfig estructurado, `@Operation` en endpoints, esquema de seguridad BearerJWT y rutas públicas permitidas).

### Bloque J — Tests Automatizados
* **Cumplido**: J4 (Tests unitarios con Mockito en `ProductoServiceTest` y `AuthServiceTest`).
* **Parcialmente Cumplido**:
  * **J1**: Los tests de integración de Auth cubren Login exitoso y ValidationError, pero no cubren el Registro exitoso ni el Login incorrecto (401).
  * **J2**: Se verifica `GET /api/productos/{id}` exitoso y sin token (401), pero no existe un test para probar la creación `POST con token válido -> HTTP 201`.
* **No Cumplido**:
  * **J3 y J5 (H2 en memoria)**: Los tests de integración de repositorios (`ProductoRepositoryTest`) no utilizan una base de datos H2 en memoria ni se configuró `application-test.properties`. Actualmente conectan directamente a la base de datos PostgreSQL de desarrollo leyendo el archivo `.env`.
  * **J6 (mvn test en verde)**: El comando de pruebas falla. El test de arranque de contexto genérico (`GuiaJavaPracticaExperimentalApplicationTests.contextLoads()`) no puede resolver la propiedad `${DB_URL}` porque la lógica para cargar el archivo `.env` reside únicamente en la clase `main` de la aplicación, la cual no es llamada por JUnit para preparar el contexto.

### Bloque K — Architecture Decision Records (ADR)
* **Cumplido**: K1-K3 (ADR-001 de Spring Boot), K4-K5 (ADR-002 de JPA/Hibernate), K7 (README.md con instrucciones claras).
* **No Cumplido**:
  * **K6 (Enlaces relativos)**: El archivo [README.md](file:///d:/HTML/Guia_JAVA_PracticaExperimental/README.md) no contiene enlaces ni referencias a los archivos de ADR creados en `docs/adr/`.

---

## 🚨 3. ERRORES QUE GARANTIZAN DESCUENTO DIRECTO DE NOTA (Sanciones)

Según la sección de advertencias en `REQUISITOS_JAVA_NOTA_100.md`:

| Error Crítico Penalizado | Estado en el Proyecto | Severidad | Razón y Ubicación |
| :--- | :---: | :---: | :--- |
| **1. Usar WebSecurityConfigurerAdapter** | ✅ Cumplido | - | Se usa la API de Spring Security 6.x moderna. |
| **2. CSRF habilitado en API REST** | ✅ Cumplido | - | Deshabilitado explícitamente en `SecurityConfig.java`. |
| **3. Contraseña almacenada sin hash** | ✅ Cumplido | - | Almacenamiento seguro por hash mediante `BCrypt`. |
| **4. Query SQL con concatenación** | ✅ Cumplido | - | Consultas JPQL seguras y parametrizadas. |
| **5. Clave JWT hardcodeada** | ✅ Cumplido | - | Se lee desde la propiedad `${JWT_SECRET}`. |
| **6. Entidades JPA retornadas directamente** | ✅ Cumplido | - | Se usan clases DTO de Request/Response dedicadas. |
| **7. Sin OncePerRequestFilter** | ✅ Cumplido | - | `JwtAuthenticationFilter` extiende de `OncePerRequestFilter`. |
| **8. Sin tabla comparativa de 8+ criterios** | ❌ **No Cumplido** | **Alta** | Falta la tabla comparativa técnica en el informe. |
| **9. Sin manejo de ResourceNotFoundException** | ✅ Cumplido | - | Manejado centralizadamente en `GlobalExceptionHandler`. |
| **10. Tests sin ejecutar o fallando** | ❌ **No Cumplido** | **Alta** | La ejecución de `mvn test` (o `.\mvnw.cmd test`) falla debido a un error de carga de propiedades en `GuiaJavaPracticaExperimentalApplicationTests`. |

---

## 🛠️ 4. Plan de Acción Recomendado para Obtener el 100% de la Nota

Si deseas corregir las desviaciones técnicas identificadas, te recomiendo realizar los siguientes pasos de forma secuencial:

### 1. Corregir los tests y agregar soporte para H2 en memoria (`J3`, `J5`, `J6`)
* Añadir la dependencia de H2 Database al `pom.xml`:
  ```xml
  <dependency>
      <groupId>com.h2database</groupId>
      <artifactId>h2</artifactId>
      <scope>test</scope>
  </dependency>
  ```
* Crear el archivo de configuración `src/test/resources/application-test.properties` definiendo la fuente de datos H2:
  ```properties
  spring.datasource.url=jdbc:h2:mem:testdb;DB_CLOSE_DELAY=-1
  spring.datasource.driver-class-name=org.h2.Driver
  spring.datasource.username=sa
  spring.datasource.password=
  spring.jpa.database-platform=org.hibernate.dialect.H2Dialect
  spring.jpa.hibernate.ddl-auto=create-drop
  app.jwt.secret=ClaveSuperSecretaDePruebasQueTieneMasDe256BitsDeLargoParaEvitarErroresEnFirma12345
  app.jwt.expiration-ms=3600000
  app.cors.allowed-origin=http://localhost:3000
  ```
* Decorar la clase `GuiaJavaPracticaExperimentalApplicationTests` con `@ActiveProfiles("test")` para que lea esa configuración y no intente resolver `${DB_URL}` ni necesite el `.env`.
* Remover `@AutoConfigureTestDatabase(replace = AutoConfigureTestDatabase.Replace.NONE)` de `ProductoRepositoryTest.java` y usar `@ActiveProfiles("test")` para aislar los tests de base de datos.

### 2. Implementar los endpoints y validaciones faltantes (`C36`, `C38`, `C40`, `E5`, `E8`)
* **Logout (`C36`)**: Crear un endpoint simple `POST /api/auth/logout` en `AuthController.java` que retorne `ResponseEntity.ok()` con un mensaje descriptivo explicando que al ser autenticación stateless con JWT, la invalidación del token debe ocurrir en el frontend (removiendo el token de las cookies o el localStorage).
* **Tamaño contraseña login (`C38` / `F5`)**: Añadir `@Size(min = 8, message = "La contraseña debe tener al menos 8 caracteres")` a `LoginRequest.java`.
* **Tipo token en respuesta (`C40`)**: Añadir un campo `private final String type = "Bearer";` (o campo equivalente) en `AuthResponse.java`.
* **Manejo de errores de credenciales (`E5`)**: En `GlobalExceptionHandler.java`, añadir un `@ExceptionHandler` para `AuthenticationException` y retornar HTTP 401 en JSON.
* **Campo `error` en ErrorResponse (`E8`)**: Modificar `ErrorResponse.java` para incluir la propiedad `private String error;` y setearla en el Handler global con el nombre del estado (ej: `"Not Found"` para 404).

### 3. Configurar CORS correctamente en la seguridad (`H6`)
* Modificar `SecurityConfig.java` para llamar explícitamente a `.cors()` de la siguiente forma:
  ```java
  http.csrf(AbstractHttpConfigurer::disable)
      .cors(cors -> cors.configurationSource(request -> {
          org.springframework.web.cors.CorsConfiguration config = new org.springframework.web.cors.CorsConfiguration();
          config.setAllowedOrigins(java.util.List.of(System.getProperty("CORS_ALLOWED_ORIGIN", "*")));
          config.setAllowedMethods(java.util.List.of("GET", "POST", "PUT", "DELETE", "OPTIONS"));
          config.setAllowedHeaders(java.util.List.of("Authorization", "Content-Type"));
          config.setAllowCredentials(true);
          return config;
      }))
  ```

### 4. Completar los ADR y el README (`K6`)
* Añadir una sección de **"Arquitectura de Decisiones"** al final del [README.md](file:///d:/HTML/Guia_JAVA_PracticaExperimental/README.md) y enlazar los dos ADRs:
  * [ADR-001: Elección de Spring Boot sobre Jakarta EE](file:///d:/HTML/Guia_JAVA_PracticaExperimental/docs/adr/ADR-001-tecnologia-backend.md)
  * [ADR-002: Elección de JPA/Hibernate sobre JDBC Directo](file:///d:/HTML/Guia_JAVA_PracticaExperimental/docs/adr/ADR-002-estrategia-bd.md)
