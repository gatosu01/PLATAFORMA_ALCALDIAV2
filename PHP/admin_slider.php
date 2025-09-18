<?php
session_start();
require 'conexion.php';

// Control acceso admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: sign_in.php");
    exit;
}

// Contar imágenes actuales
$resCount = $conexion->query("SELECT position FROM slider_images");
$positionsOccupied = [];
while ($row = $resCount->fetch_assoc()) {
    $positionsOccupied[] = intval($row['position']);
}

// Manejo de formulario para subir imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    if (count($positionsOccupied) >= 3) {
        $error = "No se pueden agregar más imágenes. Todas las posiciones están ocupadas.";
    } else {
        $targetDir = "../IMG/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $alt_text = $_POST['alt_text'] ?? '';

            // Buscar primera posición libre (1, 2, 3)
            $position = 1;
            for ($i = 1; $i <= 3; $i++) {
                if (!in_array($i, $positionsOccupied)) {
                    $position = $i;
                    break;
                }
            }

            // Insertar en la base de datos
            $stmt = $conexion->prepare("INSERT INTO slider_images (image_path, alt_text, position) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $fileName, $alt_text, $position);
            $stmt->execute();
            $stmt->close();

            header("Location: admin_slider.php");
            exit;
        } else {
            $error = "Error al subir la imagen.";
        }
    }
}

// Listar imágenes actuales
$result = $conexion->query("SELECT * FROM slider_images ORDER BY position ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Slider</title>
    <link rel="stylesheet" href="../CSS/admin_slider.css">
    <link rel="stylesheet" href="../CSS/header_admin.css">
</head>
<body>
    <?php include 'header_admin.php'; ?>

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
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="../IMG/<?= htmlspecialchars($row['image_path']) ?>" alt="" style="height:60px;"></td>
                    <td><?= htmlspecialchars($row['alt_text']) ?></td>
                    <td><?= $row['position'] ?></td>
                    <td>
                        <a href="admin_slider_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar esta imagen?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </main>
</body>
</html>
