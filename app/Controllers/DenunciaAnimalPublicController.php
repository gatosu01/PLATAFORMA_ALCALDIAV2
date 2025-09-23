<?php
namespace App\Controllers;

class DenunciaAnimalPublicController {
    public function index() {
        $denuncia_enviada = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../Models/Conexion.php';
            $db = new \App\Models\Conexion();
            $pdo = $db->getConexion();

            $foto = $_FILES['foto']['name'] ?? '';
            $ubicacion = $_POST['ubicacion_mascota'] ?? '';
            $color_casa = $_POST['color_casa'] ?? '';
            $descripcion = $_POST['descripcion_mascota'] ?? '';

            $rutaDestino = __DIR__ . '/../../UPLOADS/D_animal/' . basename($foto);
            if (!empty($foto) && move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
                $stmt = $pdo->prepare("INSERT INTO animal_complaint (photo, pet_location, house_color, pet_description, status, created_at) VALUES (?, ?, ?, ?, 'Esperando', NOW())");
                if ($stmt->execute([$foto, $ubicacion, $color_casa, $descripcion])) {
                    $denuncia_enviada = true;
                }
            }
        }
        // Pasar la variable a la vista
        extract(['denuncia_enviada' => $denuncia_enviada]);
        require __DIR__ . '/../Views/denuncia_animal_view.php';
    }
}
