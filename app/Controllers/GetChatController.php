<?php
namespace App\Controllers;

use App\Models\Conexion;

class GetChatController {
    public function index() {
        session_start();
        $conexion = (new Conexion())->getConexion();

        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(403);
            exit('No autorizado');
        }

        if (!isset($_GET['id'])) {
            http_response_code(400);
            exit('Falta el ID');
        }

        $id = intval($_GET['id']);

        // Verificar que el usuario tenga permiso para ver esta denuncia
        if ($_SESSION['rol'] !== 'admin') {
            // Usuario normal: solo puede ver sus denuncias
            $stmt = $conexion->prepare("SELECT respuesta_admin FROM complaints WHERE id = ? AND usuario_id = ?");
            $stmt->execute([$id, $_SESSION['usuario_id']]);
        } else {
            // Admin puede ver cualquiera
            $stmt = $conexion->prepare("SELECT respuesta_admin FROM complaints WHERE id = ?");
            $stmt->execute([$id]);
        }

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
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
    }
}
