<?php
namespace App\Controllers;

use App\Models\Conexion;

class LoginController {
    public function index() {
        session_start();
        $mensaje = "";
        $max_attempts = 3;
        $wait_time = 60;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $usuario_input = trim($_POST['usuario'] ?? '');
        $attempts = $this->load_attempts();
        $key_ip = "ip_$ip";
        $key_user = $usuario_input ? "user_" . md5($usuario_input) : null;
        $now = time();
        $blocked = false;
        $remaining = 0;
        if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
        if (!isset($_SESSION['login_block_time'])) $_SESSION['login_block_time'] = 0;
        if ($_SESSION['login_attempts'] >= $max_attempts) {
            $remaining = $_SESSION['login_block_time'] + $wait_time - $now;
            if ($remaining > 0) $blocked = true;
            else { $_SESSION['login_attempts'] = 0; $_SESSION['login_block_time'] = 0; }
        }
        if (!$blocked && isset($attempts[$key_ip]) && $attempts[$key_ip]['count'] >= $max_attempts) {
            $remaining = $attempts[$key_ip]['time'] + $wait_time - $now;
            if ($remaining > 0) $blocked = true;
            else unset($attempts[$key_ip]);
        }
        if (!$blocked && $key_user && isset($attempts[$key_user]) && $attempts[$key_user]['count'] >= $max_attempts) {
            $remaining = $attempts[$key_user]['time'] + $wait_time - $now;
            if ($remaining > 0) $blocked = true;
            else unset($attempts[$key_user]);
        }
        if ($blocked) {
            $mensaje = "⏳ Demasiados intentos fallidos. Espera $remaining segundos antes de volver a intentarlo.";
            require __DIR__ . '/../Views/sign_in_form.php';
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuario = $usuario_input;
            $password = $_POST['password'] ?? '';
            if (empty($usuario) || empty($password)) {
                $mensaje = "⚠️ Por favor completa todos los campos.";
            } else {
                $conexion = Conexion::getInstance();
                $stmt = $conexion->prepare("SELECT id, nombre, correo, contrasena, rol FROM usuarios WHERE correo = ? OR cedula = ?");
                $stmt->bind_param("ss", $usuario, $usuario);
                $stmt->execute();
                $resultado = $stmt->get_result();
                if ($resultado->num_rows === 1) {
                    $usuarioData = $resultado->fetch_assoc();
                    if (password_verify($password, $usuarioData['contrasena'])) {
                        session_regenerate_id(true);
                        $_SESSION['usuario_id'] = $usuarioData['id'];
                        $_SESSION['nombre'] = $usuarioData['nombre'];
                        $_SESSION['correo'] = $usuarioData['correo'];
                        $_SESSION['rol'] = $usuarioData['rol'];
                        $_SESSION['login_attempts'] = 0;
                        $_SESSION['login_block_time'] = 0;
                        unset($attempts[$key_ip]);
                        if ($key_user) unset($attempts[$key_user]);
                        $this->save_attempts($attempts);
                        if ($usuarioData['rol'] === 'admin') {
                            header("Location: /Alcaldia/public/admin-panel");
                        } elseif($usuarioData['rol'] === 'animal_admin'){
                            header("Location: /Alcaldia/public/animal-admin-panel");
                        }else {
                            header("Location: /Alcaldia/public/home");
                        }
                        exit();
                    } else {
                        $mensaje = "❌ Usuario o contraseña incorrectos.";
                    }
                } else {
                    $mensaje = "❌ Usuario o contraseña incorrectos.";
                }
                $_SESSION['login_attempts'] += 1;
                if ($_SESSION['login_attempts'] >= $max_attempts) {
                    $_SESSION['login_block_time'] = $now;
                }
                if (!isset($attempts[$key_ip])) $attempts[$key_ip] = ['count' => 0, 'time' => 0];
                $attempts[$key_ip]['count'] += 1;
                if ($attempts[$key_ip]['count'] >= $max_attempts) $attempts[$key_ip]['time'] = $now;
                if ($key_user) {
                    if (!isset($attempts[$key_user])) $attempts[$key_user] = ['count' => 0, 'time' => 0];
                    $attempts[$key_user]['count'] += 1;
                    if ($attempts[$key_user]['count'] >= $max_attempts) $attempts[$key_user]['time'] = $now;
                }
                $this->save_attempts($attempts);
                $stmt->close();
            }
        }
        require __DIR__ . '/../Views/sign_in_form.php';
    }
    private function load_attempts() {
        $file = __DIR__ . '/../../DATA/login_attempts.json';
        if (!file_exists($file)) return [];
        $data = file_get_contents($file);
        return $data ? json_decode($data, true) : [];
    }
    private function save_attempts($attempts) {
        $file = __DIR__ . '/../../DATA/login_attempts.json';
        file_put_contents($file, json_encode($attempts));
    }
}
