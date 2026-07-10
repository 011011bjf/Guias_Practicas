import { Component, inject, output, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ProductoService } from '../../services/producto.service';

@Component({
  selector: 'app-nuevo-producto',
  standalone: true,
  imports: [FormsModule],
  template: `
    <div class="card-form">
      <h3>✨ Agregar Nuevo Producto</h3>
      <p class="subtitle">Ingresa los datos para persistir en PostgreSQL</p>

      <form (ngSubmit)="guardar()" #form="ngForm">
        <div class="form-group">
          <label for="nombre">Nombre del producto</label>
          <input
            id="nombre"
            type="text"
            [(ngModel)]="nombre"
            name="nombre"
            placeholder="Ej: Teclado Mecánico RGB"
            required
            class="input-modern"
          />
        </div>

        <div class="form-row">
          <div class="form-group flex-1">
            <label for="precio">Precio ($)</label>
            <input
              id="precio"
              type="number"
              step="0.01"
              [(ngModel)]="precio"
              name="precio"
              placeholder="0.00"
              required
              class="input-modern"
            />
          </div>

          <div class="form-group flex-1">
            <label for="stock">Cantidad en Stock</label>
            <input
              id="stock"
              type="number"
              [(ngModel)]="stock"
              (ngModelChange)="onStockChange($event)"
              name="stock"
              placeholder="0"
              required
              class="input-modern"
            />
          </div>

          <div class="form-group checkbox-group">
            <label class="toggle-label">
              <input
                type="checkbox"
                [(ngModel)]="disponible"
                name="disponible"
              />
              <span class="toggle-custom"></span>
              <span class="toggle-text">¿Disponible?</span>
            </label>
          </div>
        </div>

        @if (mensajeError()) {
          <div class="error-badge">⚠️ {{ mensajeError() }}</div>
        }

        @if (guardando()) {
          <button type="button" disabled class="btn-primary guardando">
            ⏳ Guardando en servidor...
          </button>
        } @else {
          <button type="submit" class="btn-primary" [disabled]="!nombre || precio <= 0 || stock < 0">
            💾 Guardar Producto
          </button>
        }
      </form>
    </div>
  `,
  styleUrl: './nuevo-producto.component.css'
})
export class NuevoProductoComponent {
  private productoService = inject(ProductoService);

  nombre = '';
  precio = 0;
  stock = 10;
  disponible = true;

  guardando = signal(false);
  mensajeError = signal('');

  // Evento para notificar al listado principal que refresque httpResource
  productoCreado = output<void>();

  onStockChange(nuevoStock: number) {
    if (nuevoStock <= 0) {
      this.disponible = false;
    } else if (nuevoStock > 0 && !this.disponible) {
      this.disponible = true;
    }
  }

  guardar() {
    if (!this.nombre.trim() || this.precio <= 0 || this.stock < 0) {
      this.mensajeError.set('Por favor, ingresa un nombre válido, precio mayor a 0 y stock mayor o igual a 0.');
      return;
    }

    this.mensajeError.set('');
    this.guardando.set(true);

    this.productoService.crear({
      nombre: this.nombre.trim(),
      precio: Number(this.precio),
      stock: Number(this.stock),
      disponible: this.disponible
    }).subscribe({
      next: (nuevo) => {
        this.guardando.set(false);
        this.nombre = '';
        this.precio = 0;
        this.stock = 10;
        this.disponible = true;
        this.productoCreado.emit();
      },
      error: (err) => {
        this.guardando.set(false);
        this.mensajeError.set('Error al guardar el producto. Verifica la conexión o el backend de Spring Boot.');
      }
    });
  }
}
