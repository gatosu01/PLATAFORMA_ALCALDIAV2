<?php
namespace App\Controllers;

use App\Models\Conexion;

class ComplaintsAdminController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            header("Location: /Alcaldia/app/Views/sign_in.php");
            exit();
        }
        $conexion = (new Conexion())->getConexion();

        // Guardar mensajes y cambiar estados (lógica clásica por POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $respuesta = trim($_POST['respuesta'] ?? '');
            $accion = $_POST['accion'] ?? '';
            if ($accion === 'en_curso' && $respuesta !== '') {
                $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), IF(respuesta_admin IS NULL OR respuesta_admin = '', ?, CONCAT('\\nadministrador ', ?))), estado = 'En curso' WHERE id = ?");
                $stmt->execute([$respuesta, $respuesta, $id]);
            } elseif ($accion === 'seguir' && $respuesta !== '') {
                $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\\nAdministrador: ', ?) WHERE id = ?");
                $stmt->execute([$respuesta, $id]);
            } elseif ($accion === 'finalizar' && $respuesta !== '') {
                $stmt = $conexion->prepare("UPDATE complaints SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\\n---\\n', ?), estado = 'Respondida' WHERE id = ?");
                $stmt->execute([$respuesta, $id]);
            }
            // Redirigir para recargar la página y mostrar cambios
            header("Location: /Alcaldia/app/Controllers/ComplaintsAdminController.php");
            exit();
        }

        // Consultas separadas para quejas
        $stmtEsperando = $conexion->query("SELECT c.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario FROM complaints c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.tipo = 'queja' AND c.estado = 'Esperando respuesta' ORDER BY c.created_at DESC");
        $stmtEnCurso = $conexion->query("SELECT c.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario FROM complaints c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.tipo = 'queja' AND c.estado = 'En curso' ORDER BY c.created_at DESC");
        $resultEsperando = $stmtEsperando->fetchAll(\PDO::FETCH_ASSOC);
        $resultEnCurso = $stmtEnCurso->fetchAll(\PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/complaints_admin.php';
    }
}
<?php
namespace App\Controllers;

class ComplaintsAdminController {
    public function index() {
        require __DIR__ . '/../Views/complaints_admin.php';
    }
}
