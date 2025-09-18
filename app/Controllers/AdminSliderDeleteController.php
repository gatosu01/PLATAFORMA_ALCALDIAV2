<?php
namespace App\Controllers;

class AdminSliderDeleteController
{
    public function index()
    {
        session_start();
        require_once __DIR__ . '/../../PHP/conexion.php';

        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header("Location: /Alcaldia/sign_in");
            exit;
        }

        $id = intval($_GET['id'] ?? 0);

        if ($id > 0) {
            // Obtener el nombre de la imagen para borrarla del servidor
            $stmt = $conexion->prepare("SELECT image_path FROM slider_images WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($image_path);
            if ($stmt->fetch()) {
                $file = $_SERVER['DOCUMENT_ROOT'] . "/Alcaldia/IMG/" . $image_path;
                if (file_exists($file)) unlink($file);
            }
            $stmt->close();

            // Borrar de la base de datos
            $stmt2 = $conexion->prepare("DELETE FROM slider_images WHERE id = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $stmt2->close();
        }

        header("Location: /Alcaldia/admin-slider");
        exit;
    }
}
