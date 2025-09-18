<?php
session_start();
include 'conexion.php';

// Verificar si hay sesión activa
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
  header("Location: sign_in.html");
  exit();
}

// Últimas 3 quejas
$quejas = $conexion->query("
    SELECT 
        COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Anónimo') AS nombre,
        c.complaint
    FROM complaints c
    LEFT JOIN usuarios u ON c.usuario_id = u.id
    WHERE c.tipo = 'queja'
    ORDER BY c.fecha DESC
    LIMIT 3
")->fetch_all(MYSQLI_ASSOC);

// Últimas 3 denuncias
$denuncias = $conexion->query("
    SELECT 
        COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Anónimo') AS nombre,
        c.complaint
    FROM complaints c
    LEFT JOIN usuarios u ON c.usuario_id = u.id
    WHERE c.tipo = 'denuncia'
    ORDER BY c.fecha DESC
    LIMIT 3
")->fetch_all(MYSQLI_ASSOC);

// Últimas 3 sugerencias
$sugerencias = $conexion->query("
    SELECT 
        email,
        sugerencia
    FROM suggestions
    ORDER BY created_at DESC
    LIMIT 3
")->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Administrativo - Alcaldía de Santiago</title>
  <link rel="stylesheet" href="../CSS/admin_panel.css" />
  <link rel="stylesheet" href="../CSS/header_admin.css" />
</head>

<body>

  <!-- Barra lateral -->
  <?php include 'header_admin.php'; ?>

  <!-- Contenido principal -->
  <main>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>

    <!-- Últimas Sugerencias -->
    <section class="resumen-sugerencias">
      <h2>Últimas Sugerencias</h2>
      <?php if (!empty($sugerencias)): ?>
        <?php foreach ($sugerencias as $s): ?>
          <div class="sugerencia">
            <p><strong><?= htmlspecialchars($s['email']) ?>:</strong>
              <?= htmlspecialchars($s['sugerencia']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay sugerencias.</p>
      <?php endif; ?>
      <a href="suggestions_admin.php" class="ver-mas">Ver todas las sugerencias</a>
    </section>

    <!-- Últimas Quejas -->
    <section id="quejas">
      <h2>Quejas</h2>
      <?php if (!empty($quejas)): ?>
        <?php foreach ($quejas as $q): ?>
          <div class="reporte queja">
            <p><strong><?= htmlspecialchars($q['nombre']) ?>:</strong>
              <?= htmlspecialchars($q['complaint']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay quejas.</p>
      <?php endif; ?>
      <a href="complaints_admin.php" class="ver-mas">Ver todas las quejas</a>
    </section>

    <!-- Últimas Denuncias -->
    <section id="denuncias">
      <h2>Denuncias</h2>
      <?php if (!empty($denuncias)): ?>
        <?php foreach ($denuncias as $d): ?>
          <div class="reporte denuncia">
            <p><strong><?= htmlspecialchars($d['nombre']) ?>:</strong>
              <?= htmlspecialchars($d['complaint']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay denuncias.</p>
      <?php endif; ?>
      <a href="reports_admin.php" class="ver-mas">Ver todas las denuncias</a>
    </section>

  </main>

</body>

</html>