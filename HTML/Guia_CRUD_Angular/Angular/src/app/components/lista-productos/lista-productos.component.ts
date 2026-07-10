import { Component, inject, signal } from '@angular/core';
import { httpResource } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { Producto } from '../../models/producto.model';
import { ProductoService } from '../../services/producto.service';

@Component({
  selector: 'app-lista-productos',
  standalone: true,
  imports: [FormsModule],
  template: `
    <div class="card-list">
      <div class="header-actions">
        <div>
          <h2>📦 Catálogo de Productos</h2>
          <p class="subtitle">Datos consultados en tiempo real con httpResource desde Spring Boot (:8080)</p>
        </div>
        <button (click)="recargar()" class="btn-refresh" title="Refrescar catálogo">
          🔄 Refrescar
        </button>
      </div>

      @if (productos.isLoading()) {
        <div class="state-container loading-pulse">
          <div class="spinner"></div>
          <p>⏳ Cargando catálogo desde el backend en PostgreSQL...</p>
        </div>
      } @else if (productos.error()) {
        <div class="state-container error-card">
          <div class="error-icon">⚠️</div>
          <div class="error-text">
            <h4>No se pudo conectar con el servidor</h4>
            <p>
              Asegúrate de que tu aplicación de Spring Boot esté corriendo en el puerto <strong>8080</strong> y la base de datos PostgreSQL <strong>productos_db</strong> esté activa.
            </p>
            <button (click)="recargar()" class="btn-retry">Reintentar conexión</button>
          </div>
        </div>
      } @else {
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th class="col-id">ID</th>
                <th class="col-nombre">Nombre del Producto</th>
                <th class="col-precio">Precio ($)</th>
                <th class="col-stock">Stock</th>
                <th class="col-estado">Estado en Stock</th>
                <th class="col-acciones">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @for (p of productos.value(); track p.id) {
                <tr class="table-row" [class.editing-row]="idEnEdicion() === p.id">
                  <!-- Modo Edición En Línea (PUT / PATCH) -->
                  @if (idEnEdicion() === p.id) {
                    <td class="col-id"><span class="id-badge">#{{ p.id }}</span></td>
                    <td class="col-nombre">
                      <input
                        type="text"
                        [(ngModel)]="nombreEdit"
                        name="nombreEdit"
                        placeholder="Nombre del producto"
                        class="input-inline"
                      />
                    </td>
                    <td class="col-precio">
                      <input
                        type="number"
                        step="0.01"
                        [(ngModel)]="precioEdit"
                        name="precioEdit"
                        placeholder="0.00"
                        class="input-inline price"
                      />
                    </td>
                    <td class="col-stock">
                      <input
                        type="number"
                        [(ngModel)]="stockEdit"
                        (ngModelChange)="onStockEditChange($event)"
                        name="stockEdit"
                        placeholder="0"
                        class="input-inline stock"
                      />
                    </td>
                    <td class="col-estado">
                      <label class="checkbox-inline">
                        <input
                          type="checkbox"
                          [(ngModel)]="disponibleEdit"
                          name="disponibleEdit"
                        />
                        <span class="status-pill" [class.disponible]="disponibleEdit" [class.agotado]="!disponibleEdit">
                          {{ disponibleEdit ? '✓ Disp.' : '✕ Agotado' }}
                        </span>
                      </label>
                    </td>
                    <td class="col-acciones actions-group">
                      @if (guardandoId() === p.id) {
                        <button disabled class="btn-action guardando">⏳ Guardando...</button>
                      } @else {
                        <button (click)="guardarEdicion(p.id)" class="btn-action save" title="Guardar cambios">
                          💾 Guardar
                        </button>
                        <button (click)="cancelarEdicion()" class="btn-action cancel" title="Cancelar edición">
                          ✕
                        </button>
                      }
                    </td>
                  } @else {
                    <!-- Modo Lectura Normal -->
                    <td class="col-id"><span class="id-badge">#{{ p.id }}</span></td>
                    <td class="col-nombre font-semibold">{{ p.nombre }}</td>
                    <td class="col-precio font-mono">\${{ p.precio.toFixed(2) }}</td>
                    <td class="col-stock">
                      <span class="stock-badge" [class.low-stock]="p.stock <= 5 && p.stock > 0" [class.zero-stock]="p.stock === 0">
                        📦 {{ p.stock ?? 0 }} unid.
                      </span>
                    </td>
                    <td class="col-estado">
                      <span class="status-pill" [class.disponible]="p.disponible" [class.agotado]="!p.disponible">
                        {{ p.disponible ? '✓ Disponible' : '✕ Agotado' }}
                      </span>
                    </td>
                    <td class="col-acciones actions-group">
                      @if (eliminandoId() === p.id) {
                        <button disabled class="btn-action deleting">⏳ Borrando...</button>
                      } @else {
                        <button (click)="iniciarEdicion(p)" class="btn-action edit" title="Modificar producto">
                          ✏️ Editar
                        </button>
                        <button (click)="eliminarProducto(p)" class="btn-action delete" title="Eliminar producto">
                          🗑️ Eliminar
                        </button>
                      }
                    </td>
                  }
                </tr>
              } @empty {
                <tr>
                  <td colspan="6" class="empty-state">
                    <div class="empty-content">
                      <span>📭</span>
                      <p>El catálogo está vacío actualmente. Añade un producto usando el formulario de arriba.</p>
                    </div>
                  </td>
                </tr>
              }
            </tbody>
          </table>
        </div>
      }
    </div>
  `,
  styleUrl: './lista-productos.component.css'
})
export class ListaProductosComponent {
  private productoService = inject(ProductoService);

  // Consulta reactiva nativa con httpResource según el Paso 4 del plan
  readonly productos = httpResource<Producto[]>(() => '/api/productos');

  eliminandoId = signal<number | null>(null);
  guardandoId = signal<number | null>(null);
  idEnEdicion = signal<number | null>(null);

  // Campos temporales para la edición en línea
  nombreEdit = '';
  precioEdit = 0;
  stockEdit = 0;
  disponibleEdit = true;

  recargar() {
    this.productos.reload();
  }

  iniciarEdicion(p: Producto) {
    this.idEnEdicion.set(p.id);
    this.nombreEdit = p.nombre;
    this.precioEdit = p.precio;
    this.stockEdit = p.stock || 0;
    this.disponibleEdit = p.disponible;
  }

  cancelarEdicion() {
    this.idEnEdicion.set(null);
  }

  onStockEditChange(nuevoStock: number) {
    if (nuevoStock <= 0) {
      this.disponibleEdit = false;
    } else if (nuevoStock > 0 && !this.disponibleEdit) {
      this.disponibleEdit = true;
    }
  }

  guardarEdicion(id: number) {
    if (!this.nombreEdit.trim() || this.precioEdit <= 0 || this.stockEdit < 0) {
      alert('Por favor, ingresa un nombre válido, precio mayor a 0 y stock mayor o igual a 0.');
      return;
    }

    this.guardandoId.set(id);
    this.productoService.actualizar(id, {
      id: id,
      nombre: this.nombreEdit.trim(),
      precio: Number(this.precioEdit),
      stock: Number(this.stockEdit),
      disponible: this.disponibleEdit
    }).subscribe({
      next: () => {
        this.guardandoId.set(null);
        this.idEnEdicion.set(null);
        this.recargar(); // Refrescamos la señal reactiva para mostrar los datos actualizados
      },
      error: (err) => {
        this.guardandoId.set(null);
        alert('Error al modificar el producto. ' + err.message);
      }
    });
  }

  eliminarProducto(p: Producto) {
    if (confirm(`¿Estás seguro de eliminar "${p.nombre}"?`)) {
      this.eliminandoId.set(p.id);
      this.productoService.eliminar(p.id).subscribe({
        next: () => {
          this.eliminandoId.set(null);
          this.recargar();
        },
        error: (err) => {
          this.eliminandoId.set(null);
          alert('Error al eliminar el producto. ' + err.message);
        }
      });
    }
  }
}
