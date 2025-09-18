<?php
namespace App\Controllers;

class ProcessComplaintsController {
    public function index() {
        // DEBUG: Mostrar usuario_id en session justo antes de guardar
        echo '<div style="background:#ffe082;padding:8px;">usuario_id en session: ' . (isset($_SESSION['usuario_id']) ? htmlspecialchars($_SESSION['usuario_id']) : 'no existe') . '</div>';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../Models/Conexion.php';
    $conexion = (new \App\Models\Conexion())->getConexion();

        // Validar reCAPTCHA
        if (!isset($_POST['g-recaptcha-response'])) {
            die("Error: Debes completar el reCAPTCHA.");
        }
        $recaptcha = $_POST['g-recaptcha-response'];
        $secretKey = "6LcST5orAAAAABAKQ78E0Oj0aXFgMd6G9mtlQGIY";
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha");
        $responseKeys = json_decode($response, true);
        if (!$responseKeys["success"]) die("Error: Verifica el reCAPTCHA.");

       
        // Determinar usuario: logeado o anónimo (acepta id=0)
        if (isset($_SESSION['usuario_id'])) {
            $usuario_id = $_SESSION['usuario_id'];
        } else {
            $stmtAnon = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
            $stmtAnon->execute(['anonimo@anonimo.com']);
            $row = $stmtAnon->fetch(\PDO::FETCH_ASSOC);
            if ($row && $row['id'] !== null) {
                $usuario_id = $row['id'];
            } else {
                die("Error: No se encontró el usuario anónimo en la base de datos.");
            }
        }

        // Validar campos obligatorios
        if (empty($_POST['tipo']) || empty($_POST['department_id']) || empty($_POST['lat']) || empty($_POST['lng']) || empty($_POST['ubication']) || empty($_POST['complaint'])) {
            die("Error: Todos los campos obligatorios deben completarse.");
        }

        // Capturar datos
        $tipo = $_POST['tipo'];
        $departamento_id = $_POST['department_id'];

        // Verificar que el departamento existe y está activo
        $stmtDept = $conexion->prepare("SELECT nombre FROM departament WHERE id = ? AND estado = 'activo'");
        $stmtDept->execute([$departamento_id]);
        $rowDept = $stmtDept->fetch(\PDO::FETCH_ASSOC);
        $nombre_departamento = $rowDept ? $rowDept['nombre'] : null;
        if (empty($nombre_departamento)) {
            die("Error: Departamento inválido o inactivo.");
        }

        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $ubication = $_POST['ubication'];
        $complaint = $_POST['complaint'];

        // Prefijo y código
        $prefijo = ($tipo === 'queja') ? 'QJ-' : 'DN-';
        $codigo = strtoupper($prefijo . substr(md5(uniqid(mt_rand(), true)), 0, 10));

        // Manejo de fotos (mínimo 1, máximo 3)
        $photos = [null, null, null]; // placeholders para photo1, photo2, photo3
        $targetDir = __DIR__ . '/../../UPLOADS/complaints/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        if (!empty($_FILES['photos']['name'][0])) {
            foreach ($_FILES['photos']['name'] as $index => $name) {
                if ($index >= 3) break; // máximo 3
                if (!empty($name)) {
                    $fileName = time() . "_" . basename($name);
                    $targetFile = $targetDir . $fileName;
                    if (move_uploaded_file($_FILES['photos']['tmp_name'][$index], $targetFile)) {
                        $photos[$index] = $fileName; // solo el nombre del archivo
                    }
                }
            }
        } else {
            die("Error: Debes subir al menos una imagen.");
        }

        // Guardar en base de datos (PDO)
        $stmt = $conexion->prepare("INSERT INTO complaints 
            (codigo_seguimiento, usuario_id, tipo, department, lat, lng, ubication, complaint, photo1, photo2, photo3)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $codigo,
            $usuario_id,
            $tipo,
            $nombre_departamento,
            $lat,
            $lng,
            $ubication,
            $complaint,
            $photos[0],
            $photos[1],
            $photos[2]
        ]);
        if ($result) {
            $codigoSeguimiento = htmlspecialchars($codigo);
            $tituloConfirmacion = ($tipo === 'queja') 
                ? "¡Queja registrada con éxito!" 
                : "¡Denuncia registrada con éxito!";
            header("Location: /Alcaldia/complaints-success?codigo=$codigoSeguimiento&titulo=" . urlencode($tituloConfirmacion));
            exit;
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Error al guardar: " . $errorInfo[2];
        }
    }
}
