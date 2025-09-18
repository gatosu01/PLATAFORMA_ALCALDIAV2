<?php
namespace App\Models;

use App\Models\Conexion;

class Usuario {
    private $pdo;
    public function __construct() {
        $db = new Conexion();
        $this->pdo = $db->getConexion();
    }

    public function buscarPorCorreoOCedula($usuario) {
        $stmt = $this->pdo->prepare("SELECT id, nombre, correo, contrasena, rol FROM usuarios WHERE correo = :usuario OR cedula = :usuario");
        $stmt->execute(['usuario' => $usuario]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
