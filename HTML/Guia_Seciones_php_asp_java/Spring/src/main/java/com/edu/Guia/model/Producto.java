package com.edu.Guia.model;

import java.io.Serializable;

// Serializable: necesario para almacenar en sesión
public record Producto(String nombre, double precio)
        implements Serializable {

    private static final long serialVersionUID = 1L;
}
