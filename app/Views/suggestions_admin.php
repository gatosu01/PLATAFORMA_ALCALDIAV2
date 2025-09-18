<?php
session_start();
include __DIR__ . '/../Models/Conexion.php';
$conexion = (new \App\Models\Conexion())->getConexion();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Alcaldia/app/Views/sign_in.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conexion->prepare("DELETE FROM suggestions WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: /Alcaldia/app/Views/suggestions_admin.php?status=deleted");
    exit();
}
$result = $conexion->query("SELECT * FROM suggestions ORDER BY created_at DESC");
$sugerencias = $result->fetchAll(PDO::FETCH_ASSOC);
// Filtrar sugerencias duplicadas por contenido
$sugerenciasUnicas = [];
if (!empty($sugerencias)) {
    foreach ($sugerencias as $s) {
        if (!in_array($s['sugerencia'], array_column($sugerenciasUnicas, 'sugerencia'))) {
            $sugerenciasUnicas[] = $s;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugerencias - Panel Admin</title>
    <link rel="stylesheet" href="/Alcaldia/public/CSS/suggestions_admin.css">
    <link rel="stylesheet" href="/Alcaldia/public/CSS/header_admin.css">
</head>
<body>
    <?php include __DIR__ . '/header_admin.php'; ?>
    <main>
        <h1>Buzón de Sugerencias</h1>
        <div class="contenedor-sugerencias">
            <?php if (!empty($sugerenciasUnicas)): ?>
                <?php foreach ($sugerenciasUnicas as $s): ?>
                    <div class="tarjeta-sugerencia">
                        <p><strong>Email:</strong> <?= htmlspecialchars($s['email']) ?></p>
                        <p><?= nl2br(htmlspecialchars($s['sugerencia'])) ?></p>
                        <small>Enviado el: <?= htmlspecialchars($s['created_at']) ?></small>
                        <form action="/Alcaldia/suggestions_admin/eliminar" method="POST" style="margin-top: 10px;">
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
