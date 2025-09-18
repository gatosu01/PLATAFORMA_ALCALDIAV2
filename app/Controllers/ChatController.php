<?php
namespace App\Controllers;

use App\Models\Conexion;

class ChatController {
    // Endpoint para enviar mensaje
    public function sendMessage() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }
        $usuario_id = $_SESSION['usuario_id'];
        $id = $_POST['id'] ?? null;
        $mensaje = trim($_POST['mensaje'] ?? '');
        $tipo = $_POST['tipo'] ?? 'usuario'; // usuario o admin
        $accion = $_POST['accion'] ?? null;
        if (!$id || !$mensaje) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }
        $conexion = (new Conexion())->getConexion();
        // Lógica para agregar el mensaje según tipo y acción
        if ($tipo === 'usuario') {
            $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\nUsuario: ', ?) WHERE id = ? AND usuario_id = ? AND estado = 'En curso'");
            $stmt->execute([$mensaje, $id, $usuario_id]);
        } else {
            // Admin: seguir/finalizar
            if ($accion === 'seguir') {
                $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\nAdministrador: ', ?) WHERE id = ?");
                $stmt->execute([$mensaje, $id]);
            } elseif ($accion === 'finalizar') {
                $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\n---\n', ?), estado = 'Respondida' WHERE id = ?");
                $stmt->execute([$mensaje, $id]);
            }
        }
        echo json_encode(['success' => true]);
    }

    // Endpoint para obtener el chat actualizado
    public function getChat() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(403);
            echo 'No autorizado';
            exit;
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo 'ID faltante';
            exit;
        }
        $conexion = (new Conexion())->getConexion();
        $stmt = $conexion->prepare("SELECT respuesta_admin FROM complaints WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $mensajes = isset($row['respuesta_admin']) ? preg_split('/\n/', $row['respuesta_admin']) : [];
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
}
