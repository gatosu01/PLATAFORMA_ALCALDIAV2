<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_location = $_POST['ubicacion_mascota'];
    $house_color   = $_POST['color_casa'];
    $pet_description       = $_POST['descripcion_mascota'];


    // Subida de la foto
    $photo = $_FILES['foto']['name'];
    $rutaDestino = '../UPLOADS/D_animal/' . basename($photo);

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
        $stmt = $conexion->prepare("INSERT INTO animal_complaint (photo, pet_location, house_color, pet_description) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $photo, $pet_location, $house_color, $pet_description);


        if ($stmt->execute()) {
            $_SESSION['denuncia_enviada'] = true; // Activar alerta
            header("Location: ../VIEWS/denuncia_animal_view.php");
            exit();
        } else {
            echo "Error al guardar en la BD.";
        }
    } else {
        echo "Error al subir la foto.";
    }
}
