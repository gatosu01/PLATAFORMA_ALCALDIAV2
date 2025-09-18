<?php
namespace App\Controllers;

use App\Models\Conexion;

class AdminPanelController {
    public function index() {
        session_start();
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        $db = (new Conexion())->getConexion();

        // Últimas 3 quejas
        $quejas = $db->query("SELECT COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Anónimo') AS nombre, c.complaint FROM complaints c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.tipo = 'queja' ORDER BY c.fecha DESC LIMIT 3")->fetchAll();
        // Últimas 3 denuncias
        $denuncias = $db->query("SELECT COALESCE(CONCAT(u.nombre, ' ', u.apellido), 'Anónimo') AS nombre, c.complaint FROM complaints c LEFT JOIN usuarios u ON c.usuario_id = u.id WHERE c.tipo = 'denuncia' ORDER BY c.fecha DESC LIMIT 3")->fetchAll();
        // Últimas 3 sugerencias
        $sugerencias = $db->query("SELECT email, sugerencia FROM suggestions ORDER BY created_at DESC LIMIT 3")->fetchAll();

        $nombre = $_SESSION['nombre'] ?? '';

        require __DIR__ . '/../Views/admin_panel.php';
    }
}
