<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/Models/Conexion.php';
$conexion = (new \App\Models\Conexion())->getConexion();
$sql = "SELECT id, nombre, requisitos, categoria FROM procedures ORDER BY categoria, nombre";
$stmt = $conexion->query($sql);
$procedures = [];
if ($stmt) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $fila) {
        $procedures[] = [
            'id' => $fila['id'],
            'nombre' => $fila['nombre'],
            'requisitos' => $fila['requisitos'],
            'categoria' => $fila['categoria']
        ];
    }
}
echo json_encode($procedures, JSON_UNESCAPED_UNICODE);
