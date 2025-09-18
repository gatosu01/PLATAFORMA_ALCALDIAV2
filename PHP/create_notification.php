<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $mensaje = trim($_POST['mensaje']);

    if (!empty($titulo) && !empty($mensaje)) {
        $stmt = $conexion->prepare("INSERT INTO notifications (titulo, mensaje) VALUES (?, ?)");
        $stmt->bind_param("ss", $titulo, $mensaje);

        if ($stmt->execute()) {
            header("Location: notification_admin.php?status=ok");
            exit();
        } else {
            echo "Error al guardar la notificación.";
        }

        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>
