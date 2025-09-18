<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../CSS/sign_in.css" />
</head>

<body>
    <div class="container">
        <h1>INICIAR SESIÓN</h1>
        <a href="../VIEWS/index_view.php" class="btn-volver">&#8592; Volver al inicio</a>

        <?php if (!empty($mensaje)): ?>
            <div class="alert" style="color: red;"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST" action="../PHP/sign_in.php">
            <div class="form-group">
                <label for="email">Email o Cédula:</label>
                <input type="text" id="email" name="usuario" placeholder="Introduzca su Correo o Cédula" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Introduzca su Contraseña" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>

        <p class="message">¿No tienes una cuenta? <a href="../PHP/sign_up.php">Regístrate</a></p>
    </div>
</body>

</html>