<?php require 'configuracion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carrito PHP</title>
<style>
body{font-family:Arial,sans-serif;max-width:800px;margin:2rem auto}
table{width:100%;border-collapse:collapse;margin-top:1rem}
th,td{padding:8px 12px;border:1px solid #ddd;text-align:left}
th{background:#4a90d9;color:white}
.btn{padding:6px 14px;border:none;border-radius:4px;cursor:pointer}
.btn-add{background:#27ae60;color:white}
.btn-del{background:#e74c3c;color:white}
.btn-clear{background:#7f8c8d;color:white}
.btn-destroy{background:#8e44ad;color:white}
.total{font-weight:bold;font-size:1.2rem;margin-top:1rem}
</style>
</head>
<body>
<h1>Tienda - Sesión PHP</h1>

<!-- Catálogo de productos -->
<h2>Catálogo</h2>
<form method="POST" action="agregar.php">
  <select name="nombre">
    <option value="Laptop">Laptop - $450</option>
    <option value="Mouse">Mouse - $25</option>
    <option value="Teclado">Teclado - $45</option>
    <option value="Monitor">Monitor - $180</option>
  </select>
  <button type="submit" class="btn btn-add">Agregar al carrito</button>
</form>

<!-- Carrito actual -->
<h2>Carrito</h2>
<?php
$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)): ?>
  <p>El carrito está vacío.</p>
  <form method="POST" action="destruir_sesion.php" style="margin-top:1rem;">
    <button class="btn btn-destroy" title="Destruye la sesión actual y genera un nuevo PHPSESSID">
       Destruir y Nueva Sesión (Cambiar PHPSESSID)
    </button>
  </form>
<?php else: ?>
  <table>
    <tr><th>Producto</th><th>Precio</th><th>Acción</th></tr>
    <?php foreach ($carrito as $i => $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['nombre']) ?></td>
      <td>$<?= $item['precio'] ?></td>
      <td>
        <form method="POST" action="eliminar.php" style="margin:0">
          <input type="hidden" name="indice" value="<?= $i ?>">
          <button class="btn btn-del">Eliminar</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <p class="total">
    Total: $<?= array_sum(array_column($carrito, 'precio')) ?>
  </p>
  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <form method="POST" action="limpiar.php" style="margin:0;">
      <button class="btn btn-clear" title="Borra los ítems pero mantienes el mismo PHPSESSID">
         Vaciar carrito (Mantener sesión)
      </button>
    </form>
    <form method="POST" action="destruir_sesion.php" style="margin:0;">
      <button class="btn btn-destroy" title="Destruye la sesión actual y genera un nuevo PHPSESSID">
         Destruir y Nueva Sesión (Cambiar PHPSESSID)
      </button>
    </form>
  </div>
<?php endif; ?>

<!-- Debug: mostrar ID de sesión (solo en desarrollo) -->
<p style="color:#999;font-size:0.8rem">
  Session ID: <?= session_id() ?>
</p>
</body>
</html>
