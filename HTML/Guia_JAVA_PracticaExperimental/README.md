# Guía Práctica Experimental — Backend Spring Boot 4.1

Este es el backend del proyecto construido con **Java 25**, **Spring Boot 4.1.0**, **Spring Security**, **JPA/Hibernate** y **PostgreSQL**. Cuenta con autenticación por tokens **JWT** stateless y documentación de API interactiva con **OpenAPI/Swagger**.

## Requisitos Previos

- **Java Development Kit (JDK)**: JDK 21 o superior (Java 25 configurado por defecto).
- **PostgreSQL**: Servidor de base de datos relacional corriendo localmente o de manera remota.
- **Maven**: (Se incluye el Maven wrapper `./mvnw` para omitir la instalación local).

## Configuración del Entorno

1. Cree un archivo llamado `.env` en el directorio raíz del proyecto (donde se ubica `pom.xml`).
2. Agregue las siguientes variables con sus configuraciones locales:

```env
# Configuración de PostgreSQL
DB_URL=jdbc:postgresql://localhost:5432/guia_java_db
DB_USERNAME=postgres
DB_PASSWORD=su_contraseña_postgresql

# Seguridad JWT (Debe ser un hash fuerte de al menos 256 bits)
JWT_SECRET=ClaveSecretaSuperSeguraDeAlMenos256BitsParaJWTAuthenticationToken12345
JWT_EXPIRATION_MS=10800000

# CORS
CORS_ALLOWED_ORIGIN=http://localhost:4020
```

*Nota: Asegúrese de haber creado la base de datos `guia_java_db` en su servidor de PostgreSQL antes de iniciar.*

## Ejecución del Proyecto

Para iniciar el servidor de desarrollo, ejecute el siguiente comando en la terminal:

```bash
# Windows
./mvnw.cmd spring-boot:run

# Linux / macOS
./mvnw spring-boot:run
```

El servidor iniciará por defecto en el puerto `8080` (`http://localhost:8080`).

## Documentación de la API

Una vez iniciado el servidor, puede acceder a la documentación interactiva en:
- **Swagger UI**: [http://localhost:8080/swagger-ui.html](http://localhost:8080/swagger-ui.html)
- **OpenAPI Json**: [http://localhost:8080/api-docs](http://localhost:8080/api-docs)

---

## Endpoints Principales

### Autenticación (Públicos)
- **POST** `/api/auth/register` : Registra un usuario nuevo y devuelve el token JWT. (El primer usuario registrado se convierte en ADMIN automáticamente).
- **POST** `/api/auth/login` : Autentica un usuario con email y contraseña, devolviendo el token.

### Categorías
- **GET** `/api/categorias` : Lista todas las categorías.
- **GET** `/api/categorias/{id}` : Obtiene detalles de una categoría.
- **POST** `/api/categorias` : Crea una nueva categoría. *(Requiere Rol: ADMIN)*
- **PUT** `/api/categorias/{id}` : Actualiza datos de una categoría. *(Requiere Rol: ADMIN)*
- **DELETE** `/api/categorias/{id}` : Elimina una categoría. *(Requiere Rol: ADMIN)*
- **GET** `/api/categorias/buscar?nombre=...` : Busca categorías por coincidencia de nombre.

### Productos
- **GET** `/api/productos` : Retorna lista de productos paginada (parámetros: `page`, `size`, `sortBy`).
- **GET** `/api/productos/{id}` : Obtiene detalles de un producto.
- **POST** `/api/productos` : Crea un nuevo producto. *(Requiere Rol: ADMIN)*
- **PUT** `/api/productos/{id}` : Modifica un producto. *(Requiere Rol: ADMIN)*
- **DELETE** `/api/productos/{id}` : Elimina un producto. *(Requiere Rol: ADMIN)*
- **GET** `/api/productos/buscar?nombre=...` : Busca productos por coincidencia de nombre (paginado).
- **GET** `/api/productos/categoria/{categoriaId}` : Retorna productos que pertenecen a la categoría (paginado).
