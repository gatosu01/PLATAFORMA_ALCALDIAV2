<?php
session_start();

include '../PHP/conexion.php';

// --- Lógica de obtención de datos --- //
// Es una buena práctica tener toda la obtención de datos al principio del script.

// Slider
$slider_images_query = $conexion->query("SELECT * FROM slider_images ORDER BY position ASC");
$slider_images = $slider_images_query ? $slider_images_query->fetch_all(MYSQLI_ASSOC) : [];

// Notificaciones
// Se mueve la lógica aquí para centralizar la obtención de datos y no depender de includes.
$result = $conexion->query("SELECT titulo, mensaje FROM notifications ORDER BY fecha DESC LIMIT 3");
$notificaciones = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// FAQs
$faqPath = '../DATA/faqs.json';
$faqs = [];
if (file_exists($faqPath)) {
    $faqsData = json_decode(file_get_contents($faqPath), true);
    // Asegurarse que el JSON es válido y es un array
    if (is_array($faqsData)) {
        $faqs = $faqsData;
    }
}
$ultimasFaqs = [];
if (!empty($faqs)) {
    // Tomar solo las 3 más recientes
    $ultimasFaqs = array_slice($faqs, -3);
}

// --- Funciones Auxiliares de Renderizado ---

/**
 * Renderiza un cuadro de información estándar.
 */
function render_info_box($title, $text, $button_text, $button_link_or_id, $is_link = true) {
    $button_attr = $is_link ? 'data-href="' . htmlspecialchars($button_link_or_id) . '"' : 'data-section-id="' . htmlspecialchars($button_link_or_id) . '"';
    echo '<div class="cuadroblanco">';
    echo '<h2>' . htmlspecialchars($title) . '</h2>';
    echo '<p>' . htmlspecialchars($text) . '</p>';
    echo '<button class="nav-button" ' . $button_attr . '>' . htmlspecialchars($button_text) . '</button>';
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alcaldía de Santiago</title>
  <link rel="stylesheet" href="../CSS/index.css" />
  <link rel="stylesheet" href="../CSS/header.css" />
  <!-- Alertify CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
  <!-- Alertify Default Theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
  <?php if (isset($_SESSION['registro_exitoso']) && $_SESSION['registro_exitoso']): ?>
    <!-- Script de Alertify JS para confirmación -->
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        alertify.alert("🎉 Registro Exitoso",
          "¡Bienvenido, <?= htmlspecialchars($_SESSION['nombre']); ?>! Tu cuenta ha sido creada correctamente.",
          function() {
            alertify.success('¡Cuenta lista!');
          }
        );
      });
    </script>
  <?php
    // Limpiar la variable para que no se muestre nuevamente
    unset($_SESSION['registro_exitoso']);
  endif; ?>

</head>


<body>

  <?php include '../PHP/header.php'; ?>

  <main>
    <section class="title" id="INDEX">
      <h1>Bienvenido a la Alcaldía de Santiago</h1>
      <p>Conoce nuestros servicios y trámites disponibles para ti.</p>
    </section>

    <section class="botones-navegacion">
      <button class="scroll-button" data-section-id="notificaciones">🔔 Notificaciones</button>
      <button class="scroll-button" data-section-id="banco-info">💳 Pagos</button>
    </section>

    <section class="slider">
      <div class="slides">
        <!-- Inputs de radio para el control -->
        <?php foreach ($slider_images as $index => $image): ?>
            <input type="radio" name="radio-btn" id="radio<?= $index + 1 ?>" <?= $index === 0 ? 'checked' : '' ?> />
        <?php endforeach; ?>

        <!-- Imágenes del slider -->
        <?php foreach ($slider_images as $index => $image): ?>
            <div class="slide <?= $index === 0 ? 'first' : '' ?>">
                <img src="../IMG/<?= htmlspecialchars($image['image_path']) ?>" alt="<?= htmlspecialchars($image['alt_text']) ?>">
            </div>
        <?php endforeach; ?>

        <?php
        // Controles manuales (labels)
        $total = count($slider_images);
        if ($total > 0): ?>
            <div class="navigation-manual">
                <?php for ($i = 1; $i <= $total; $i++): ?>
                    <label for="radio<?= $i ?>" class="manual-btn"></label>
                <?php endfor; ?>
            </div>
        <?php endif;
        ?>
      </div>
    </section>

    <!-- NOTIFICACIONES -->
    <section class="notificaciones-slider" id="notificaciones">
      <h2>Notificaciones</h2>
      <div class="notificaciones-container">
        <?php if (!empty($notificaciones)): ?>
          <?php foreach ($notificaciones as $n): ?>
            <div class="notificacion">
              <h3><?= htmlspecialchars($n['titulo']) ?></h3>
              <p><?= nl2br(htmlspecialchars($n['mensaje'])) ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No hay notificaciones disponibles.</p>
        <?php endif; ?>
      </div>
    </section>


    <!-- PREGUNTAS FRECUENTES -->
    <section class="faq">
      <h2>Preguntas Frecuentes</h2>
      <?php if (!empty($ultimasFaqs)): ?>
        <?php foreach ($ultimasFaqs as $faq): ?>
          <div class="pregunta">
            <h3><?= htmlspecialchars($faq['pregunta']) ?></h3>
            <p><?= nl2br(htmlspecialchars($faq['respuesta'])) ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay preguntas frecuentes disponibles.</p>
      <?php endif; ?>
    </section>


    <!-- INFORMACIÓN BANCARIA -->
    <section class="BancoNacional" id="banco-info">
      <div class="cuadroblanco">
        <p>
          RECUERDE QUE PUEDE REALIZAR TU PAGO DESDE LA COMODIDAD DE TU CASA U
          OFICINA, A TRAVÉS DE LA BANCA EN LÍNEA A LA SIGUIENTE CUENTA:
        </p>
        <div class="banco">
          <p>BANCO NACIONAL DE PANAMÁ</p>
          <p>CUENTA#: 01-00000-30368</p>
          <p>TESORERÍA MUNICIPAL DE SANTIAGO</p>
          <p>CUENTA CORRIENTE</p>
        </div>
        <p>
          <strong>NOTA:</strong> Una vez realizado el pago, envíe su comprobante
          de pago al correo electrónico: pagos@alcaldiadesantiago.com
        </p>
      </div>
    </section>

    <!-- POSTÚLATE -->
    <section class="postulate" id="postulate">
      <?php render_info_box(
          'Postúlate',
          'Si deseas formar parte de nuestro equipo, envíanos tu currículum en formato PDF.',
          'Enviar CV',
          '../VIEWS/postulate_view.php'
      ); ?>
    </section>


    <!-- MASCOTAS -->
    <section class="mascotas" id="mascotas">
      <?php render_info_box('🐾 Reporta tu Mascota', 'Si tu mascota está perdida, repórtala aquí.', 'Reportar Mascota', '../VIEWS/reportar_mascotas_view.php'); ?>
      <?php render_info_box('🐶 Mascotas Extraviadas', 'Mira todas las mascotas reportadas como extraviadas.', 'Ver Mascotas', '../VIEWS/mascotas_extraviadas_view.php'); ?>
      <?php render_info_box('🚨 Denuncia Animal', 'Reporta casos de maltrato o abandono animal.', 'Hacer Denuncia', '../VIEWS/denuncia_animal_view.php'); ?>
    </section>
    <div class="mensaje-centro">
      <p>
        Si has encontrado a tu mascota, por favor llama al
        <strong>[número de contacto]</strong> de Bienestar Animal para informar sobre su ubicación.
        ¡Tu ayuda es muy importante! 🐾
      </p>
    </div>

  </main>
  <!-- Alertify JS -->
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<?php include '../VIEWS/footer.php'; ?>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Manejador para botones de scroll suave
    document.querySelectorAll('.scroll-button').forEach(button => {
      button.addEventListener('click', () => {
        const sectionId = button.dataset.sectionId;
        document.getElementById(sectionId).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });

    // Manejador para botones de navegación a otras páginas
    document.querySelectorAll('.nav-button').forEach(button => {
      if (button.dataset.href) {
        button.addEventListener('click', () => {
          window.location.href = button.dataset.href;
        });
      }
    });
  });
</script>
<script src="../JS/slider_auto.js"></script>

</html>