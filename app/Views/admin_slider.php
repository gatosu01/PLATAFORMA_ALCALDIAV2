<?php
session_start();
include __DIR__ . '/../Models/Conexion.php';
$conexion = (new \App\Models\Conexion())->getConexion();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /Alcaldia/app/Views/sign_in.php");
    exit;
}
$resCount = $conexion->query("SELECT position FROM slider_images");
$positionsOccupied = [];
foreach ($resCount as $row) {
    $positionsOccupied[] = intval($row['position']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    if (count($positionsOccupied) >= 3) {
        $error = "No se pueden agregar más imágenes. Todas las posiciones están ocupadas.";
    } else {
        $targetDir = __DIR__ . '/../../IMG/';
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $alt_text = $_POST['alt_text'] ?? '';
            $position = 1;
            for ($i = 1; $i <= 3; $i++) {
                if (!in_array($i, $positionsOccupied)) {
                    $position = $i;
                    break;
                }
            }
            $stmt = $conexion->prepare("INSERT INTO slider_images (image_path, alt_text, position) VALUES (?, ?, ?)");
            $stmt->execute([$fileName, $alt_text, $position]);
            header("Location: /Alcaldia/app/Views/admin_slider.php");
            exit;
        } else {
            $error = "Error al subir la imagen.";
        }
    }
}
$result = $conexion->query("SELECT * FROM slider_images ORDER BY position ASC");
$sliderImages = $result->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Slider</title>
    <link rel="stylesheet" href="/Alcaldia/public/CSS/admin_slider.css">
    <link rel="stylesheet" href="/Alcaldia/public/CSS/header_admin.css">
</head>
<body>
    <?php include __DIR__ . '/header_admin.php'; ?>
    <main>
        <h1>Administrar imágenes del slider</h1>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <h2>Agregar nueva imagen</h2>
        <?php if (count($positionsOccupied) >= 3): ?>
            <p class="error-message">Ya no se pueden agregar más imágenes. Todas las posiciones (1, 2, 3) están ocupadas.</p>
        <?php else: ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <label>Imagen: <input type="file" name="image" required></label><br>
                <label>Texto alternativo: <input type="text" name="alt_text"></label><br>
                <button type="submit">Subir imagen</button>
            </form>
        <?php endif; ?>
        <h2>Imágenes actuales</h2>
        <div class="table-wrapper">
            <table>
                <tr>
                    <th>Imagen</th>
                    <th>Texto Alternativo</th>
                    <th>Posición</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($sliderImages as $row): ?>
                <tr>
                    <td><img src="/Alcaldia/public/IMG/<?= htmlspecialchars($row['image_path']) ?>" alt="" style="height:60px;"></td>
                    <td><?= htmlspecialchars($row['alt_text']) ?></td>
                    <td><?= $row['position'] ?></td>
                    <td>
                        <a href="/Alcaldia/PHP/admin_slider_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar esta imagen?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>
</body>
</html>
