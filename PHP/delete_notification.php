<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conexion->prepare("DELETE FROM notifications WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: notification_admin.php?status=deleted");
        exit();
    } else {
        echo "Error al eliminar la notificación.";
    }

    $stmt->close();
} else {
    header("Location: notification_admin.php");
    exit();
}
?>
