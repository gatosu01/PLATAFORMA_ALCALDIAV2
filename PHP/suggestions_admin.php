<?php
session_start();
include 'conexion.php';

// Verificar si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: sign_in.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conexion->prepare("DELETE FROM suggestions WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: suggestions_admin.php?status=deleted");
        exit();
    } else {
        echo "Error al eliminar la sugerencia.";
    }
}
$result = $conexion->query("SELECT * FROM suggestions ORDER BY created_at DESC");
$sugerencias = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugerencias - Panel Admin</title>
    <link rel="stylesheet" href="../CSS/suggestions_admin.css">
        <link rel="stylesheet" href="../CSS/header_admin.css">

    
</head>

<body
    <?php include 'header_admin.php'; ?>
    <main>
        <h1>Buzón de Sugerencias</h1>

        <div class="contenedor-sugerencias">
            <?php if (!empty($sugerencias)): ?>
                <?php foreach ($sugerencias as $s): ?>
                    <div class="tarjeta-sugerencia">
                        <p><strong>Email:</strong> <?= htmlspecialchars($s['email']) ?></p>
                        <p><?= nl2br(htmlspecialchars($s['sugerencia'])) ?></p>
                        <small>Enviado el: <?= $s['created_at'] ?></small>

                        <form action="suggestions_admin.php" method="POST" style="margin-top: 10px;">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <button type="submit" class="btn-eliminar">Eliminar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay sugerencias aún.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>