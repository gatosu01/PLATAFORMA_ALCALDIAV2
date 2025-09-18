<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mascotas Extraviadas - Alcaldía de Santiago</title>
  <link rel="stylesheet" href="/public/CSS/index.css">
  <link rel="stylesheet" href="/Alcaldia/public/CSS/header.css" />
  <link rel="stylesheet" href="/public/CSS/mascotas.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>
<main>
  <h2 style="text-align:center; margin-top:1rem;">🐶 Mascotas Extraviadas</h2>
  <div class="mascotas-lista">
    <?php if (!empty($mascotas)): ?>
      <?php foreach ($mascotas as $m): ?>
        <div class="mascota-card">
          <img src="/public/UPLOADS/mascotas/<?= htmlspecialchars($m['foto']) ?>" alt="Mascota">
          <h3><?= htmlspecialchars($m['nombre_mascota']) ?></h3>
          <p><strong>Dueño(s):</strong> <?= htmlspecialchars($m['nombre_dueno']) ?></p>
          <p><strong>Última vez vista en:</strong> <?= htmlspecialchars($m['ultima_vista']) ?></p>
          <p><strong>Teléfono:</strong> <?= htmlspecialchars($m['telefono']) ?></p>
          <?php if (!empty($m['recompensa'])): ?>
            <p><strong>Recompensa:</strong> <?= htmlspecialchars($m['recompensa']) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style='grid-column:1/-1; text-align:center;'>No hay mascotas reportadas aún.</p>
    <?php endif; ?>
  </div>
</main>
<?php include __DIR__ . '/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<?php if(isset($_SESSION['mascota_reportada']) && $_SESSION['mascota_reportada']): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    alertify.alert(
        "🐾 Mascota Reportada",
        "¡Tu mascota ha sido reportada correctamente! Gracias por mantener la información actualizada.",
        function(){
            alertify.success('Mascota reportada');
        }
    );
});
</script>
<?php unset($_SESSION['mascota_reportada']); endif; ?>
</body>
</html>
