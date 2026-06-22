# Tienda Stock - Backend Control de Inventarios

Sistema backend para la gestión de productos e inventarios con diseño visual premium, desarrollado utilizando Laravel 13, arquitectura de capas decoupled (Controladores -> Servicios -> Repositorios -> Modelos Eloquent), y robusto análisis estático de tipos.

---

## 🚀 Características

- **Arquitectura Limpia**: Desacoplamiento de lógica de negocio mediante el patrón Repository y la capa de Servicios.
- **Seguridad Avanzada**:
    - Hashing de contraseñas mediante **Argon2id** (`HASH_DRIVER=argon2id`).
    - Cabeceras de seguridad HTTP personalizadas (HSTS, CSP, X-Frame-Options, XSS Protection).
    - Cifrado automático de datos de sesión (`SESSION_ENCRYPT=true`) utilizando el driver de base de datos.
    - Protección client-side y server-side contra ataques CSRF y XSS.
- **Catálogo de Productos Completo**:
    - CRUD visual premium utilizando vistas Blade estilizadas con TailwindCSS v4.
    - CRUD de API RESTful con respuestas JSON estructuradas y renderizadores de excepciones adaptados.
    - Paginación nativa y búsquedas/filtros por categoría y estado de stock.
- **Testing Exhaustivo**:
    - Cobertura completa de la suite de pruebas unitarias y de características (Auth, CRUD de productos, repositorios).
- **Análisis Estático**: Configurado con Larastan a nivel de tipo 5.

---

## 📂 Estructura de Directorios Clave

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── CategoriaController.php    # CRUD API de Categorías
│   │   │   └── ProductoController.php     # CRUD API de Productos (con validación de requests)
│   │   ├── Auth/
│   │   │   ├── LoginController.php        # Manejo de Login (guest/auth)
│   │   │   ├── RegisterController.php     # Registro de nuevos usuarios
│   │   │   └── LogoutController.php       # Invalida sesiones de forma segura
│   │   ├── DashboardController.php        # Inicio protegido
│   │   └── ProductoViewController.php     # CRUD en HTML / Blade de Productos
│   ├── Middleware/
│   │   └── SecurityHeaders.php            # Inyecta cabeceras CSP, HSTS, etc.
│   └── Requests/
│       ├── Auth/ (LoginRequest, RegisterRequest)
│       └── Producto/ (StoreProductoRequest, UpdateProductoRequest)
├── Models/
│   ├── User.php                           # Modelo User con Hashing casted
│   ├── Categoria.php                      # Relación HasMany con Producto
│   └── Producto.php                       # Modelo Producto con Scopes locales
├── Providers/
│   └── RepositoryServiceProvider.php      # Binding de interfaces de repositorios a implementaciones
├── Repositories/
│   ├── Contracts/                         # Interfaces (UserRepositoryInterface, ProductoRepositoryInterface)
│   └── Eloquent/                          # Implementaciones concretas de Eloquent (preparan PDO de forma segura)
└── Services/
    ├── AuthService.php                    # Lógica de sesiones y registro
    └── ProductoService.php                # Lógica de negocio de productos
```

---

## 🛠️ Requisitos de Instalación

- **PHP**: 8.2 o superior (con extensiones `pdo_sqlite`, `sodium`, `openssl`, `mbstring`).
- **Composer**: Administrador de dependencias de PHP.
- **NodeJS y npm**: Para compilar los recursos de frontend (Tailwind v4 / Vite).

---

## ⚙️ Configuración Inicial

1. **Clonar e instalar dependencias:**

    ```bash
    composer install
    npm install
    ```

2. **Configurar el archivo de entorno:**
   Copia el archivo `.env.example` como `.env`:

    ```bash
    copy .env.example .env
    ```

    El archivo `.env` ya viene preconfigurado para utilizar **SQLite** local como base de datos por defecto para facilitar la ejecución.

3. **Crear archivo de base de datos SQLite y migrar:**

    ```bash
    # En Windows PowerShell
    New-Item -ItemType File -Path database\database.sqlite -Force

    # Ejecutar migraciones y seeders
    php artisan migrate --seed
    ```

    _Nota: El seeder crea un usuario administrador por defecto:_
    - **Usuario**: `admin@example.com`
    - **Contraseña**: `password`

4. **Compilar recursos de interfaz y levantar el servidor:**
    ```bash
    # Servidor de desarrollo
    npm run dev
    ```

---

## 🧪 Pruebas Unitarias y de Integración

Ejecutar la suite completa de pruebas para verificar que el inicio de sesión, el CRUD y los repositorios funcionan correctamente:

```bash
php artisan test
```

---

## 📝 Decisiones de Arquitectura (ADRs)

El diseño del backend sigue decisiones justificadas detalladas en nuestro directorio de ADRs:

- [ADR-001 - Selección del Framework](docs/adr/ADR-001-tecnologia-backend.md)
- [ADR-002 - Estrategia de Base de Datos y Repositorios](docs/adr/ADR-002-estrategia-bd.md)
