<?php
include 'conexion.php';
$result = $conexion->query("SELECT * FROM notifications ORDER BY fecha DESC");
$notificaciones = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crear Notificaciones</title>
  <link rel="stylesheet" href="../CSS/notification_admin.css" />
  <link rel="stylesheet" href="../CSS/header_admin.css" />
</head>
<body>
    <?php include 'header_admin.php'; ?>

  <main>
    <h1>Crear Notificación</h1>

    <!-- Formulario para crear notificación -->
    <form class="notification-form" action="create_notification.php" method="POST">
      <label for="titulo">Título de la Notificación:</label>
      <input type="text" id="titulo" name="titulo" required>

      <label for="mensaje">Mensaje:</label>
      <textarea id="mensaje" name="mensaje" rows="4" required></textarea>

      <button type="submit">Publicar Notificación</button>
    </form>

    <hr>

    <!-- Notificaciones publicadas -->
    <h2>Notificaciones Enviadas</h2>
    <?php if (!empty($notificaciones)): ?>
        <?php foreach ($notificaciones as $n): ?>
            <div class="notificacion">
                <h3><?= htmlspecialchars($n['titulo']) ?></h3>
                <p><?= nl2br(htmlspecialchars($n['mensaje'])) ?></p>
                <span class="fecha"><?= date("d/m/Y", strtotime($n['fecha'])) ?></span>

                 <!-- Botón para eliminar -->
                <form action="delete_notification.php" method="POST" style="margin-top:10px;">
                    <input type="hidden" name="id" value="<?= $n['id'] ?>">
                    <button type="submit" class="btn-eliminar">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay notificaciones aún.</p>
    <?php endif; ?>
  </main>
</body>
</html>
