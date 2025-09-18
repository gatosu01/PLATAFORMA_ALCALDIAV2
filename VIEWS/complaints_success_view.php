<?php
session_start();
$codigo = htmlspecialchars($_GET['codigo'] ?? '');
$titulo = htmlspecialchars($_GET['titulo'] ?? '¡Registro exitoso!');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Exitoso</title>
    <meta http-equiv="refresh" content="5;url=index_view.php">
    <link rel="stylesheet" href="../CSS/complaints_reports.css">
</head>
<body>
    <div class="confirmacion">
        <h1><?= $titulo ?></h1>
        <p>Tu código de seguimiento es:</p>
        <p class="codigo"><?= $codigo ?></p>
        <p>Serás redirigido al inicio en 5 segundos...</p>
        <a href="../PHP/index.php">Haz clic aquí si no eres redirigido</a>
    </div>
</body>
</html>
