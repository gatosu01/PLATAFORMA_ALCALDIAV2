<?php
namespace App\Controllers;

use App\Models\Conexion;

class GetSubdepartamentController {
    public function index() {
        header('Content-Type: application/json');
        $departamento_id = isset($_GET['departamento_id']) ? $_GET['departamento_id'] : null;
        $result = [];
        if ($departamento_id) {
            $conexion = new Conexion();
            $pdo = $conexion->getConexion();
                $stmt = $pdo->prepare("SELECT id, nombre FROM subdepartamentos WHERE departamento_id = ? AND estado = 'activo'");
            $stmt->execute([$departamento_id]);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        echo json_encode($result);
        exit;
    }
}
