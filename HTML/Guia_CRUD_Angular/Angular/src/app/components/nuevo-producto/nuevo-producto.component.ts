import { Component, inject, output, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ProductoService } from '../../services/producto.service';

@Component({
  selector: 'app-nuevo-producto',
  imports: [FormsModule],
  templateUrl: './nuevo-producto.component.html',
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
