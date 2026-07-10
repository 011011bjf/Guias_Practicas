<?php
require 'configuracion.php';

// Opción B: Destruir toda la sesión y eliminar datos del servidor
session_unset();     // 1. Vacía todas las variables de $_SESSION en memoria
session_destroy();   // 2. Borra el archivo temporal del servidor (/tmp/sess_...)

// 3. Caduca y elimina la cookie PHPSESSID del navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirigir para que index.php inicie una nueva sesión limpia con nuevo PHPSESSID
header('Location: index.php');
exit;
