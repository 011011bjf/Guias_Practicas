# ✅ CHECKLIST COMPLETO — BACKEND JAVA CON SPRING BOOT 3.x
## Requisitos para obtener el 100% de la nota
### Práctica Experimental N.° 2 — Aplicaciones Web [111]

---

> Marca cada ítem conforme lo completes. El docente verificará el repositorio,
> el código fuente, las capturas y la tabla comparativa.
> **Todos** los ítems deben estar cumplidos para nota perfecta.

---

## BLOQUE A — ENTORNO Y CONFIGURACIÓN INICIAL
*Criterio de verificación oficial: aplicación arranca y responde HTTP 200*

- [ ] **A1.** JDK 21 LTS instalado y verificable con `java -version` (versión 21.x)
- [ ] **A2.** Maven 3.9 instalado y verificable con `mvn -version`
- [ ] **A3.** Proyecto generado desde Spring Initializr (`https://start.spring.io`) — evidencia del `pom.xml`
- [ ] **A4.** Dependencias declaradas en `pom.xml`:
  - `spring-boot-starter-web`
  - `spring-boot-starter-security`
  - `spring-boot-starter-data-jpa`
  - `spring-boot-starter-validation`
  - `mysql-connector-j` (o `postgresql`)
  - `jjwt-api` + `jjwt-impl` + `jjwt-jackson` (librería JWT)
  - `lombok` (opcional pero recomendado)
  - `springdoc-openapi-starter-webmvc-ui` (Swagger UI)
- [ ] **A5.** Perfiles separados: `application.properties`, `application-dev.properties`, `application-prod.properties`
- [ ] **A6.** Credenciales de BD en `application-dev.properties` — **nunca hardcodeadas** en clases Java
- [ ] **A7.** `.gitignore` excluye `target/`, `*.class`, variables de entorno con credenciales
- [ ] **A8.** Aplicación arranca sin errores: `mvn spring-boot:run` o `java -jar app.jar`
- [ ] **A9.** Endpoint de health accesible: `GET /api/health` o `/actuator/health` → HTTP 200
- [ ] **A10.** Base de datos conectada y tablas creadas por Hibernate (`ddl-auto=update` en dev)
- [ ] **A11.** Captura de pantalla de la consola mostrando "Started BackendApplication" en el informe

---

## BLOQUE B — ARQUITECTURA Y ESTRUCTURA DEL PROYECTO
*El docente verificará la separación de capas y aplicación de SOLID*

### B.1 Estructura de paquetes obligatoria
- [ ] **B1.** Paquete `controller` — solo recibe requests, delega a services, retorna DTOs
- [ ] **B2.** Paquete `service` — interfaces de servicio (contratos)
- [ ] **B3.** Paquete `service.impl` — implementaciones concretas de los servicios
- [ ] **B4.** Paquete `repository` — interfaces JPA (extienden `JpaRepository`)
- [ ] **B5.** Paquete `model` — entidades JPA (`@Entity`)
- [ ] **B6.** Paquete `dto.request` — objetos de entrada (lo que llega del cliente)
- [ ] **B7.** Paquete `dto.response` — objetos de salida (lo que se devuelve al cliente)
- [ ] **B8.** Paquete `security` — toda la configuración de Spring Security + JWT
- [ ] **B9.** Paquete `exception` — excepciones custom + handler global
- [ ] **B10.** Paquete `config` — beans de configuración (CORS, OpenAPI, etc.)

### B.2 Principios SOLID aplicados (documentar en el informe)
- [ ] **B11.** **SRP** — cada clase tiene una sola responsabilidad: Controller no accede a BD, Service no construye HTTP responses
- [ ] **B12.** **OCP** — servicios dependen de interfaces, no de implementaciones concretas
- [ ] **B13.** **LSP** — las implementaciones de `AuthService`, `ProductoService` son sustituibles por sus interfaces
- [ ] **B14.** **ISP** — interfaces pequeñas y específicas (no una interfaz `SuperService` con 20 métodos)
- [ ] **B15.** **DIP** — controladores inyectan interfaces (`@Autowired AuthService authService`), nunca `new AuthServiceImpl()`
- [ ] **B16.** Ejemplo concreto de cada principio SOLID **documentado en el informe** con fragmento de código del PFC

---

## BLOQUE C — AUTENTICACIÓN CON JWT Y SPRING SECURITY (OE1 + OE3)
*El mismo módulo de autenticación que en PHP, implementado en Java*

### C.1 Entidades y modelos de usuario
- [ ] **C1.** Entidad `User.java` con anotaciones JPA: `@Entity`, `@Table(name="users")`, `@Id`, `@GeneratedValue`
- [ ] **C2.** Campos mínimos: `id`, `name`, `email` (único: `@Column(unique=true)`), `password`, `role`, `createdAt`
- [ ] **C3.** Enum `Role.java` con valores: `ROLE_USER`, `ROLE_ADMIN`
- [ ] **C4.** `UserDetailsImpl.java` implementa `UserDetails` de Spring Security (getAuthorities, getPassword, etc.)

### C.2 Repositorio de usuarios
- [ ] **C5.** `UserRepository.java` extiende `JpaRepository<User, Long>`
- [ ] **C6.** Método `Optional<User> findByEmail(String email)` declarado en la interfaz
- [ ] **C7.** Método `boolean existsByEmail(String email)` para validar email único en registro

### C.3 Generación y validación de JWT
- [ ] **C8.** Clase `JwtTokenProvider.java` con método `generateToken(Authentication auth)` → retorna String JWT
- [ ] **C9.** JWT firmado con clave secreta de mínimo 256 bits (HS256) — clave en `application.properties`, no en el código
- [ ] **C10.** Método `validateToken(String token)` → retorna `boolean`, captura `JwtException` y `IllegalArgumentException`
- [ ] **C11.** Método `getUsernameFromToken(String token)` → extrae el subject (email) del JWT
- [ ] **C12.** Expiración del token configurada (ej: 24 horas) y leída desde `application.properties`
- [ ] **C13.** Clase `JwtAuthenticationFilter.java` extiende `OncePerRequestFilter`
- [ ] **C14.** El filtro extrae el token del header `Authorization: Bearer <token>`
- [ ] **C15.** El filtro valida el token y setea el `SecurityContextHolder` si es válido
- [ ] **C16.** Clase `JwtAuthEntryPoint.java` implementa `AuthenticationEntryPoint` → retorna HTTP 401 en JSON cuando no hay token

### C.4 Configuración de Spring Security
- [ ] **C17.** Clase `SecurityConfig.java` con `@Configuration` + `@EnableWebSecurity`
- [ ] **C18.** Bean `SecurityFilterChain` configurado (no usar el deprecated `WebSecurityConfigurerAdapter`)
- [ ] **C19.** CSRF **deshabilitado** para API REST stateless: `.csrf(csrf -> csrf.disable())`
- [ ] **C20.** Sesiones stateless: `.sessionManagement(s -> s.sessionCreationPolicy(STATELESS))`
- [ ] **C21.** Rutas públicas declaradas: `/api/auth/**` permitidas sin token
- [ ] **C22.** Todas las demás rutas requieren autenticación: `.anyRequest().authenticated()`
- [ ] **C23.** `JwtAuthenticationFilter` registrado antes de `UsernamePasswordAuthenticationFilter`
- [ ] **C24.** `JwtAuthEntryPoint` registrado como `authenticationEntryPoint`
- [ ] **C25.** Bean `PasswordEncoder` de tipo `BCryptPasswordEncoder` con strength 12
- [ ] **C26.** Bean `AuthenticationManager` expuesto como `@Bean` para usarlo en el servicio de auth

### C.5 Servicio de autenticación
- [ ] **C27.** Interfaz `AuthService.java` con métodos: `login(LoginRequest)`, `register(RegisterRequest)`
- [ ] **C28.** `AuthServiceImpl.java` implementa `AuthService`
- [ ] **C29.** **Registro:** validar email no existe → encodear password con `BCryptPasswordEncoder` → guardar usuario → retornar `AuthResponse`
- [ ] **C30.** **Login:** `authenticationManager.authenticate()` → si válido → `generateToken()` → retornar `AuthResponse` con el JWT
- [ ] **C31.** Contraseña **nunca** almacenada en texto plano — solo el hash BCrypt
- [ ] **C32.** `UserDetailsService` implementado con `loadUserByUsername(email)` que busca en BD

### C.6 Controlador de autenticación
- [ ] **C33.** Clase `AuthController.java` con `@RestController` + `@RequestMapping("/api/auth")`
- [ ] **C34.** `POST /api/auth/register` → `@Valid RegisterRequest` → delega a `AuthService` → HTTP 201
- [ ] **C35.** `POST /api/auth/login` → `@Valid LoginRequest` → delega a `AuthService` → HTTP 200 con JWT
- [ ] **C36.** `POST /api/auth/logout` → HTTP 200 (en JWT el logout es client-side; documentar y justificar esto)
- [ ] **C37.** Controlador **no** contiene lógica de negocio — solo llama al servicio y retorna la respuesta

### C.7 DTOs de autenticación
- [ ] **C38.** `LoginRequest.java` con validaciones: `@NotBlank`, `@Email` en email; `@NotBlank`, `@Size(min=8)` en password
- [ ] **C39.** `RegisterRequest.java` con validaciones: nombre, email único, contraseña fuerte
- [ ] **C40.** `AuthResponse.java` con campos: `token`, `type` ("Bearer"), `email`, `role`
- [ ] **C41.** La respuesta **nunca** incluye la contraseña ni el hash

---

## BLOQUE D — CRUD COMPLETO CON JPA/HIBERNATE (OE2 + OE3)
*Criterio de verificación oficial: 5 operaciones funcionando + ningún SQL por concatenación*

### D.1 Entidad JPA
- [ ] **D1.** Entidad `Producto.java` con `@Entity`, `@Table(name="productos")`
- [ ] **D2.** Campos con anotaciones correctas: `@Id`, `@GeneratedValue(strategy=IDENTITY)`, `@Column(nullable=false)`
- [ ] **D3.** Relación `@ManyToOne` con `Categoria` correctamente mapeada con `@JoinColumn`
- [ ] **D4.** Campos de auditoría: `@CreatedDate`, `@LastModifiedDate` con `@EnableJpaAuditing` en config
- [ ] **D5.** `@NotNull`, `@NotBlank` en campos requeridos de la entidad

### D.2 Repositorio JPA
- [ ] **D6.** `ProductoRepository.java` extiende `JpaRepository<Producto, Long>`
- [ ] **D7.** Al menos 1 query JPQL con `@Query` parametrizado (ej: buscar por nombre)
- [ ] **D8.** **CERO** queries con concatenación de strings — solo JPQL con `:parametros` o `?1`
- [ ] **D9.** Método con `Pageable` para paginación: `Page<Producto> findAll(Pageable pageable)`
- [ ] **D10.** `CategoriaRepository.java` extiende `JpaRepository<Categoria, Long>`

### D.3 DTOs del CRUD
- [ ] **D11.** `ProductoRequest.java` con validaciones Bean Validation:
  - `@NotBlank` en nombre
  - `@NotNull @Positive` en precio
  - `@NotNull @PositiveOrZero` en stock
  - `@NotNull` en categoriaId
- [ ] **D12.** `ProductoResponse.java` — DTO de salida que **nunca expone la entidad JPA directamente**
- [ ] **D13.** `PagedResponse.java` — wrapper con: `content`, `page`, `size`, `totalElements`, `totalPages`

### D.4 Servicio del CRUD
- [ ] **D14.** Interfaz `ProductoService.java` con métodos: `findAll(Pageable)`, `findById(Long)`, `create(ProductoRequest)`, `update(Long, ProductoRequest)`, `delete(Long)`
- [ ] **D15.** `ProductoServiceImpl.java` implementa `ProductoService`
- [ ] **D16.** `findAll()` retorna `Page<ProductoResponse>` con paginación
- [ ] **D17.** `findById()` lanza `ResourceNotFoundException` si no existe (nunca retorna `null`)
- [ ] **D18.** `create()` mapea `ProductoRequest` → `Producto` (entidad) → guarda → mapea a `ProductoResponse`
- [ ] **D19.** `update()` verifica que el producto existe → actualiza campos → guarda → retorna `ProductoResponse`
- [ ] **D20.** `delete()` verifica que el producto existe → elimina → no retorna nada (void)
- [ ] **D21.** Lógica de negocio **en el servicio**, no en el controlador ni en el repositorio

### D.5 Controlador del CRUD
- [ ] **D22.** `ProductoController.java` con `@RestController` + `@RequestMapping("/api/productos")`
- [ ] **D23.** `GET /api/productos` → paginado con `?page=0&size=10` → HTTP 200 con `PagedResponse`
- [ ] **D24.** `GET /api/productos/{id}` → HTTP 200 con `ProductoResponse` o 404 si no existe
- [ ] **D25.** `POST /api/productos` → `@Valid @RequestBody ProductoRequest` → HTTP 201 con `ProductoResponse`
- [ ] **D26.** `PUT /api/productos/{id}` → `@Valid @RequestBody ProductoRequest` → HTTP 200 con `ProductoResponse`
- [ ] **D27.** `DELETE /api/productos/{id}` → HTTP 204 No Content
- [ ] **D28.** Todos los endpoints con `@PreAuthorize` o protegidos por `SecurityFilterChain`
- [ ] **D29.** Controlador **solo** llama al servicio — cero lógica de negocio directa

### D.6 Verificación funcional (evidencias requeridas)
- [ ] **D30.** Captura Postman/Swagger: `POST /api/productos` → 201 Created con body del producto creado
- [ ] **D31.** Captura Postman/Swagger: `GET /api/productos` → 200 con lista paginada
- [ ] **D32.** Captura Postman/Swagger: `GET /api/productos/{id}` → 200 con producto específico
- [ ] **D33.** Captura Postman/Swagger: `PUT /api/productos/{id}` → 200 con producto actualizado
- [ ] **D34.** Captura Postman/Swagger: `DELETE /api/productos/{id}` → 204 No Content
- [ ] **D35.** Captura: intento de CRUD sin JWT → 401 Unauthorized

---

## BLOQUE E — MANEJO DE EXCEPCIONES CENTRALIZADO
- [ ] **E1.** Clase `GlobalExceptionHandler.java` con `@RestControllerAdvice`
- [ ] **E2.** Maneja `ResourceNotFoundException` → HTTP 404 con cuerpo JSON: `{status, mensaje, timestamp}`
- [ ] **E3.** Maneja `BadRequestException` → HTTP 400
- [ ] **E4.** Maneja `MethodArgumentNotValidException` → HTTP 400 con lista de errores de validación por campo
- [ ] **E5.** Maneja `AuthenticationException` → HTTP 401
- [ ] **E6.** Maneja `AccessDeniedException` → HTTP 403
- [ ] **E7.** Maneja `Exception` genérica → HTTP 500 con mensaje genérico (sin exponer stack trace al cliente)
- [ ] **E8.** Clase `ErrorResponse.java` con campos: `status`, `error`, `message`, `timestamp`, `path`
- [ ] **E9.** Stack traces **nunca** expuestos en las respuestas HTTP de producción
- [ ] **E10.** Captura de respuesta de error en el informe (400 y 404 documentados)

---

## BLOQUE F — VALIDACIÓN DE ENTRADAS (Bean Validation)
- [ ] **F1.** `@Valid` en todos los `@RequestBody` de los controladores
- [ ] **F2.** `@NotBlank` en campos de texto requeridos
- [ ] **F3.** `@NotNull` en objetos requeridos
- [ ] **F4.** `@Email` en campos de correo electrónico
- [ ] **F5.** `@Size(min=8)` en contraseñas
- [ ] **F6.** `@Positive` en precios y cantidades positivas
- [ ] **F7.** `@PositiveOrZero` en stock (puede ser 0)
- [ ] **F8.** Mensajes de validación personalizados en español (atributo `message` de cada anotación)
- [ ] **F9.** `GlobalExceptionHandler` captura `MethodArgumentNotValidException` y retorna errores por campo
- [ ] **F10.** Validación en el **servidor** — no confiar solo en validación del cliente

---

## BLOQUE G — SEGURIDAD OWASP APLICADA (OE4)
- [ ] **G1. A01 — Control de Acceso Roto:** `@PreAuthorize("hasRole('ADMIN')")` en endpoints de administración
- [ ] **G2. A01:** validar que el usuario solo accede a sus propios recursos (ej: `producto.getUserId() == currentUserId`)
- [ ] **G3. A02 — Fallas Criptográficas:** `BCryptPasswordEncoder` con strength 12 — **nunca MD5/SHA-1**
- [ ] **G4. A02:** JWT firmado con clave secreta fuerte (256 bits mínimo), nunca expuesta en el código
- [ ] **G5. A02:** HTTPS configurado para producción (`application-prod.properties`)
- [ ] **G6. A03 — Inyección:** JPQL parametrizado en **100%** de las queries — cero concatenación SQL
- [ ] **G7. A03:** inputs sanitizados antes de procesar (no confiar en el cliente)
- [ ] **G8. A05 — Configuración Insegura:** cabeceras de seguridad configuradas con Spring Security
  - `X-Frame-Options: DENY`
  - `X-Content-Type-Options: nosniff`
  - `Strict-Transport-Security`
- [ ] **G9. A05:** mensajes de error genéricos en producción (sin stack traces)
- [ ] **G10. A06 — Componentes Vulnerables:** `mvn dependency:check` o OWASP Dependency Check ejecutado sin vulnerabilidades críticas
- [ ] **G11. A07 — Fallas de Autenticación:** tokens JWT con expiración configurada (no tokens eternos)
- [ ] **G12. A07:** manejo de token inválido retorna 401, nunca 500
- [ ] **G13. XSS:** outputs sanitizados — Spring MVC serializa a JSON (no HTML), documentar por qué XSS es menor riesgo en APIs REST vs. apps con vistas
- [ ] **G14.** Cabeceras CORS configuradas correctamente: solo orígenes del frontend permitidos, no `*` en producción
- [ ] **G15.** Cada vulnerabilidad OWASP A01–A07 + XSS **documentada en el informe** con: qué es, cómo se manifiesta en el PFC, qué se implementó para mitigarla

---

## BLOQUE H — CONFIGURACIÓN CORS
- [ ] **H1.** Clase `CorsConfig.java` con `@Configuration`
- [ ] **H2.** `allowedOrigins` configurado con la URL del frontend (no `*` en producción)
- [ ] **H3.** `allowedMethods`: GET, POST, PUT, DELETE, OPTIONS
- [ ] **H4.** `allowedHeaders`: Authorization, Content-Type
- [ ] **H5.** `allowCredentials(true)` si se usan cookies (documentar decisión)
- [ ] **H6.** CORS registrado en `SecurityFilterChain` con `.cors(cors -> cors.configurationSource(...))`

---

## BLOQUE I — DOCUMENTACIÓN CON SWAGGER / OPENAPI
- [ ] **I1.** Dependencia `springdoc-openapi-starter-webmvc-ui` en `pom.xml`
- [ ] **I2.** Swagger UI accesible en `http://localhost:8080/swagger-ui.html` → captura en el informe
- [ ] **I3.** Clase `OpenApiConfig.java` con título, descripción, versión del API
- [ ] **I4.** Anotación `@Operation(summary="...")` en al menos los endpoints del CRUD
- [ ] **I5.** Esquema de seguridad JWT configurado en Swagger para poder probar endpoints protegidos
- [ ] **I6.** Swagger UI excluido de la seguridad en dev: `/swagger-ui/**`, `/v3/api-docs/**` en rutas públicas

---

## BLOQUE J — TESTS AUTOMATIZADOS
- [ ] **J1.** Al menos **2 tests de integración** del controlador de autenticación con `@WebMvcTest` + MockMvc:
  - Test: registro exitoso → HTTP 201
  - Test: login con credenciales correctas → HTTP 200 con token
  - Test: login con credenciales incorrectas → HTTP 401
- [ ] **J2.** Al menos **2 tests de integración** del CRUD con MockMvc:
  - Test: GET sin token → HTTP 401
  - Test: POST con token válido → HTTP 201
- [ ] **J3.** Al menos **1 test de repositorio** con `@DataJpaTest` usando H2 en memoria
- [ ] **J4.** Al menos **1 test unitario** de servicio con `@ExtendWith(MockitoExtension.class)` y mocks de repositorio
- [ ] **J5.** Perfil de test con H2 en memoria: `application-test.properties` con `spring.datasource.url=jdbc:h2:mem:testdb`
- [ ] **J6.** Tests ejecutan sin errores: `mvn test` en verde — captura en el informe

---

## BLOQUE K — ARCHITECTURE DECISION RECORDS (ADR)
*Criterio de verificación oficial: 2 ADR completos en `docs/adr/` + referenciados en README*

- [ ] **K1.** Archivo `docs/adr/ADR-001-tecnologia-backend.md` en el repositorio Java
- [ ] **K2.** ADR-001 con formato completo: Título, Fecha, Estado (Aceptado), Contexto, Decisión, Consecuencias, Alternativas rechazadas
- [ ] **K3.** ADR-001 justifica por qué Spring Boot 3.x (ej: JDK 21 LTS, soporte nativo, Spring Security maduro)
- [ ] **K4.** Archivo `docs/adr/ADR-002-estrategia-bd.md` en el repositorio Java
- [ ] **K5.** ADR-002 justifica JPA/Hibernate vs. JDBC directo (con criterios técnicos: productividad, N+1, lazy loading)
- [ ] **K6.** Ambos ADR **referenciados en el README.md** con enlaces relativos
- [ ] **K7.** README con instrucciones claras: prerequisitos, cómo clonar, configurar BD, ejecutar, probar con Swagger

---

## BLOQUE L — TABLA COMPARATIVA OBLIGATORIA (OE3)
*Mínimo 8 criterios técnicos con valores basados en fuentes citadas*

- [ ] **L1.** Tabla incluida en el informe con mínimo **8 criterios técnicos**
- [ ] **L2.** Cada criterio tiene valor concreto para PHP/Laravel **Y** Java/Spring Boot
- [ ] **L3.** Criterios mínimos obligatorios cubiertos:

| # | Criterio | ¿Incluido? |
|---|----------|-----------|
| 1 | Mecanismo de autenticación (Sesiones vs JWT) | ☐ |
| 2 | Hash de contraseñas (Argon2id vs BCrypt) | ☐ |
| 3 | ORM / Acceso a datos (Eloquent/PDO vs JPA/Hibernate) | ☐ |
| 4 | Prevención de inyección SQL | ☐ |
| 5 | Validación de entradas (Form Requests vs Bean Validation) | ☐ |
| 6 | Rendimiento benchmarked (RPS aproximado) | ☐ |
| 7 | Curva de aprendizaje (escala objetiva) | ☐ |
| 8 | Disponibilidad de hosting en Ecuador / costo | ☐ |
| 9 | Análisis estático (PHPStan vs SonarLint/SpotBugs) | ☐ |
| 10 | Ecosistema de paquetes / Comunidad | ☐ |

- [ ] **L4.** Cada valor de la tabla respaldado con **fuente citada** (no valores inventados)
- [ ] **L5.** Conclusión de la tabla: cuál tecnología es más adecuada para el PFC y por qué

---

## BLOQUE M — ESTRUCTURA DEL REPOSITORIO Y CALIDAD DE CÓDIGO
- [ ] **M1.** Repositorio en rama `feature/backend` (no directo en `main`)
- [ ] **M2.** Proyecto Java en subcarpeta `backend-java/` separado del PHP
- [ ] **M3.** `pom.xml` en la raíz del proyecto Java con todas las dependencias
- [ ] **M4.** Código sin `System.out.println()` — usar SLF4J con `@Slf4j` y `log.info()`, `log.error()`
- [ ] **M5.** Sin bloques `catch (Exception e) {}` vacíos — siempre loguear o relanzar
- [ ] **M6.** Sin dependencias circulares entre paquetes
- [ ] **M7.** Constantes no hardcodeadas en el código — usar `@Value("${propiedad}")` o clase de constantes
- [ ] **M8.** Commits con mensajes descriptivos: `feat: add JWT authentication filter`, `fix: handle ResourceNotFoundException`
- [ ] **M9.** `Dockerfile` funcional para empaquetar la aplicación (opcional pero suma)
- [ ] **M10.** SonarLint instalado en el IDE y sin issues críticos reportados — documentar en el informe

---

## BLOQUE N — REQUISITOS DEL INFORME ESCRITO (PARTE JAVA)

### N.1 Fundamento teórico correspondiente a Java/Spring Boot
- [ ] **N1.** Subtema 3.4 completo: pipeline de Spring Security, IoC Container, principios SOLID — mínimo 350 palabras
- [ ] **N2.** Comparativa IoC Spring vs DI ASP.NET Core documentada
- [ ] **N3.** `SecurityFilterChain` explicado con el orden correcto de filtros y por qué importa
- [ ] **N4.** Fragmento de código de `PreparedStatement` Java comparado con PDO PHP (Bloque teórico 3.3)

### N.2 Documentación del procedimiento Java
- [ ] **N5.** Diagrama E-R de las entidades JPA (Producto, Categoria, User) — generado con dbdiagram.io o DBeaver
- [ ] **N6.** Diagrama de arquitectura de capas: Controller → Service → Repository → BD
- [ ] **N7.** Fragmento de código: `SecurityConfig.java` completo documentado en el informe
- [ ] **N8.** Fragmento de código: `JwtTokenProvider.java` con `generateToken()` y `validateToken()` documentados
- [ ] **N9.** Fragmento de código: `ProductoRepository.java` con query JPQL documentado
- [ ] **N10.** Fragmento de código: `GlobalExceptionHandler.java` documentado

### N.3 Evidencias y capturas para el informe
- [ ] **N11.** Captura: aplicación Spring Boot arrancada en consola (puerto 8080)
- [ ] **N12.** Captura: Swagger UI con todos los endpoints listados
- [ ] **N13.** Captura: `POST /api/auth/register` → 201 con token JWT
- [ ] **N14.** Captura: `POST /api/auth/login` → 200 con token JWT
- [ ] **N15.** Captura: `GET /api/productos` sin token → 401 Unauthorized
- [ ] **N16.** Captura: `GET /api/productos` con token válido → 200 con lista paginada
- [ ] **N17.** Captura: las 5 operaciones CRUD con token válido (Postman o Swagger)
- [ ] **N18.** Captura: `mvn test` → BUILD SUCCESS (tests en verde)
- [ ] **N19.** Captura: tablas creadas en BD (DBeaver o PhpMyAdmin)

### N.4 Preguntas de análisis (Anexo C — desde perspectiva Java)
- [ ] **N20.** Pregunta 1: argumentar si Spring Boot fue más seguro por defecto que PHP/Laravel con ejemplos concretos
- [ ] **N21.** Pregunta 2: recomendar tecnología para Ecuador con datos de la tabla comparativa y bibliografía
- [ ] **N22.** Pregunta 3: reportar resultado de SonarLint (número de issues por categoría y correcciones)
- [ ] **N23.** Pregunta 4: diagrama de flujo completo login → JWT → request protegido → datos — incluido en el informe

---

## RESUMEN DE CONTEO

| Bloque | Descripción | Ítems |
|--------|-------------|-------|
| A | Entorno y configuración | 11 |
| B | Arquitectura y SOLID | 16 |
| C | Autenticación JWT + Spring Security | 41 |
| D | CRUD con JPA/Hibernate | 35 |
| E | Manejo de excepciones | 10 |
| F | Validación Bean Validation | 10 |
| G | Seguridad OWASP | 15 |
| H | Configuración CORS | 6 |
| I | Swagger / OpenAPI | 6 |
| J | Tests automatizados | 6 |
| K | Architecture Decision Records | 7 |
| L | Tabla comparativa obligatoria | 5 |
| M | Repositorio y calidad de código | 10 |
| N | Informe escrito (parte Java) | 23 |
| **TOTAL** | | **201** |

---

## ⚠️ ERRORES QUE GARANTIZAN DESCUENTO DE NOTA

1. **Usar `WebSecurityConfigurerAdapter`** (deprecated desde Spring Boot 3) — indica código desactualizado
2. **CSRF habilitado en API REST** — rompe el funcionamiento con JWT y muestra desconocimiento de arquitecturas stateless
3. **Contraseña almacenada sin hash o con MD5/SHA-1** — viola OE1 y A02-OWASP directamente
4. **Cualquier query SQL con concatenación de strings** — viola OE2 explícitamente
5. **Clave JWT hardcodeada en el código fuente** — viola A02-OWASP y es detectable por el docente en revisión de código
6. **Entidad JPA retornada directamente desde el controlador** (sin DTO) — expone estructura interna de BD y viola principios de diseño
7. **Sin `OncePerRequestFilter`** para JWT — el filtro puede ejecutarse múltiples veces por request
8. **Sin tabla comparativa de 8+ criterios** — criterio de verificación oficial del Paso 4
9. **Sin manejo de `ResourceNotFoundException`** (lanzar 500 cuando no existe un recurso) — el docente prueba con IDs inexistentes
10. **Tests sin ejecutar o fallando** — `mvn test` debe retornar BUILD SUCCESS

---

## 🔑 DIFERENCIAS CLAVE JAVA vs PHP QUE EL DOCENTE EVALUARÁ

| Lo que el docente verifica | PHP/Laravel | Java/Spring Boot |
|---|---|---|
| Autenticación | Sesiones + CSRF token | JWT stateless |
| Protección de contraseña | `password_hash(ARGON2ID)` | `BCryptPasswordEncoder(12)` |
| Protección de rutas | Middleware `Authenticate.php` | `SecurityFilterChain` + JWT filter |
| Acceso a datos | PDO Prepared Statements | JPQL parametrizado |
| Análisis estático | PHPStan nivel 5 → 0 errores | SonarLint → 0 issues críticos |
| Inyección de dependencias | ServiceProvider binding | `@Autowired` con interfaces |
| Validación de entradas | Form Requests | `@Valid` + Bean Validation |
| Manejo de errores | `Handler.php` global | `@RestControllerAdvice` |

---

*Checklist generado a partir del análisis exhaustivo de la Guía de Práctica Experimental N.° 2*
*Asignatura: Aplicaciones Web [111] — 5to Nivel A — Periodo 2026-2027 PPA*
