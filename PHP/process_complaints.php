<?php
session_start();
include 'conexion.php';

// Validar reCAPTCHA
if (!isset($_POST['g-recaptcha-response'])) {
    die("Error: Debes completar el reCAPTCHA.");
}
$recaptcha = $_POST['g-recaptcha-response'];
$secretKey = "6LcST5orAAAAABAKQ78E0Oj0aXFgMd6G9mtlQGIY";
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha");
$responseKeys = json_decode($response, true);
if (!$responseKeys["success"]) die("Error: Verifica el reCAPTCHA.");

// Determinar usuario
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
 
} else {
    $result = $conexion->query("SELECT id FROM usuarios WHERE correo='anonimo@anonimo.com' LIMIT 1");
    $row = $result->fetch_assoc();
    $usuario_id = $row ? $row['id'] : NULL;
    
}

// Validar campos obligatorios
if (empty($_POST['tipo']) || empty($_POST['department_id']) || empty($_POST['lat']) || empty($_POST['lng']) || empty($_POST['ubication']) || empty($_POST['complaint'])) {
    die("Error: Todos los campos obligatorios deben completarse.");
}

// Capturar datos
$tipo = $_POST['tipo'];
$departamento_id = $_POST['department_id'];



// Verificar que el departamento existe y está activo

$verificarDept = $conexion->prepare("SELECT nombre FROM departament WHERE id = ? AND estado = 'activo'");
$verificarDept->bind_param("i", $departamento_id);
$verificarDept->execute();
$verificarDept->bind_result($nombre_departamento);
$verificarDept->fetch();
$verificarDept->close();

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
// Manejo de fotos (mínimo 1, máximo 3)
$photos = [null, null, null]; // placeholders para photo1, photo2, photo3
$targetDir = "../UPLOADS/complaints/";
if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

if (!empty($_FILES['photos']['name'][0])) {
    foreach ($_FILES['photos']['name'] as $index => $name) {
        if ($index >= 3) break; // máximo 3
        if (!empty($name)) {
            $fileName = time() . "_" . basename($name);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($_FILES['photos']['tmp_name'][$index], $targetFile)) {
                $photos[$index] = $targetFile; // asigna a photo1, photo2, photo3
            }
        }
    }
} else {
    die("Error: Debes subir al menos una imagen.");
}



// Guardar en base de datos
$stmt = $conexion->prepare("INSERT INTO complaints 
(codigo_seguimiento, usuario_id, tipo, department, lat, lng, ubication, complaint, photo1, photo2, photo3)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");


$stmt->bind_param("sissddsssss", 
    $codigo, 
    $usuario_id, 
    $tipo, 
    $nombre_departamento, 
    $lat, 
    $lng, 
    $ubication, 
    $complaint, 
    $photos[0],  // photo1
    $photos[1],  // photo2
    $photos[2]   // photo3
);





if ($stmt->execute()) {
    $codigoSeguimiento = htmlspecialchars($codigo);
    $tituloConfirmacion = ($tipo === 'queja') 
        ? "¡Queja registrada con éxito!" 
        : "¡Denuncia registrada con éxito!";

    header("Location: ../VIEWS/complaints_success_view.php?codigo=$codigoSeguimiento&titulo=" . urlencode($tituloConfirmacion));
    exit;
} else {
    echo "Error al guardar: " . $conexion->error;
}
?>
