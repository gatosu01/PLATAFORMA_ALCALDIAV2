<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Denuncia Animal</title>
    <link rel="stylesheet" href="/Alcaldia/CSS/index.css">
    <link rel="stylesheet" href="/Alcaldia/CSS/header.css" />
    <link rel="stylesheet" href="/Alcaldia/CSS/mascotas.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main>
        <h2 style="text-align:center; margin-top:1rem;">🚨 Denuncia Animal</h2>
    <form action="/Alcaldia/denuncia-animal" method="POST" enctype="multipart/form-data" class="form-mascota">
            <label for="foto">Foto de la denuncia animal:</label>
            <input type="file" name="foto" id="foto" accept="image/*" required>
            <label for="ubicacion_mascota">Ubicación exacta</label>
            <input type="text" name="ubicacion_mascota" id="ubicacion_mascota" required>
            <label for="color_casa">Color de casa:</label>
            <input type="text" name="color_casa" id="color_casa" required>
            <label for="descripcion_mascota">Descripción:</label>
            <input type="text" name="descripcion_mascota" id="descripcion_mascota" required>
            <button type="submit">🚨 Denuncia Animal</button>
        </form>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <?php if(isset($denuncia_enviada) && $denuncia_enviada): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        alertify.alert(
            "🚨 Denuncia Enviada",
            "¡Tu denuncia ha sido registrada correctamente! Gracias por tu colaboración.",
            function(){
                alertify.success('Denuncia registrada');
            }
        );
    });
    </script>
    <?php endif; ?>
</body>
</html>
