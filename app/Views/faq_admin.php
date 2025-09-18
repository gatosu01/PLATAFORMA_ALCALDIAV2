<?php
$jsonPath = __DIR__ . '/../../DATA/faqs.json';
$faqs = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];
$editIndex = null;
$editPregunta = '';
$editRespuesta = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['pregunta'], $_POST['respuesta'])) {
    $nuevaFAQ = ['pregunta' => $_POST['pregunta'], 'respuesta' => $_POST['respuesta']];
    if (isset($_POST['faq_edit_index']) && $_POST['faq_edit_index'] !== '') {
      $index = (int)$_POST['faq_edit_index'];
      $faqs[$index] = $nuevaFAQ;
    } else {
      $faqs[] = $nuevaFAQ;
    }
    file_put_contents($jsonPath, json_encode($faqs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: /Alcaldia/app/Views/faq_admin.php");
    exit;
  }
  if (isset($_POST['delete'])) {
    unset($faqs[$_POST['delete']]);
    $faqs = array_values($faqs);
    file_put_contents($jsonPath, json_encode($faqs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: /Alcaldia/app/Views/faq_admin.php");
    exit;
  }
  if (isset($_POST['edit'])) {
    $editIndex = (int)$_POST['edit'];
    $editPregunta = $faqs[$editIndex]['pregunta'];
    $editRespuesta = $faqs[$editIndex]['respuesta'];
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Administrar FAQs</title>
  <link rel="stylesheet" href="/Alcaldia/public/CSS/faq_admin.css">
  <link rel="stylesheet" href="/Alcaldia/public/CSS/header_admin.css">
</head>
<body>
  <?php include __DIR__ . '/header_admin.php'; ?>
  <main>
    <h1>Panel de Administración - FAQs</h1>
    <form method="POST" class="faq-form">
      <input type="text" name="pregunta" placeholder="Pregunta" required value="<?= htmlspecialchars($editPregunta) ?>">
      <textarea name="respuesta" placeholder="Respuesta" required><?= htmlspecialchars($editRespuesta) ?></textarea>
      <?php if ($editIndex !== null): ?>
        <input type="hidden" name="faq_edit_index" value="<?= $editIndex ?>">
        <button type="submit">Guardar Cambios</button>
      <?php else: ?>
        <button type="submit">Agregar FAQ</button>
      <?php endif; ?>
    </form>
    <hr>
    <div class="faq-list">
      <?php foreach ($faqs as $index => $faq): ?>
        <div class="faq-item">
          <strong><?= htmlspecialchars($faq['pregunta']) ?></strong>
          <p><?= htmlspecialchars($faq['respuesta']) ?></p>
          <div class="faq-actions">
            <form method="POST">
              <input type="hidden" name="edit" value="<?= $index ?>">
              <button type="submit" class="edit-btn">Editar</button>
            </form>
            <form method="POST">
              <input type="hidden" name="delete" value="<?= $index ?>">
              <button type="submit" class="delete-btn" onclick="return confirm('¿Eliminar esta pregunta?')">Eliminar</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
