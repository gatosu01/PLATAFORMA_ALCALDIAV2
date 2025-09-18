<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Adaptación MVC: los datos deben ser pasados por el controlador
// Se asume que $departamentos está disponible como array asociativo
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quejas y Denuncias - Alcaldía de Santiago</title>
  <link rel="stylesheet" href="/Alcaldia/public/CSS/header.css" />
  <link rel="stylesheet" href="/Alcaldia/public/CSS/complaints_reports.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
  <?php include __DIR__ . '/header.php'; ?>
  <main>
    <section class="title">
      <h1>Quejas y Denuncias</h1>
      <p>Utiliza este formulario para registrar tus quejas o denuncias.</p>
    </section>
    <section class="form-section">
  <form action="/Alcaldia/process-complaints" method="post" enctype="multipart/form-data">
        <label>Tipo:</label>
        <div>
          <input type="radio" id="queja" name="tipo" value="queja" required>
          <label for="queja">Queja</label>
          <input type="radio" id="denuncia" name="tipo" value="denuncia">
          <label for="denuncia">Denuncia</label>
        </div>
        <label for="department">Departamento correspondiente:</label>
        <select id="department" name="department_id" required>
          <option value="">Seleccione una opción</option>
          <?php foreach ($departamentos as $row): ?>
            <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
        <label for="map">Ubicación exacta:</label>
        <div id="map" style="height:300px;border-radius:10px;margin-bottom:15px;"></div>
        <button type="button" id="btn-ubicacion">Usar mi ubicación actual</button>
        <input type="hidden" id="lat" name="lat">
        <input type="hidden" id="lng" name="lng">
        <label for="ubication">Ubicación exacta (dirección o referencia):</label>
        <input type="text" id="ubication" name="ubication" required>
        <label for="photo">Adjuntar imágenes (mínimo 1, máximo 3):</label>
        <div id="photo-wrapper">
          <div id="photo-container">
            <input type="file" name="photos[]" accept="image/*" required>
          </div>
          <button type="button" id="add-photo">+ Agregar otra imagen</button>
        </div>
        <label for="complaint">Detalles de la Queja o Denuncia:</label>
        <textarea id="complaint" name="complaint" rows="5" required></textarea>
        <div class="g-recaptcha" data-sitekey="6LcST5orAAAAAFD8vCHxtOpTt0dyqojYnTQdlrqb"></div>
        <button type="submit">Enviar Queja/Denuncia</button>
      </form>
    </section>
  </main>
  <?php include __DIR__ . '/footer.php'; ?>
  <script src="/Alcaldia/public/JS/complaints_map.js"></script>
  <script src="/Alcaldia/public/JS/clon_input_photo.js"></script>
</body>
</html>
