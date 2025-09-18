<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trámites - Alcaldía de Santiago</title>
    <link rel="stylesheet" href="/Alcaldia/public/CSS/procedures.css">
    <link rel="stylesheet" href="/Alcaldia/public/CSS/header.css" />
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main>
        <section class="title">
            <h1>Trámites Municipales</h1>
            <p>Seleccione una categoría para ver los procedimientos y requisitos.</p>
        </section>
        <input type="text" id="search-bar" placeholder="Buscar trámite...">
        <section id="procedures-container" class="procedures-list">
            <!-- Las categorías y trámites se cargan dinámicamente aquí -->
        </section>
        <div id="no-results" style="display:none;text-align:center;color:red;margin:20px 0;">No se encontraron trámites.</div>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
    <script src="/Alcaldia/public/JS/procedures.js"></script>
</body>
</html>
