<?php
namespace App\Controllers;

use App\Models\Conexion;

class SignUpController {
    public function index() {
        $mensaje = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST['name'] ?? '';
            $apellido = $_POST['lastname'] ?? '';
            $cedula = $_POST['usercedula'] ?? '';
            $correo = $_POST['email'] ?? '';
            $contrasena = $_POST['password'] ?? '';
            $confirmar = $_POST['confirm-password'] ?? '';

            if ($contrasena !== $confirmar) {
                $mensaje = "❌ Las contraseñas no coinciden.";
            } else {
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $rol = 'usuario';
                $conexion = (new Conexion())->getConexion();
                $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, apellido, cedula, correo, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $apellido, $cedula, $correo, $hash, $rol]);
                if ($stmt->rowCount() > 0) {
                    $_SESSION['usuario_id'] = $conexion->lastInsertId();
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['correo'] = $correo;
                    $_SESSION['rol'] = $rol;
                    $_SESSION['registro_exitoso'] = true;
                    header("Location: /home");
                    exit;
                } else {
                    $mensaje = "❌ Error al registrar.";
                }
            }
        }
        include __DIR__ . '/../Views/sign_up_view.php';
    }
}
