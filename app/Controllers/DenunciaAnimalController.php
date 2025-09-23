<?php
namespace App\Controllers;

class DenunciaAnimalController {
    public function index() {
        session_start();
        require_once __DIR__ . '/../Models/Conexion.php';
        $db = new \App\Models\Conexion();
        $pdo = $db->getConexion();

        // Autenticación y rol
        if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'animal_admin') {
            header("Location: /Alcaldia/sign_in");
            exit();
        }

        // Actualizar status a Respondida si se envía POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $stmt = $pdo->prepare("UPDATE animal_complaint SET status=? WHERE id=?");
            $stmt->execute(['Respondida', $id]);
        }

        // Consultar denuncias pendientes
        $stmt = $pdo->prepare("SELECT * FROM animal_complaint WHERE status='Esperando' ORDER BY created_at DESC");
        $stmt->execute();
        $denuncias = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    extract(['denuncias' => $denuncias]);
    require __DIR__ . '/../Views/denuncia_animal_admin_view.php';
    }
}
