import { Component, inject, signal } from '@angular/core';
import { httpResource } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { Producto } from '../../models/producto.model';
import { ProductoService } from '../../services/producto.service';

@Component({
  selector: 'app-lista-productos',
  imports: [FormsModule],
  templateUrl: './lista-productos.component.html',
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
