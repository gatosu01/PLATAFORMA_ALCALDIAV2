<?php
namespace App\Controllers;

class AnimalAdminPanelController {
    public function index() {
        session_start();
        // Usar el modelo Conexion (PDO)
        require_once __DIR__ . '/../Models/Conexion.php';
        $db = new \App\Models\Conexion();
        $pdo = $db->getConexion();

        // Autenticación y rol
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'animal_admin') {
            header("Location: /Alcaldia/sign_in");
            exit();
        }

        // Consulta mascotas extraviadas con PDO
        $stmt = $pdo->prepare("SELECT * FROM mascotas_extraviadas WHERE estado='busqueda' ORDER BY fecha_reporte DESC");
        $stmt->execute();
        $mascotas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        extract(['mascotas' => $mascotas]);
        require __DIR__ . '/../Views/animal_admin_panel_view.php';
    }
}
