<?php
namespace App\Controllers;

use App\Models\Conexion;

class MascotasExtraviadasController {
    public function index() {
        session_start();
        $db = new Conexion();
        $pdo = $db->getConexion();
        $stmt = $pdo->prepare("SELECT * FROM mascotas_extraviadas WHERE estado='busqueda' ORDER BY fecha_reporte DESC");
        $stmt->execute();
        $mascotas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        require __DIR__ . '/../Views/mascotas_extraviadas_view.php';
    }
}
