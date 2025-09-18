<?php
namespace App\Models;
use PDO;
use App\Models\Conexion;
class Suggestion {
    public function guardar($email, $sugerencia) {
        $conexion = (new Conexion())->getConexion();
        $stmt = $conexion->prepare("INSERT INTO suggestions (email, sugerencia, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$email, $sugerencia]);
    }
    public function todas() {
        $conexion = (new Conexion())->getConexion();
        $stmt = $conexion->query("SELECT * FROM suggestions ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function eliminar($id) {
        $conexion = (new Conexion())->getConexion();
        $stmt = $conexion->prepare("DELETE FROM suggestions WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
