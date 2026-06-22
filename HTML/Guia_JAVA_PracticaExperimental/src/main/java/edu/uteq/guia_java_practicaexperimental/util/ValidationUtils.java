package edu.uteq.guia_java_practicaexperimental.util;

import java.util.regex.Pattern;

public class ValidationUtils {

    private static final Pattern HTML_PATTERN = Pattern.compile("<[^>]*>");

    private ValidationUtils() {
        // Prevent instantiation
    }

    public static String sanitizeInput(String input) {
        if (input == null) {
            return null;
        }
        // Sanitización básica eliminando etiquetas HTML
        return HTML_PATTERN.matcher(input).replaceAll("").trim();
    }

    public static boolean isValidPrice(double price) {
        return price > 0;
    }
}
