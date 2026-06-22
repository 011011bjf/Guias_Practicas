<?php
declare(strict_types=1);

// Incluir la conexión a la base de datos
require __DIR__ . '/config/db.php';

// ==========================================
// 1. ALTA (POST) -> Insertar y redirigir (PRG)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Saneamiento de las entradas
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $carrera = trim($_POST['carrera'] ?? '');
    
    // Validación de campos obligatorios y formato de correo electrónico
    if ($nombre !== '' && filter_var($correo, FILTER_VALIDATE_EMAIL) && $carrera !== '') {
        // Preparar y ejecutar la consulta usando sentencias preparadas de PDO (evita inyección SQL)
        $stmt = $pdo->prepare('INSERT INTO estudiantes (nombre, correo, carrera) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $correo, $carrera]);
    }
    
    // Redirección para evitar reenvío de formulario al recargar la página (Patrón PRG)
    header('Location: index.php');
    exit;
}

// ==========================================
// 2. ELIMINAR (GET) -> Borrar y redirigir
// ==========================================
if (isset($_GET['eliminar'])) {
    // Casteo seguro a entero
    $id = (int) $_GET['eliminar'];
    
    // Ejecutar eliminación mediante sentencia preparada
    $stmt = $pdo->prepare('DELETE FROM estudiantes WHERE id = ?');
    $stmt->execute([$id]);
    
    // Redirección posterior para limpiar la URL
    header('Location: index.php');
    exit;
}

// ==========================================
// 3. LISTAR (Reporte) -> Consultar todos los registros
// ==========================================
$estudiantes = $pdo->query('SELECT id, nombre, correo, carrera FROM estudiantes ORDER BY id')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de control escolar y registro de estudiantes CRUD desarrollado con PHP, PDO y PostgreSQL.">
    <title>Registro de Estudiantes - CRUD PHP & PostgreSQL</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Variables y Reset de Estilos */
        :root {
            --bg-color: #0c0e17;
            --card-bg: rgba(26, 28, 48, 0.45);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-primary: #6366f1;
            --accent-secondary: #8b5cf6;
            --accent-gradient: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
            --danger-color: #ef4444;
            --danger-hover: #dc2626;
            --success-color: #10b981;
            --font-family: 'Outfit', sans-serif;
            --glow-shadow: 0 0 20px rgba(99, 102, 241, 0.25);
            --transition-speed: 0.3s;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, #141625 0%, #0a0b12 100%);
            background-attachment: fixed;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2.5rem 1rem;
        }

        /* Contenedor Principal */
        .container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
        }

        /* Encabezado */
        header {
            text-align: center;
            margin-bottom: 0.5rem;
            animation: fadeInDown 0.8s ease-out;
        }

        h1 {
            font-size: 2.75rem;
            font-weight: 700;
            background: linear-gradient(to right, #a5b4fc, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 300;
        }

        /* Grid de Contenido */
        main {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            animation: fadeInUp 0.8s ease-out;
        }

        @media (min-width: 992px) {
            main {
                grid-template-columns: 380px 1fr;
            }
        }

        /* Estilo Glassmorphism de Tarjetas */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 2rem;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
        }

        .card:hover {
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.4);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        /* Controles de Formulario */
        .form-group {
            margin-bottom: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper svg {
            position: absolute;
            left: 1rem;
            width: 1.25rem;
            height: 1.25rem;
            color: var(--text-secondary);
            pointer-events: none;
            transition: color var(--transition-speed);
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            background: rgba(10, 11, 20, 0.5);
            border: 1px solid var(--card-border);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-family);
            font-size: 0.95rem;
            transition: border-color var(--transition-speed), box-shadow var(--transition-speed);
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: var(--glow-shadow);
        }

        input[type="text"]:focus + svg,
        input[type="email"]:focus + svg {
            color: var(--accent-primary);
        }

        /* Botones Premium */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-family: var(--font-family);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45), var(--glow-shadow);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.12);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 0.5rem 0.8rem;
            font-size: 0.85rem;
            border-radius: 8px;
            width: auto;
            text-decoration: none;
        }

        .btn-danger:hover {
            background: var(--danger-color);
            color: white;
            border-color: var(--danger-color);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Tabla de Reportes */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--card-border);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.95rem;
            background: rgba(15, 17, 30, 0.3);
        }

        th {
            background: rgba(22, 25, 45, 0.8);
            color: var(--text-primary);
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-bottom: 2px solid var(--card-border);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--card-border);
            color: var(--text-secondary);
            vertical-align: middle;
            transition: color var(--transition-speed);
        }

        tr {
            transition: background-color var(--transition-speed);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        tr:hover td {
            color: var(--text-primary);
        }

        .td-id {
            color: var(--accent-primary) !important;
            font-weight: 600;
            width: 60px;
        }

        .td-name {
            font-weight: 500;
            color: var(--text-primary);
        }

        .td-email {
            font-family: monospace;
            font-size: 0.9rem;
        }

        .td-carrera {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(139, 92, 246, 0.1);
            color: #c084fc;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        /* Estado Vacío */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .empty-state svg {
            width: 3.5rem;
            height: 3.5rem;
            color: rgba(255, 255, 255, 0.1);
        }

        /* Animaciones */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Footer */
        footer {
            margin-top: auto;
            padding-top: 3rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 300;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <header>
            <h1 id="main-heading">Control de Estudiantes</h1>
            <p class="subtitle">CRUD moderno conectado a PostgreSQL con PDO y patrón PRG</p>
        </header>

        <!-- Contenido principal -->
        <main>
            <!-- Formulario de alta -->
            <section class="card" aria-labelledby="form-section-title">
                <h2 class="card-title" id="form-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent-primary);"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    Nuevo Estudiante
                </h2>
                
                <form action="index.php" method="POST" id="form-estudiante">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <div class="input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" id="nombre" name="nombre" required placeholder="Ej. Juan Pérez" autocomplete="name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <input type="email" id="correo" name="correo" required placeholder="Ej. juan@correo.com" autocomplete="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="carrera">Carrera Universitaria</label>
                        <div class="input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>
                            <input type="text" id="carrera" name="carrera" required placeholder="Ej. Ingeniería de Software">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-guardar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        Registrar Estudiante
                    </button>
                </form>
            </section>

            <!-- Reporte / Listado de estudiantes -->
            <section class="card" aria-labelledby="list-section-title">
                <h2 class="card-title" id="list-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent-secondary);"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    Listado de Estudiantes Registrados
                </h2>

                <?php if (empty($estudiantes)): ?>
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m17 8 5 5m0-5-5 5"/></svg>
                        <p>No hay estudiantes registrados en la base de datos.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Carrera</th>
                                    <th style="text-align: center;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estudiantes as $e): ?>
                                    <tr>
                                        <td class="td-id">#<?= htmlspecialchars((string)$e['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="td-name"><?= htmlspecialchars((string)$e['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="td-email"><?= htmlspecialchars((string)$e['correo'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <span class="td-carrera">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>
                                                <?= htmlspecialchars((string)$e['carrera'], ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="index.php?eliminar=<?= (int)$e['id'] ?>" 
                                               class="btn btn-danger" 
                                               id="btn-eliminar-<?= (int)$e['id'] ?>"
                                               onclick="return confirm('¿Estás seguro de que deseas eliminar al estudiante <?= htmlspecialchars(addslashes((string)$e['nombre']), ENT_QUOTES, 'UTF-8'); ?>?');">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <!-- Pie de página -->
        <footer>
            <p>&copy; 2026 Guía de PHP para Aplicaciones Web UTEQ. Diseñado con fines académicos.</p>
        </footer>
    </div>
</body>
</html>
