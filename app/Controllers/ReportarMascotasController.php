<?php
namespace App\Controllers;

class ReportarMascotasController {
    public function index() {
        require __DIR__ . '/../Views/reportar_mascotas_view.php';
    }

    public function store() {
        session_start();
        require __DIR__ . '/../../PHP/conexion.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_mascota = $_POST['nombre_mascota'];
            $nombre_dueno   = $_POST['nombre_dueno'];
            $ultima_vista   = $_POST['ultima_vista'];
            $telefono       = $_POST['telefono'];
            $recompensa     = $_POST['recompensa'] ?? '';

            // Verificar si ya existe la mascota con el mismo nombre y dueño
            $check = $conexion->prepare("SELECT id FROM mascotas_extraviadas WHERE nombre_mascota = ? AND nombre_dueno = ?");
            $check->bind_param("ss", $nombre_mascota, $nombre_dueno);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                echo "<script>alert('Esta mascota ya ha sido reportada.'); window.location.href='/Alcaldia/reportar-mascotas';</script>";
                return;
            }

            // Subida de la foto
            $foto = $_FILES['foto']['name'];
            $rutaDestino = __DIR__ . '/../../UPLOADS/mascotas/' . basename($foto);

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
                $stmt = $conexion->prepare("INSERT INTO mascotas_extraviadas (foto, nombre_mascota, nombre_dueno, ultima_vista, telefono, recompensa) VALUES (?,?,?,?,?,?)");
                $stmt->bind_param("ssssss", $foto, $nombre_mascota, $nombre_dueno, $ultima_vista, $telefono, $recompensa);

                if ($stmt->execute()) {
                    $_SESSION['mascota_reportada'] = true;
                    header("Location: /Alcaldia/mascotas-extraviadas");
                    exit();
                } else {
                    echo "Error al guardar en la BD.";
                }
            } else {
                echo "Error al subir la foto.";
            }
        }
    }
}
