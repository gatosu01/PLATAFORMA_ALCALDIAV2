<?php
session_start();
include __DIR__ . '/../Models/Conexion.php';
$conexion = (new \App\Models\Conexion())->getConexion();
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
        $stmt->execute([$nombre, $apellido, $cedula, $correo, $hash, $rol]);
        if ($stmt) {
            $_SESSION['usuario_id'] = $conexion->lastInsertId();
            $_SESSION['nombre'] = $nombre;
            $_SESSION['correo'] = $correo;
            $_SESSION['rol'] = $rol;
            $_SESSION['registro_exitoso'] = true;
            header("Location: /home");
            exit;
        } else {
            $mensaje = "❌ Error al registrar: " . $stmt->errorInfo()[2];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registrarse</title>
  <link rel="stylesheet" href="/Alcaldia/public/CSS/sign_up.css" />
</head>
<body>
  <div class="container">
    <h1>REGÍSTRATE</h1>
    <?php if (!empty($mensaje)) : ?>
      <div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <form action="" method="POST">
      <div class="form-group">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required />
      </div>
      <div class="form-group">
        <label for="lastname">Apellido:</label>
        <input type="text" id="lastname" name="lastname" required />
      </div>
      <div class="form-group">
        <label for="usercedula">Cédula/R.U.C:</label>
        <input type="text" id="usercedula" name="usercedula" required />
      </div>
      <div class="form-group">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required />
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirmar Contraseña:</label>
        <input type="password" id="confirm-password" name="confirm-password" required />
      </div>
      <button type="submit">Registrarse</button>
    </form>
    <p class="message">¿Ya tienes una cuenta? <a href="/Alcaldia/app/Views/sign_in.php">Iniciar Sesión</a></p>
  </div>
</body>
</html>
