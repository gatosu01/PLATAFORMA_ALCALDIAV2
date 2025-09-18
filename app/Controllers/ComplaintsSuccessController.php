<?php
namespace App\Controllers;

class ComplaintsSuccessController {
    public function index() {
        $titulo = isset($_GET['titulo']) ? urldecode($_GET['titulo']) : '';
        $codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
        require __DIR__ . '/../Views/complaints_success_view.php';
    }
}
