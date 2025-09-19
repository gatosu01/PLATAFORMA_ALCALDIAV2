<?php
namespace App\Controllers;

use App\Models\Conexion;

class PostulateController {
    public function index() {
        // Obtener departamentos activos
        $conexion = new Conexion();
        $pdo = $conexion->getConexion();
        $stmt = $pdo->query("SELECT id, nombre FROM departament WHERE estado='activo'");
        $departamentos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // Pasar variable a la vista
        require __DIR__ . '/../Views/postulate_view.php';
    }

        public function process() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                session_start();
                $nombre = $_POST['nombre'] ?? '';
                $correo = $_POST['correo'] ?? '';
                $telefono = $_POST['telefono'] ?? '';
                $departamento = $_POST['departamento'] ?? '';
                $subdepartamento = $_POST['subdepartamento'] ?? '';
                $cv = $_FILES['cv'] ?? null;

                // Validaciones básicas
                if (!$nombre || !$correo || !$telefono || !$departamento || !$subdepartamento || !$cv) {
                    $_SESSION['postulacion_error'] = 'Todos los campos son obligatorios.';
                    header('Location: /Alcaldia/postulate');
                    exit;
                }

                // Guardar archivo CV
                $cvDir = __DIR__ . '/../../UPLOADS/cv/';
                if (!is_dir($cvDir)) {
                    mkdir($cvDir, 0777, true);
                }
                $cvName = uniqid('cv_') . '_' . basename($cv['name']);
                $cvPath = $cvDir . $cvName;
                if (!move_uploaded_file($cv['tmp_name'], $cvPath)) {
                    $_SESSION['postulacion_error'] = 'Error al subir el archivo.';
                    header('Location: /Alcaldia/postulate');
                    exit;
                }

                // Guardar datos en la base de datos
                try {
                    $conexion = new Conexion();
                    $pdo = $conexion->getConexion();
                    $stmt = $pdo->prepare("INSERT INTO postulaciones (nombre, correo, telefono, archivo_pdf, fecha_postulacion, departamento, subdepartamento) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
                    $stmt->execute([$nombre, $correo, $telefono, $cvName, $departamento, $subdepartamento]);
                    $_SESSION['postulacion_enviada'] = true;
                } catch (\Exception $e) {
                    $_SESSION['postulacion_error'] = 'Error al guardar la postulación.';
                }
                header('Location: /Alcaldia/postulate');
                exit;
            }
        }
}
