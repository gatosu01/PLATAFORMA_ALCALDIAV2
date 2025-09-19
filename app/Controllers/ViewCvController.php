<?php
namespace App\Controllers;
use App\Models\Conexion;
class ViewCvController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            http_response_code(403);
            echo 'Acceso denegado.';
            exit;
        }
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo 'ID no especificado.';
            exit;
        }
        $id = intval($_GET['id']);
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $stmt = $pdo->prepare('SELECT archivo_pdf FROM postulaciones WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row || empty($row['archivo_pdf'])) {
            http_response_code(404);
            echo 'Archivo no encontrado.';
            exit;
        }
        $file = __DIR__ . '/../../UPLOADS/cv/' . $row['archivo_pdf'];
        if (!file_exists($file)) {
            http_response_code(404);
            echo 'Archivo no existe.<br>';
            echo 'Ruta buscada: ' . $file . '<br>';
            echo 'Nombre en BD: ' . htmlspecialchars($row['archivo_pdf']) . '<br>';
            exit;
        }
        echo 'Ruta encontrada: ' . $file . '<br>';
        echo 'Nombre en BD: ' . htmlspecialchars($row['archivo_pdf']) . '<br>';
        // Puedes comentar las siguientes dos líneas para ver solo el debug
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($file) . '"');
        readfile($file);
        exit;
    }
}
