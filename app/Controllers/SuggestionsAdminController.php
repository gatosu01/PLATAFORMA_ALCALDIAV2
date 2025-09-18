<?php
namespace App\Controllers;
use App\Models\Suggestion;

class SuggestionsAdminController {
    public function index() {
        $model = new Suggestion();
        $sugerencias = $model->todas();
        require __DIR__ . '/../Views/suggestions_admin.php';
    }
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $model = new Suggestion();
            $model->eliminar($_POST['id']);
            header("Location: /Alcaldia/suggestions_admin?status=deleted");
            exit();
        }
    }
}
