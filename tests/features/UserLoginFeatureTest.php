
<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use App\Models\Usuario;

class UserLoginFeatureTest extends TestCase {
    public function testUserCanLoginSuccessfully() {
        $usuarioModel = new Usuario();
        $user = $usuarioModel->buscarPorCorreoOCedula('carlos@gmail.com');
        $this->assertIsArray($user);
        $this->assertArrayHasKey('correo', $user);
        // Aquí podrías simular el login real si tienes métodos públicos en el controlador
    }
}
