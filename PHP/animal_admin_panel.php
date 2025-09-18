<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'animal_admin') {
    header("Location: ../VIEWS/sign_in_form.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Bienestar Animal - Alcaldía de Santiago</title>
<link rel="stylesheet" href="../CSS/index.css">
<link rel="stylesheet" href="../CSS/header_admin.css">
<link rel="stylesheet" href="../CSS/mascotas.css">
</head>
<body>
<?php include '../PHP/header_animal.php'; ?>

<main>
<h1 style="text-align:center; margin-top:1rem;">Panel Bienestar Animal</h1>

<section class="mascotas-admin">
  <h2>🐶 Mascotas Extraviadas</h2>
  <div class="mascotas-lista">
    <?php
      $res = $conexion->query("SELECT * FROM mascotas_extraviadas where estado='busqueda' ORDER BY fecha_reporte DESC");
      if ($res && $res->num_rows > 0) {
          while ($m = $res->fetch_assoc()) {
              echo '<div class="mascota-card">';
              echo '<img src="../UPLOADS/mascotas/'.htmlspecialchars($m['foto']).'" alt="Mascota">';
              echo '<h3>'.htmlspecialchars($m['nombre_mascota']).'</h3>';
              echo '<p><strong>Dueño(s):</strong> '.htmlspecialchars($m['nombre_dueno']).'</p>';
              echo '<p><strong>Última vez vista en:</strong> '.htmlspecialchars($m['ultima_vista']).'</p>';
              echo '<p><strong>Teléfono:</strong> '.htmlspecialchars($m['telefono']).'</p>';
              if (!empty($m['recompensa'])) echo '<p><strong>Recompensa:</strong> '.htmlspecialchars($m['recompensa']).'</p>';
              
              // Botón para cambiar estado solo si está en busqueda
              if($m['estado'] === 'busqueda'){
                  echo '<form method="POST" action="cambiar_estado_mascota.php" style="margin-top:0.5rem;">';
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
