<?php
namespace App\Controllers;

use App\Models\Conexion;

class MyComplaintsController {
    public function index() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /Alcaldia/login");
            exit();
        }
        $usuario_id = $_SESSION['usuario_id'];
        $denuncias = [];
        $quejas = [];
        $busqueda = null;
        $conexion = (new Conexion())->getConexion();

        // Procesar respuesta de usuario en denuncias en curso
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['respuesta_usuario'])) {
            $id = $_POST['id'];
            $mensaje = trim($_POST['respuesta_usuario']);
            if ($mensaje !== '') {
                $stmt = $conexion->prepare("
                    UPDATE complaints 
                    SET respuesta_admin = CONCAT(IFNULL(respuesta_admin, ''), '\nUsuario: ', ?) 
                    WHERE id = ? AND usuario_id = ? AND estado = 'En curso'
                ");
                $stmt->execute([$mensaje, $id, $usuario_id]);
                header("Location: /Alcaldia/my-complaints");
                exit();
            }
        }

        // Búsqueda por código
        if (isset($_POST['codigo_seguimiento']) && trim($_POST['codigo_seguimiento']) !== '') {
            $codigo = trim($_POST['codigo_seguimiento']);
            $stmt = $conexion->prepare("
                SELECT id, codigo_seguimiento, tipo, department, complaint, photo1, photo2, photo3, estado, respuesta_admin
                FROM complaints 
                WHERE usuario_id = ? 
                AND codigo_seguimiento = ?
                LIMIT 1
            ");
            $stmt->execute([$usuario_id, $codigo]);
            $busqueda = $stmt->fetch(\PDO::FETCH_ASSOC);
        } else {
            // Depuración: obtener todas las denuncias y quejas del usuario
            $stmt = $conexion->prepare("
                SELECT id, codigo_seguimiento, tipo, department, complaint, photo1, photo2, photo3, estado, respuesta_admin
                FROM complaints 
                WHERE usuario_id = ?
                ORDER BY created_at DESC
            ");
            $stmt->execute([$usuario_id]);
            $todas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Filtrar denuncias y quejas como antes
            $denuncias = array_filter($todas, function($d) {
                return trim(strtolower($d['tipo'])) === 'denuncia';
            });
            $quejas = array_filter($todas, function($d) {
                return trim(strtolower($d['tipo'])) === 'queja';
            });
        }

        require __DIR__ . '/../Views/my_complaints.php';
    }
}

