<?php
include 'conexion.php';

if (isset($_GET['departamento_id'])) {
    $departamento_id = intval($_GET['departamento_id']);
    $result = $conexion->query("SELECT id, nombre FROM subdepartamentos WHERE departamento_id = $departamento_id AND estado='activo'");
    $subdepartamentos = [];
    while ($row = $result->fetch_assoc()) {
        $subdepartamentos[] = $row;
    }
    echo json_encode($subdepartamentos);
}
