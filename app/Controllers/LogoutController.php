<?php
namespace App\Controllers;

class LogoutController {
    public function index() {
        // Lógica de cierre de sesión
        session_start();
        session_destroy();
        header('Location: /Alcaldia/');
        exit;
    }
}
