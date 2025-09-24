<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Bienestar Animal - Alcaldía de Santiago</title>
<link rel="stylesheet" href="/Alcaldia/public/CSS/index.css">
<link rel="stylesheet" href="/Alcaldia/public/CSS/header_admin.css">
<link rel="stylesheet" href="/Alcaldia/public/CSS/mascotas.css">
</head>
<body>
<?php include __DIR__ . '/header_animal.php'; ?>
<main>
<h1 style="text-align:center; margin-top:1rem;">Panel Bienestar Animal</h1>
<section class="mascotas-admin">
  <h2>🐶 Mascotas Extraviadas</h2>
  <div class="mascotas-lista">
    <?php
      if (!empty($mascotas)) {
          foreach ($mascotas as $m) {
              echo '<div class="mascota-card">';
              echo '<img src="/Alcaldia/UPLOADS/mascotas/'.htmlspecialchars($m['foto']).'" alt="Mascota">';
              echo '<h3>'.htmlspecialchars($m['nombre_mascota']).'</h3>';
              echo '<p><strong>Dueño(s):</strong> '.htmlspecialchars($m['nombre_dueno']).'</p>';
              echo '<p><strong>Última vez vista en:</strong> '.htmlspecialchars($m['ultima_vista']).'</p>';
              echo '<p><strong>Teléfono:</strong> '.htmlspecialchars($m['telefono']).'</p>';
              if (!empty($m['recompensa'])) echo '<p><strong>Recompensa:</strong> '.htmlspecialchars($m['recompensa']).'</p>';
              // Botón para cambiar estado solo si está en busqueda
              if($m['estado'] === 'busqueda'){
                  echo '<form method="POST" action="/Alcaldia/cambiar-estado-mascota" style="margin-top:0.5rem;">';
                  echo '<input type="hidden" name="id" value="'.$m['id'].'">';
                  echo '<button type="submit">✅ Marcar como encontrada</button>';
                  echo '</form>';
              } else {
                  echo '<p style="color:green;"><strong>Encontrada ✅</strong></p>';
              }
              echo '</div>';
          }
      } else {
          echo "<p style='grid-column:1/-1; text-align:center;'>No hay mascotas registradas aún.</p>";
      }
    ?>
  </div>
</section>
</main>
</body>
</html>
