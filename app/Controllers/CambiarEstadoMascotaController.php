<?php
namespace App\Controllers;

class CambiarEstadoMascotaController {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            require_once __DIR__ . '/../Models/Conexion.php';
            $db = new \App\Models\Conexion();
            $pdo = $db->getConexion();

            $stmt = $pdo->prepare("UPDATE mascotas_extraviadas SET estado = 'encontrada' WHERE id = ?");
            if ($stmt->execute([$id])) {
                // Redirige de vuelta al panel
                header("Location: /Alcaldia/animal-admin-panel");
                exit();
            } else {
                echo "Error al actualizar el estado.";
            }
        } else {
            echo "Solicitud inválida.";
        }
    }
}
