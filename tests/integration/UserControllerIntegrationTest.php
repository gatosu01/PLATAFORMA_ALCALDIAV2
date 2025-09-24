
<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use App\Controllers\LoginController;
use App\Models\Usuario;

class UserControllerIntegrationTest extends TestCase {
    public function testLoginFlow() {
        $usuarioModel = new Usuario();
        $user = $usuarioModel->buscarPorCorreoOCedula('carlos@gmail.com');
        $this->assertIsArray($user);
        $this->assertArrayHasKey('correo', $user);
        // Aquí podrías simular el flujo de login usando LoginController si tienes métodos públicos para ello
    }
}
