<?php
header('Content-Type: application/json');

// Incluir conexión existente
require_once __DIR__ . '/conexion.php';

// Consultar tramites y requisitos
$sql = "SELECT id, nombre, requisitos, categoria FROM procedures ORDER BY categoria, nombre";
$resultado = $conexion->query($sql);

$procedures = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $procedures[] = [
            'id' => $fila['id'],
            'nombre' => $fila['nombre'],
            'requisitos' => $fila['requisitos'],
            'categoria' => $fila['categoria']
        ];
    }
}

echo json_encode($procedures, JSON_UNESCAPED_UNICODE);
$conexion->close();
