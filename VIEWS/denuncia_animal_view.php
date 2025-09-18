<?php
session_start();
include '../PHP/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denuncia Animal</title>
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
  <h2 style="text-align:center; margin-top:1rem;">🚨 Denuncia Animal</h2>

  <form action="../PHP/denuncia_animal.php" method="POST" enctype="multipart/form-data" class="form-mascota">
    <label for="foto">Foto de la denuncia animal:</label>
    <input type="file" name="foto" id="foto" accept="image/*" required>

    <label for="Ubicacion_mascota">Ubicacion exacta</label>
    <input type="text" name="ubicacion_mascota" id="Ubicacion_mascota" required>

    <label for="color_casa">Color de casa:</label>
    <input type="text" name="color_casa" id="color_casa" required>

    <label for="descripcion_mascota">Descripcion:</label>
    <input type="text" name="descripcion_mascota" id="descripcion_mascota" required>

    <button type="submit">🚨 Denuncia Animal</button>
  </form>
</main>
 <?php include '../VIEWS/footer.php'; ?>
    <!-- Alertify JS -->
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<?php if(isset($_SESSION['denuncia_enviada']) && $_SESSION['denuncia_enviada']): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    alertify.alert(
        "🚨 Denuncia Enviada",
        "¡Tu denuncia ha sido registrada correctamente! Gracias por tu colaboración.",
        function(){
            alertify.success('Denuncia registrada');
        }
    );
});
</script>
<?php 
unset($_SESSION['denuncia_enviada']); // Limpiar variable
endif; ?>

</body>
</html>
