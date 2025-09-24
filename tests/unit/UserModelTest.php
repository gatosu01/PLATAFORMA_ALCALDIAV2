<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// tests/unit/UserModelTest.php
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase {
    public function testBuscarPorCorreoOCedula() {
        $usuarioModel = new App\Models\Usuario();
        // Cambia este correo por uno que exista en tu base de datos de pruebas
        $correoExistente = 'carlos@gmail.com';
        $result = $usuarioModel->buscarPorCorreoOCedula($correoExistente);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('correo', $result);
    }
}
