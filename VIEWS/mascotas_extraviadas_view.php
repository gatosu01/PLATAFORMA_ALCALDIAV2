<?php
session_start();
include '../PHP/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mascotas Extraviadas - Alcaldía de Santiago</title>
  <link rel="stylesheet" href="../CSS/index.css">
  <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/mascotas.css">
    <!-- Alertify CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>

</head>
<body>
<?php include '../PHP/header.php'; ?>

<main>
  <h2 style="text-align:center; margin-top:1rem;">🐶 Mascotas Extraviadas</h2>
  <div class="mascotas-lista">
    <?php
      $res = $conexion->query("SELECT * FROM mascotas_extraviadas WHERE estado='busqueda' ORDER BY fecha_reporte DESC");
      if ($res && $res->num_rows > 0) {
        while ($m = $res->fetch_assoc()) {
          echo '<div class="mascota-card">';
          echo '<img src="../UPLOADS/mascotas/'.htmlspecialchars($m['foto']).'" alt="Mascota">';
          echo '<h3>'.htmlspecialchars($m['nombre_mascota']).'</h3>';
          echo '<p><strong>Dueño(s):</strong> '.htmlspecialchars($m['nombre_dueno']).'</p>';
          echo '<p><strong>Última vez vista en:</strong> '.htmlspecialchars($m['ultima_vista']).'</p>'; 
          echo '<p><strong>Teléfono:</strong> '.htmlspecialchars($m['telefono']).'</p>';
          if (!empty($m['recompensa'])) {
            echo '<p><strong>Recompensa:</strong> '.htmlspecialchars($m['recompensa']).'</p>';
          }
          echo '</div>';
        }
      } else {
        echo "<p style='grid-column:1/-1; text-align:center;'>No hay mascotas reportadas aún.</p>";
      }
    ?>
  </div>
</main>
 <?php include '../VIEWS/footer.php'; ?>
<!-- Alertify JS -->
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
<?php 
unset($_SESSION['mascota_reportada']); // Limpiar variable
endif; ?>

</body>
</html>
