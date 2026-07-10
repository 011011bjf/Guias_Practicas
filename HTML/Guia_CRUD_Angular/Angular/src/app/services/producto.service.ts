import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Producto } from '../models/producto.model';

@Injectable({ providedIn: 'root' })
export class ProductoService {
   private readonly http = inject(HttpClient);
  private readonly urlBase = '/api/productos';

  crear(producto: Omit<Producto, 'id'>): Observable<Producto> {
    return this.http.post<Producto>(this.urlBase, producto).pipe(
      catchError(this.manejarError)
    );
  }

  actualizar(id: number, producto: Producto): Observable<Producto> {
    return this.http.put<Producto>(`${this.urlBase}/${id}`, producto).pipe(
      catchError(this.manejarError)
    );
  }

  eliminar(id: number): Observable<void> {
    return this.http.delete<void>(`${this.urlBase}/${id}`).pipe(
      catchError(this.manejarError)
    );
  }

  // Manejo de errores clasificado por familia de código HTTP (según Paso 6 del plan)
  private manejarError(error: HttpErrorResponse) {
    if (error.status === 0) {
      console.error('Error de red físico o bloqueo de CORS. El navegador no recibió respuesta:', error.error);
    } else if (error.status >= 400 && error.status < 500) {
      console.error(`Error del cliente (${error.status}): Petición inválida o recurso no encontrado.`, error.error);
    } else if (error.status >= 500) {
      console.error(`Error del servidor (${error.status}): Fallo interno en Spring Boot / PostgreSQL.`, error.error);
    }
    return throwError(() => new Error(error.message || 'Ocurrió un error en la solicitud HTTP al backend.'));
  }
}
