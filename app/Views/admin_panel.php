<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Models/Conexion.php';
$conexion = (new \App\Models\Conexion())->getConexion();

// Verificar sesión activa
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Alcaldia/public/sign_in.php");
    exit();
}

// Nombre del administrador
$admin_nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Administrador';

// Últimas 3 quejas en estado 'esperando respuesta'
$quejas = $conexion->query(
    "SELECT COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Anónimo') AS nombre, c.complaint, c.fecha FROM complaints c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.tipo = 'queja' AND c.estado = 'esperando respuesta' ORDER BY c.fecha DESC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);
$quejasUnicas = [];
foreach ($quejas as $q) {
    if (!in_array($q['complaint'], array_column($quejasUnicas, 'complaint'))) {
        $quejasUnicas[] = $q;
    }
    if (count($quejasUnicas) >= 3) break;
}

// Últimas 3 denuncias en estado 'esperando respuesta'
$denuncias = $conexion->query(
    "SELECT COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Anónimo') AS nombre, c.complaint, c.fecha FROM complaints c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.tipo = 'denuncia' AND c.estado = 'esperando respuesta' ORDER BY c.fecha DESC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);
$denunciasUnicas = [];
foreach ($denuncias as $d) {
    if (!in_array($d['complaint'], array_column($denunciasUnicas, 'complaint'))) {
        $denunciasUnicas[] = $d;
    }
    if (count($denunciasUnicas) >= 3) break;
}

// Últimas 3 sugerencias (no tienen estado)
$sugerencias = $conexion->query(
    "SELECT email, sugerencia, created_at FROM suggestions ORDER BY created_at DESC LIMIT 10"
)->fetchAll(PDO::FETCH_ASSOC);
$sugerenciasUnicas = [];
foreach ($sugerencias as $s) {
    if (!in_array($s['sugerencia'], array_column($sugerenciasUnicas, 'sugerencia'))) {
        $sugerenciasUnicas[] = $s;
    }
    if (count($sugerenciasUnicas) >= 3) break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Administrativo - Alcaldía de Santiago</title>
  <link rel="stylesheet" href="/Alcaldia/public/CSS/admin_panel.css" />
  <link rel="stylesheet" href="/Alcaldia/public/CSS/header_admin.css" />
</head>
<body>
  <?php include __DIR__ . '/header_admin.php'; ?>
  <main>
    <h1>Bienvenido, <?= htmlspecialchars($admin_nombre) ?></h1>
    <section class="resumen-sugerencias">
      <h2>Últimas Sugerencias</h2>
      <?php if (!empty($sugerenciasUnicas)): ?>
        <?php foreach ($sugerenciasUnicas as $s): ?>
          <div class="sugerencia">
            <p><strong><?= htmlspecialchars($s['email']) ?>:</strong> <?= htmlspecialchars($s['sugerencia']) ?></p>
            <small><?= htmlspecialchars(date('d/m/Y H:i', strtotime($s['created_at']))) ?></small>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay sugerencias.</p>
      <?php endif; ?>
      <a href="/Alcaldia/public/suggestions_admin.php" class="ver-mas">Ver todas las sugerencias</a>
    </section>
    <section id="quejas">
      <h2>Quejas (Esperando Respuesta)</h2>
      <?php if (!empty($quejasUnicas)): ?>
        <?php foreach ($quejasUnicas as $q): ?>
          <div class="reporte queja">
            <p><strong><?= htmlspecialchars(!empty($q['nombre']) ? $q['nombre'] : 'Anónimo') ?>:</strong> <?= htmlspecialchars($q['complaint']) ?></p>
            <small><?= htmlspecialchars(date('d/m/Y H:i', strtotime($q['fecha']))) ?></small>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay quejas en espera de respuesta.</p>
      <?php endif; ?>
      <a href="/Alcaldia/public/complaints_admin.php" class="ver-mas">Ver todas las quejas</a>
    </section>
    <section id="denuncias">
      <h2>Denuncias (Esperando Respuesta)</h2>
      <?php if (!empty($denunciasUnicas)): ?>
        <?php foreach ($denunciasUnicas as $d): ?>
          <div class="reporte denuncia">
            <p><strong><?= htmlspecialchars(!empty($d['nombre']) ? $d['nombre'] : 'Anónimo') ?>:</strong> <?= htmlspecialchars($d['complaint']) ?></p>
            <small><?= htmlspecialchars(date('d/m/Y H:i', strtotime($d['fecha']))) ?></small>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay denuncias en espera de respuesta.</p>
      <?php endif; ?>
      <a href="/Alcaldia/public/reports_admin.php" class="ver-mas">Ver todas las denuncias</a>
    </section>
  </main>
</body>
</html>
