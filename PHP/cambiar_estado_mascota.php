<?php
session_start();
include 'conexion.php';

if(!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'animal_admin'){
    header("Location: ../VIEWS/sign_in_form.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])){
    $id = intval($_POST['id']);
    $stmt = $conexion->prepare("UPDATE mascotas_extraviadas SET estado='encontrada' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: animal_admin_panel.php");
exit();
?>
