<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_mascota = $_POST['nombre_mascota'];
    $nombre_dueno   = $_POST['nombre_dueno'];
    $ultima_vista   = $_POST['ultima_vista'];
    $telefono       = $_POST['telefono'];
    $recompensa     = $_POST['recompensa'] ?? '';

    // Subida de la foto
    $foto = $_FILES['foto']['name'];
    $rutaDestino = '../UPLOADS/mascotas/' . basename($foto);

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
        $stmt = $conexion->prepare("INSERT INTO mascotas_extraviadas (foto, nombre_mascota, nombre_dueno, ultima_vista, telefono, recompensa) 
         VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $foto, $nombre_mascota, $nombre_dueno, $ultima_vista, $telefono, $recompensa);


        if ($stmt->execute()) {
            $_SESSION['mascota_reportada'] = true; // Activar alerta
            header("Location: ../VIEWS/mascotas_extraviadas_view.php");
            exit();
        } else {
            echo "Error al guardar en la BD.";
        }
    } else {
        echo "Error al subir la foto.";
    }
}
