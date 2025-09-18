<?php
namespace App\Controllers;

class ComplaintsReportsController {
    public function index() {
        require_once __DIR__ . '/../../app/Models/Conexion.php';
        $conexion = (new \App\Models\Conexion())->getConexion();
        $departamentos_query = $conexion->query("SELECT id, nombre FROM departament WHERE estado = 'activo'");
        $departamentos = $departamentos_query ? $departamentos_query->fetchAll(\PDO::FETCH_ASSOC) : [];
        require __DIR__ . '/../Views/complaints_reports_view.php';
    }
}
