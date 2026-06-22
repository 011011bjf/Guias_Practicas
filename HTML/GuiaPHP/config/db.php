<?php
declare(strict_types=1);

// Configuración de la base de datos PostgreSQL
// Se utiliza la base de datos 'appweb2026ppa' en localhost
$dsn = 'pgsql:host=127.0.0.1;port=5432;dbname=appweb2026ppa';

try {
    // Inicialización del objeto PDO con el usuario 'postgres' y la contraseña '12345'
    $pdo = new PDO($dsn, 'postgres', '12345', [
        // Lanzar excepciones en caso de errores de SQL/conexión
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Establecer el modo de obtención predeterminado a un array asociativo
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Si la conexión falla, se interrumpe la ejecución del script
    die('Error de conexion');
}
