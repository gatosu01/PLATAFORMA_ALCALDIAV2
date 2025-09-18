<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postúlate - Alcaldía de Santiago</title>
    <link rel="stylesheet" href="/public/CSS/index.css">
    <link rel="stylesheet" href="/Alcaldia/public/CSS/header.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main>
        <section class="postulate-form">
            <div class="cuadroblanco">
                <h2>Formulario de Postulación</h2>
                <form action="/postulate-process" method="POST" enctype="multipart/form-data">
                    <label for="nombre">Nombre completo:</label>
                    <input type="text" name="nombre" id="nombre" required>
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" name="correo" id="correo" required>
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" required>
                    <label for="departamento">Departamento:</label>
                    <select name="departamento" id="departamento" required>
                        <option value="">Seleccione un departamento</option>
                        <?php if (!empty($departamentos)): ?>
                            <?php foreach ($departamentos as $row): ?>
                                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <label for="subdepartamento">Área de preferencia:</label>
                    <select name="subdepartamento" id="subdepartamento" required>
                        <option value="">Seleccione un subdepartamento</option>
                    </select>
                    <label for="cv">Currículum en PDF:</label>
                    <input type="file" name="cv" id="cv" accept="application/pdf" required>
                    <button type="submit">Enviar CV</button>
                </form>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="/public/JS/subdepartament.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
</body>
</html>
