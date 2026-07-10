import { Component, viewChild } from '@angular/core';
import { NuevoProductoComponent } from './components/nuevo-producto/nuevo-producto.component';
import { ListaProductosComponent } from './components/lista-productos/lista-productos.component';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [NuevoProductoComponent, ListaProductosComponent],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App {
  // Referencia declarativa al componente hijo ListaProductosComponent en Angular 22
  listaProductos = viewChild(ListaProductosComponent);

  // Cuando se crea un producto en NuevoProductoComponent, disparar recarga en ListaProductosComponent
  onProductoCreado() {
    this.listaProductos()?.recargar();
  }
}
