<?php
session_start();
include '../PHP/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Preguntas Frecuentes - Alcaldía de Santiago</title>
  <link rel="stylesheet" href="../CSS/faq.css" />
  <link rel="stylesheet" href="../CSS/header.css" />
</head>
<body>

  <?php include '../PHP/header.php'; ?>

  <main class="faq-container">
    <div class="title">
      <h1>Preguntas Frecuentes</h1>
      <p>Encuentra respuestas a las dudas más comunes sobre los servicios de la Alcaldía de Santiago.</p>
    </div>

    <?php
      $jsonPath = '../DATA/faqs.json'; // ruta al archivo con preguntas frecuentes
      if (file_exists($jsonPath)) {
          $faqs = json_decode(file_get_contents($jsonPath), true);
          foreach ($faqs as $faq) {
              echo '<div class="faq-item">';
              echo '<button class="faq-question">' . htmlspecialchars($faq['pregunta']) . '</button>';
              echo '<div class="faq-answer"><p>' . htmlspecialchars($faq['respuesta']) . '</p></div>';
              echo '</div>';
          }
      } else {
          echo '<p>No hay preguntas frecuentes disponibles.</p>';
      }
    ?>
  </main>
    <?php include '../VIEWS/footer.php'; ?>

  <script src="../JS/faq.js"></script>
</body>
</html>
