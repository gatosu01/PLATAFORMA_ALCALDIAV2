<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    exit('No autorizado');
}

if (!isset($_GET['id'])) {
    exit('Falta el ID');
}

$id = intval($_GET['id']);

// Verificar que el usuario tenga permiso para ver esta denuncia
if ($_SESSION['rol'] !== 'admin') {
    // Usuario normal: solo puede ver sus denuncias
    $stmt = $conexion->prepare("SELECT respuesta_admin FROM complaints WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
} else {
    // Admin puede ver cualquiera
    $stmt = $conexion->prepare("SELECT respuesta_admin FROM complaints WHERE id = ?");
    $stmt->bind_param("i", $id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $mensajes = preg_split('/\n/', $row['respuesta_admin']);
    foreach ($mensajes as $msg) {
        $msg = trim($msg);
        if ($msg === '') continue;
        if (stripos($msg, 'Usuario:') === 0) {
            echo '<div class="mensaje usuario">' . htmlspecialchars($msg) . '</div>';
        } else {
            echo '<div class="mensaje admin">' . htmlspecialchars($msg) . '</div>';
        }
    }
}
