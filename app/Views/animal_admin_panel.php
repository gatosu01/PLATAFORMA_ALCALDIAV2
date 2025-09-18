
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Bienestar Animal - Alcaldía de Santiago</title>
<link rel="stylesheet" href="/Alcaldia/public/CSS/header_admin.css">
<link rel="stylesheet" href="/Alcaldia/public/CSS/animal_admin_panel.css">
</head>
<body>
<?php include __DIR__ . '/header_animal.php'; ?>

<main>
<h1 style="text-align:center; margin-top:1rem;">Panel Bienestar Animal</h1>

<section class="mascotas-admin">
  <h2>🐶 Mascotas Extraviadas</h2>
  <div class="mascotas-lista">
    <?php if (!empty($mascotas)): ?>
      <?php foreach ($mascotas as $m): ?>
        <div class="mascota-card">
          <img src="/Alcaldia/UPLOADS/mascotas/<?= htmlspecialchars($m['foto']) ?>" alt="Mascota">
          <h3><?= htmlspecialchars($m['nombre_mascota']) ?></h3>
          <p><strong>Dueño(s):</strong> <?= htmlspecialchars($m['nombre_dueno']) ?></p>
          <p><strong>Última vez vista en:</strong> <?= htmlspecialchars($m['ultima_vista']) ?></p>
          <p><strong>Teléfono:</strong> <?= htmlspecialchars($m['telefono']) ?></p>
          <?php if (!empty($m['recompensa'])): ?>
            <p><strong>Recompensa:</strong> <?= htmlspecialchars($m['recompensa']) ?></p>
          <?php endif; ?>
          <?php if ($m['estado'] === 'busqueda'): ?>
            <form method="POST" action="/Alcaldia/public/cambiar-estado-mascota" style="margin-top:0.5rem;">
              <input type="hidden" name="id" value="<?= $m['id'] ?>">
              <button type="submit">✅ Marcar como encontrada</button>
            </form>
          <?php else: ?>
            <p style="color:green;"><strong>Encontrada ✅</strong></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
  <p class="no-registros">No hay mascotas registradas aún.</p>
    <?php endif; ?>
  </div>
</section>

</main>
</body>
</html>
