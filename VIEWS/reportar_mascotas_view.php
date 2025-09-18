<?php
session_start();
include '../PHP/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportar Mascota - Alcaldía de Santiago</title>
  <link rel="stylesheet" href="../CSS/index.css">
  <link rel="stylesheet" href="../CSS/header.css">
  <link rel="stylesheet" href="../CSS/mascotas.css">

</head>
<body>
<?php include '../PHP/header.php'; ?>

<main>
  <h2 style="text-align:center; margin-top:1rem;">🐾 Reportar Mascota Extraviada</h2>

  <form action="../PHP/reportar_mascotas.php" method="POST" enctype="multipart/form-data" class="form-mascota">
    <label for="foto">Foto de la mascota:</label>
    <input type="file" name="foto" id="foto" accept="image/*" required>

    <label for="nombre_mascota">Nombre de la mascota:</label>
    <input type="text" name="nombre_mascota" id="nombre_mascota" required>

    <label for="nombre_dueno">Nombre del dueño(s):</label>
    <input type="text" name="nombre_dueno" id="nombre_dueno" required>

    <label for="ultima_vista">Última vez vista en:</label>
<input type="text" name="ultima_vista" id="ultima_vista" required>

    <label for="telefono">Teléfono(s) de contacto:</label>
    <input type="text" name="telefono" id="telefono" required>

    <label for="recompensa">Recompensa (opcional):</label>
    <input type="text" name="recompensa" id="recompensa" placeholder="Ej: $100">

    <button type="submit">📩 Reportar Mascota</button>
  </form>
</main>
 <?php include '../VIEWS/footer.php'; ?>
</body>
</html>
