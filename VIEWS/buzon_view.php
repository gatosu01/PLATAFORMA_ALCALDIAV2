<?php
session_start();
$mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Buzón de Sugerencias - Alcaldía de Santiago</title>
  <link rel="stylesheet" href="../CSS/buzon.css" />
  <link rel="stylesheet" href="../CSS/header.css" />
</head>
<body>
  <?php include '../PHP/header.php'; ?>

  <main>
    <section class="buzon-title">
      <h1>Buzón de Sugerencias</h1>
      <p>Tu opinión es importante para nosotros. Déjanos tus sugerencias.</p>
      <?php if (!empty($mensaje)): ?>
        <p style="color: green; font-weight: bold;"><?= $mensaje ?></p>
      <?php endif; ?>
    </section>

    <section class="buzon-form">
      <form action="../PHP/procesar_buzon.php" method="post">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required />

        <label for="sugerencia">Sugerencia:</label>
        <textarea id="sugerencia" name="sugerencia" rows="4" required></textarea>

        <button type="submit">Enviar Sugerencia</button>
      </form>
    </section>
  </main>

  <?php include '../VIEWS/footer.php'; ?>

</body>
</html>
