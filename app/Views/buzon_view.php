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
  <link rel="stylesheet" href="/Alcaldia/public/CSS/buzon.css" />
  <link rel="stylesheet" href="/Alcaldia/public/CSS/header.css" />
</head>
<body>
  <?php include __DIR__ . '/header.php'; ?>
  <main>
    <section class="buzon-title">
      <h1>Buzón de Sugerencias</h1>
      <p>Tu opinión es importante para nosotros. Déjanos tus sugerencias.</p>
      <?php if (!empty($mensaje)): ?>
        <p style="background: #fff; color: #1a237e; font-weight: bold; border-radius: 6px; border: 1px solid #1976d2; padding: 8px 16px; display: inline-block; box-shadow: 0 2px 8px rgba(26,35,126,0.08); margin-top: 10px;">
          <?= $mensaje ?>
        </p>
      <?php endif; ?>
    </section>
    <section class="buzon-form">
      <form action="/Alcaldia/buzon/procesar" method="post">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required />
        <label for="sugerencia">Sugerencia:</label>
        <textarea id="sugerencia" name="sugerencia" rows="4" required></textarea>
        <button type="submit">Enviar Sugerencia</button>
      </form>
    </section>
  </main>
  <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
