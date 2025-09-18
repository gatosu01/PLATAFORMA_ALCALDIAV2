<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: sign_in.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // Obtener el nombre de la imagen para borrarla del servidor
    $stmt = $conexion->prepare("SELECT image_path FROM slider_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image_path);
    if ($stmt->fetch()) {
        $file = "../IMG/" . $image_path;
        if (file_exists($file)) unlink($file);
    }
    $stmt->close();

    // Borrar de la base de datos
    $stmt2 = $conexion->prepare("DELETE FROM slider_images WHERE id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $stmt2->close();
}

header("Location: admin_slider.php");
exit;
