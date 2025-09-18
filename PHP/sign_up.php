<?php
session_start();
include 'conexion.php';

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

        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, apellido, cedula, correo, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nombre, $apellido, $cedula, $correo, $hash, $rol);

        if ($stmt->execute()) {
            $_SESSION['usuario_id'] = $conexion->insert_id;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['correo'] = $correo;
            $_SESSION['rol'] = $rol;

            $_SESSION['registro_exitoso'] = true;
            // Redirigir al index
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "❌ Error al registrar: " . $stmt->error;
        }

        $stmt->close();
        $conexion->close();
    }
}

include '../VIEWS/sign_up_view.php';
