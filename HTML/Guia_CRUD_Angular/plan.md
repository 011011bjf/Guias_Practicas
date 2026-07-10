# Plan de Trabajo — Consumo de Web Services REST con Angular 22

> Basado en la guía de práctica "Consumo de Web Services REST con Angular 22" (Aplicaciones Web, Ingeniería de Software, UTEQ).

## 0. Resumen ejecutivo

La práctica consiste en construir una aplicación Angular 22 (standalone, zoneless) que consuma una API REST, cubriendo las 4 operaciones CRUD, usando `httpResource` para lecturas reactivas y `HttpClient` para mutaciones, con manejo de errores por familia de código HTTP y resolución de CORS mediante proxy de desarrollo.

**Entregable final:** repositorio con la app funcionando + README con evidencias (versiones, captura de error CORS y captura de la app funcionando tras aplicar el proxy).

**Puntaje total:** 10.0 puntos, distribuidos en 5 criterios (ver sección 7).

---

## 1. Requisitos previos

### 1.1 Software obligatorio

| Herramienta | Versión mínima | Verificación |
|---|---|---|
| Node.js | **22.x** (Node 20 ya NO es compatible) | `node --version` |
| Angular CLI | **22.x** | `ng version` |
| TypeScript | **6** (se instala junto con Angular 22) | incluido en `ng version` |
| Editor | VS Code (recomendado) | — |
| Navegador | Chrome/Edge/Firefox actualizado | — |

### 1.2 Conocimientos previos necesarios

- Fundamentos de TypeScript (interfaces, tipos, clases).
- Componentes Angular standalone y la nueva sintaxis de control de flujo (`@if`, `@for`).
- Conceptos HTTP básicos (métodos, códigos de estado).
- Uso de terminal / línea de comandos y Git.

### 1.3 Preparación del entorno (checklist)

- [ ] Instalar/actualizar Node.js a la versión 22 o superior.
- [ ] Instalar la CLI globalmente: `npm install -g @angular/cli@22`.
- [ ] Confirmar con `ng version` que `@angular/core` y `@angular/cli` coinciden en 22.x.
- [ ] Definir o conseguir una API REST para consumir:
  - Una API pública (p. ej. una API de prueba tipo JSONPlaceholder o similar), **o**
  - Un backend propio (Node/Express, json-server, etc.) corriendo en un puerto distinto al de Angular (p. ej. `localhost:8080`), para poder reproducir y resolver el problema de CORS de forma realista.
- [ ] Tener Git instalado y una cuenta en GitHub/GitLab para el repositorio entregable.

---

## 2. Marco conceptual a repasar antes de programar

Antes de escribir código conviene tener claros estos conceptos (la guía los explica en detalle en su sección 3):

1. **Web Service, API, REST** — REST es un estilo arquitectónico (Fielding, 2000): cliente-servidor, sin estado, recursos identificados por URL, interfaz uniforme.
2. **Métodos HTTP y su mapeo a operaciones CRUD:**

   | Operación | Método | Endpoint típico |
   |---|---|---|
   | Leer todos | GET | `/api/productos` |
   | Leer uno | GET | `/api/productos/7` |
   | Crear | POST | `/api/productos` |
   | Actualizar (reemplazo total) | PUT | `/api/productos/7` |
   | Modificar (parcial) | PATCH | `/api/productos/7` |
   | Eliminar | DELETE | `/api/productos/7` |

3. **Códigos de estado por familia:** `2xx` éxito, `4xx` error del cliente, `5xx` error del servidor.
4. **JSON** como formato de intercambio (Angular lo deserializa automáticamente a objetos tipados).
5. **Origen y CORS:**
   - Origen = esquema + dominio + puerto.
   - CORS es un mecanismo de cabeceras HTTP que el **servidor** debe habilitar; el cliente no puede "saltárselo".
   - Peticiones con efectos secundarios (PUT, DELETE, POST con ciertas cabeceras) disparan una petición *preflight* `OPTIONS`.
   - En desarrollo se soluciona con un **proxy** de la CLI de Angular; en producción, el servidor debe enviar `Access-Control-Allow-Origin` o servir frontend y API bajo el mismo origen.
6. **Piezas de Angular moderno:** `HttpClient` (sobre Fetch por defecto en v22), `provideHttpClient()`, `inject()`, `Observable` (RxJS), `signal`, `httpResource()` (API estable en v22 para lecturas reactivas con estados `value`/`isLoading`/`error`).

**Regla práctica clave:** usar `httpResource` para **lecturas (GET)** que se muestran en la interfaz, y `HttpClient` directamente para **mutaciones** (POST/PUT/PATCH/DELETE).

---

## 3. Estructura propuesta del proyecto

```
consumo-rest/
├── proxy.conf.json                  # Configuración del proxy de desarrollo (paso 7)
├── angular.json
├── package.json
├── README.md                        # Entregable: versiones, capturas CORS
├── src/
│   ├── index.html
│   ├── main.ts
│   └── app/
│       ├── app.config.ts            # provideHttpClient() (paso 1)
│       ├── app.component.ts         # Componente raíz (contenedor de la vista)
│       ├── app.routes.ts            # (opcional si se usa routing)
│       │
│       ├── models/
│       │   └── producto.model.ts    # Interfaz Producto (paso 2)
│       │
│       ├── services/
│       │   └── producto.service.ts  # GET/POST/PUT/DELETE tipados (paso 3)
│       │
│       └── components/
│           ├── lista-productos/
│           │   ├── lista-productos.component.ts   # httpResource + señales (paso 4)
│           │   └── lista-productos.component.html (opcional si se separa la plantilla)
│           │
│           └── nuevo-producto/
│               ├── nuevo-producto.component.ts     # Crear producto vía HttpClient (paso 5)
│               └── nuevo-producto.component.html
│
└── docs/
    └── capturas/
        ├── cors-error.png           # Evidencia del bloqueo CORS antes del proxy
        └── app-funcionando.png      # Evidencia de la app funcionando con proxy
```

**Notas de diseño:**
- Toda la lógica HTTP vive en `services/`, nunca en los componentes.
- Las URLs del servicio son relativas (`/api/productos`) para que el proxy funcione y no se fije el dominio en el código.
- `lista-productos` se encarga solo de **leer** (usa `httpResource`); `nuevo-producto` (y un futuro botón de eliminar en la lista) se encargan de **mutar** (usan `ProductoService` + `HttpClient`).
- Se puede añadir eliminación directamente dentro de `lista-productos` (botón "Eliminar" por fila) reutilizando `ProductoService.eliminar()` y refrescando `httpResource` tras el borrado (por ejemplo llamando a `productos.reload()` si la versión de `httpResource` lo soporta, o recargando la señal).

---

## 4. Plan de ejecución paso a paso

### Paso 0 — Definir la API a consumir
Elegir una API pública o levantar un backend propio en un puerto distinto (p. ej. `8080`). Confirmar que responde a `GET/POST/PUT/DELETE` sobre un recurso, por ejemplo `productos`.

### Paso 1 — Crear el proyecto y registrar el cliente HTTP
```bash
ng new consumo-rest --style=css --ssr=false
cd consumo-rest
ng serve -o
```
En `src/app/app.config.ts`, añadir `provideHttpClient()` al arreglo `providers` (no hace falta `withFetch()`, ya es el backend por defecto en v22).

### Paso 2 — Definir el modelo de datos
Crear `producto.model.ts` con la interfaz `Producto { id, nombre, precio, disponible }`.

### Paso 3 — Crear el servicio REST
Crear `producto.service.ts` con `@Injectable({ providedIn: 'root' })`, URL base relativa (`/api/productos`), e implementar:
- `listar(): Observable<Producto[]>`
- `obtener(id): Observable<Producto>`
- `crear(p: Omit<Producto,'id'>): Observable<Producto>`
- `actualizar(id, p): Observable<Producto>`
- `eliminar(id): Observable<void>`

### Paso 4 — Mostrar la lista con `httpResource`
Crear `lista-productos.component.ts`, usando `httpResource<Producto[]>(() => '/api/productos')` y la plantilla con `@if (productos.isLoading())`, `@else if (productos.error())`, `@else { @for (... ; track p.id) }`.

### Paso 5 — Crear un producto con `HttpClient`
Crear `nuevo-producto.component.ts`, inyectar `ProductoService`, construir el objeto nuevo y llamar `.crear(nuevo).subscribe({ next, error })`. Recordar: sin `subscribe()` el `Observable` nunca se ejecuta.

### Paso 6 — Manejar errores por familia de código
Añadir manejo de errores con `catchError`/`HttpErrorResponse`, distinguiendo:
- `status === 0` → sin red o bloqueo CORS.
- `404` → recurso no encontrado.
- `>= 500` → error del servidor.

### Paso 7 — Reproducir y resolver CORS con proxy
1. Levantar la API en un puerto distinto (p. ej. `8080`) y comprobar que, al llamarla desde `localhost:4200` sin proxy, el navegador bloquea la respuesta (`status 0` en consola). **Capturar esta pantalla** para el entregable.
2. Crear `proxy.conf.json` en la raíz del proyecto:
   ```json
   {
     "/api": {
       "target": "http://localhost:8080",
       "secure": false,
       "changeOrigin": true
     }
   }
   ```
3. Levantar de nuevo con `ng serve --proxy-config proxy.conf.json` y comprobar que ahora todo funciona. **Capturar esta pantalla también.**
4. Documentar en el README que este proxy es solo para desarrollo, y que en producción la solución real es habilitar CORS en el servidor o servir frontend y API bajo el mismo origen.

### Paso 8 — Implementar eliminación con refresco de la lista
Añadir un botón "Eliminar" en cada fila de la lista, llamar a `ProductoService.eliminar(id)`, y al recibir éxito, refrescar la fuente de `httpResource` (recargar la señal o volver a disparar la petición) para que la lista se actualice sin recargar la página completa.

### Paso 9 — Buenas prácticas finales (revisión)
- Lógica HTTP solo en servicios.
- URLs relativas en todo el código.
- Todas las respuestas tipadas con interfaces.
- `httpResource` para lecturas, `HttpClient` para mutaciones.
- Errores gestionados por familia de código, no asumidos como éxito siempre.

### Paso 10 — Documentar y entregar
Escribir el `README.md` con:
- Versión de Node y de Angular usadas (salida de `node --version` y `ng version`).
- Explicación breve de la arquitectura del proyecto.
- Instrucciones para ejecutar (`npm install`, `ng serve --proxy-config proxy.conf.json`).
- Captura del error CORS (antes del proxy) y captura de la app funcionando (después del proxy).

---

## 5. Cronograma sugerido (orientativo)

| Sesión | Actividad |
|---|---|
| 1 | Preparación de entorno (Paso 0-1), definición de la API, creación del proyecto |
| 2 | Modelo de datos y servicio REST (Pasos 2-3) |
| 3 | Lectura reactiva con `httpResource` (Paso 4) |
| 4 | Creación y eliminación con `HttpClient` (Pasos 5, 8) |
| 5 | Manejo de errores y diagnóstico/resolución de CORS (Pasos 6-7) |
| 6 | Pulido, buenas prácticas, README y capturas, entrega (Pasos 9-10) |

---

## 6. Checklist de verificación antes de entregar

- [ ] `node --version` ≥ 22 y `ng version` muestra `@angular/core`/`@angular/cli` en 22.x.
- [ ] `provideHttpClient()` registrado en `app.config.ts`.
- [ ] Interfaz `Producto` (u otro recurso) tipando todas las respuestas.
- [ ] Servicio con `listar`, `crear`, `actualizar`/`eliminar` implementados y probados.
- [ ] Componente de listado usa `httpResource` y muestra estados de carga/error.
- [ ] Componente de creación usa `HttpClient` + `subscribe()`.
- [ ] Eliminación implementada y la lista se refresca tras borrar.
- [ ] Errores manejados por familia de código (`0`, `404`, `5xx`, etc.).
- [ ] `proxy.conf.json` creado y funcionando con `--proxy-config`.
- [ ] Captura del error CORS sin proxy.
- [ ] Captura de la app funcionando con proxy.
- [ ] README completo con versiones y capturas.
- [ ] Repositorio subido (Git) con historial de commits razonable.

---

## 7. Rúbrica de evaluación (según la guía)

| Criterio | Puntos |
|---|---|
| Configuración correcta del cliente HTTP y del proxy | 2.0 |
| Servicio con las operaciones GET/POST/DELETE tipadas | 3.0 |
| Lectura reactiva con `httpResource` (estados carga/error) | 2.5 |
| Manejo de errores por código de estado | 1.5 |
| Evidencia de diagnóstico y resolución del CORS | 1.0 |
| **Total** | **10.0** |

---

## 8. Riesgos comunes y cómo evitarlos

- **Olvidar `subscribe()`:** el `Observable` de `HttpClient` no ejecuta la petición hasta que alguien se suscribe; si "no pasa nada" al crear/eliminar, revisar esto primero.
- **Confundir `httpResource` con `HttpClient`:** `httpResource` es solo para lecturas (GET); no usarlo para mutaciones.
- **URLs absolutas en el código:** rompen el proxy y atan el frontend a un dominio fijo; usar siempre rutas relativas (`/api/...`).
- **Pensar que CORS se resuelve en el cliente:** CORS siempre se resuelve en el servidor; el proxy es solo un atajo de desarrollo, no una solución real de producción.
- **No tipar las respuestas:** perder el tipado de TypeScript elimina el autocompletado y oculta errores de forma temprana.
