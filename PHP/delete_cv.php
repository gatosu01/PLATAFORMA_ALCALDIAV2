<?php
session_start();
include 'conexion.php';

// Verificar rol admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: sign_in.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Obtener el nombre del archivo para eliminarlo del servidor
    $stmt = $conexion->prepare("SELECT archivo_pdf FROM postulaciones WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($archivo_pdf);
    $stmt->fetch();
    $stmt->close();

    if ($archivo_pdf && file_exists('../UPLOADS/cv/' . $archivo_pdf)) {
        unlink('../UPLOADS/cv/' . $archivo_pdf); // Eliminar archivo
    }

    // Eliminar registro de la base de datos
    $stmt = $conexion->prepare("DELETE FROM postulaciones WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../PHP/admin_postulations.php");
    exit;
}
?>
