<?php
namespace App\Controllers;

class DeleteCvController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: /Alcaldia/sign_in');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $conexion = new \App\Models\Conexion();
            $pdo = $conexion->getConexion();
            // Obtener el nombre del archivo
            $stmt = $pdo->prepare('SELECT archivo_pdf FROM postulaciones WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($row && !empty($row['archivo_pdf'])) {
                $file = __DIR__ . '/../../UPLOADS/cv/' . $row['archivo_pdf'];
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            // Eliminar registro de la base de datos
            $stmtDel = $pdo->prepare('DELETE FROM postulaciones WHERE id = ?');
            $stmtDel->execute([$id]);
        }
        header('Location: /Alcaldia/admin-postulations');
        exit;
    }
}
