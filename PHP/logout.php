<?php
session_start();
session_unset();      // Limpia todas las variables de sesión
session_destroy();    // Destruye la sesión
header("Location: /Alcaldia/app/Views/home.php"); // Redirige al inicio
exit();
