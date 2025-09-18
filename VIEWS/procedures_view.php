<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trámites - Alcaldía de Santiago</title>
    <link rel="stylesheet" href="../CSS/procedures.css">
    <link rel="stylesheet" href="../CSS/header.css">
</head>

<body>
    <?php include '../PHP/header.php'; ?>
    <header>
        <h1>Trámites Municipales</h1>
        <p>Seleccione una categoría para ver los procedimientos y requisitos.</p>
    </header>

    <main>
        <!-- Barra de búsqueda -->
        <input type="text" id="search-bar" placeholder="Buscar trámite...">

        <section id="procedures-container">
            <!-- Aquí se cargan dinámicamente los tramites -->
        </section>
    </main>
    <?php include '../VIEWS/footer.php'; ?>


    <script src="../JS/procedures.js"></script>
</body>

</html>