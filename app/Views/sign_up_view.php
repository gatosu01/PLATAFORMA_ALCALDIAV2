<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registrarse</title>
  <link rel="stylesheet" href="/CSS/sign_up.css" />
</head>
<body>
  <div class="container">
    <h1>REGÍSTRATE</h1>

    <?php if (!empty($mensaje)) : ?>
      <div style="color: red; margin-bottom: 15px;"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form action="/sign-up" method="POST">
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
    <p class="message">¿Ya tienes una cuenta? <a href="/sign-in">Iniciar Sesión</a></p>
  </div>
</body>
</html>
