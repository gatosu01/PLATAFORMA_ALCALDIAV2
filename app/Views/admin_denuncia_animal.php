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
    <h1 class="panel-animal-title">Panel Bienestar Animal</h1>
    <section class="denuncia-animal-admin">
        <h2>🚨 Denuncias Animales</h2>
        <div class="denuncias-lista">
            <?php if (!empty($denuncias)): ?>
                <?php foreach ($denuncias as $d): ?>
                    <div class="mascota-card">
                        <p><strong>Ubicación:</strong> <?= htmlspecialchars($d['pet_location']) ?></p>
                        <p><strong>Color de Casa:</strong> <?= htmlspecialchars($d['house_color']) ?></p>
                        <p><strong>Descripción:</strong> <?= htmlspecialchars($d['pet_description']) ?></p>
                        <?php if (!empty($d['photo'])): ?>
                            <img src="/Alcaldia/UPLOADS/D_animal/<?= htmlspecialchars($d['photo']) ?>" alt="Foto denuncia">
                            <div class="opciones-imagen">
                                <a href="/Alcaldia/UPLOADS/D_animal/<?= htmlspecialchars($d['photo']) ?>" target="_blank" class="btn-ver">👁️ Vista previa</a>
                                <a href="/Alcaldia/UPLOADS/D_animal/<?= htmlspecialchars($d['photo']) ?>" download class="btn-descargar">⬇️ Descargar</a>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="/Alcaldia/public/cambiar-estado-denuncia-animal" class="status-form">
                            <input type="hidden" name="id" value="<?= $d['id'] ?>">
                            <button type="submit" class="btn-respondida">✅ Respondida</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-registros">No hay denuncias registradas aún.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>
