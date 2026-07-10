package com.edu.Guia.controller;

import com.edu.Guia.model.Producto;
import jakarta.servlet.http.HttpSession;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.util.*;

@Controller
@RequestMapping("/carrito")
public class CarritoController {

    // Clave de la sesión
    private static final String CLAVE = "carrito";

    // Catálogo de precios válidos (servidor, nunca del cliente)
    private static final Map<String, Double> PRECIOS = Map.of(
            "Laptop", 450.0,
            "Mouse", 25.0,
            "Teclado", 45.0,
            "Monitor", 180.0
    );

    // Helper: obtener el carrito de la sesión
    @SuppressWarnings("unchecked")
    private List<Producto> obtenerCarrito(HttpSession session) {
        List<Producto> carrito =
                (List<Producto>) session.getAttribute(CLAVE);
        if (carrito == null) {
            carrito = new ArrayList<>();
            session.setAttribute(CLAVE, carrito);
        }
        return carrito;
    }

    // GET /carrito -- mostrar catálogo y carrito
    @GetMapping
    public String index(HttpSession session, Model model) {
        List<Producto> carrito = obtenerCarrito(session);
        double total = carrito.stream()
                .mapToDouble(Producto::precio)
                .sum();

        model.addAttribute("carrito", carrito);
        model.addAttribute("catalogo", PRECIOS.keySet());
        model.addAttribute("total", total);
        model.addAttribute("sessionId", session.getId());
        return "carrito/index"; // templates/carrito/index.html
    }

    // POST /carrito/agregar -- agregar producto
    @PostMapping("/agregar")
    public String agregar(@RequestParam String nombre,
                           HttpSession session) {
        if (PRECIOS.containsKey(nombre)) {
            obtenerCarrito(session)
                    .add(new Producto(nombre, PRECIOS.get(nombre)));
        }
        return "redirect:/carrito"; // PRG pattern
    }

    // POST /carrito/eliminar -- eliminar por índice
    @PostMapping("/eliminar")
    public String eliminar(@RequestParam int indice,
                            HttpSession session) {
        List<Producto> carrito = obtenerCarrito(session);
        if (indice >= 0 && indice < carrito.size()) {
            carrito.remove(indice);
        }
        return "redirect:/carrito";
    }

    // POST /carrito/limpiar -- vaciar el carrito
    @PostMapping("/limpiar")
    public String limpiar(HttpSession session) {
        session.removeAttribute(CLAVE); // solo el carrito
        // Para cerrar sesión completa:
        // session.invalidate();
        return "redirect:/carrito";
    }

    // POST /carrito/destruir -- destruir toda la sesión y generar nueva CARRITO_SESSION
    @PostMapping("/destruir")
    public String destruir(HttpSession session) {
        session.invalidate(); // Destruye la sesión en el servidor y fuerza un nuevo ID
        return "redirect:/carrito";
    }
}
