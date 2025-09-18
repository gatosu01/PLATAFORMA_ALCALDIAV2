<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $departamento = trim($_POST['departamento']);
    $subdepartamento = trim($_POST['subdepartamento']);

    // Validar archivo
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['cv']['tmp_name'];
        $fileName = $_FILES['cv']['name'];
        $fileSize = $_FILES['cv']['size'];
        $fileType = $_FILES['cv']['type'];
        $fileNameCmps = pathinfo($fileName);
        $fileExtension = strtolower($fileNameCmps['extension']);

        // Solo PDF
        if ($fileExtension === 'pdf') {
            $newFileName = uniqid('cv_') . '.' . $fileExtension;
            $uploadFileDir = '../UPLOADS/cv/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                // Guardar en DB
                $stmt = $conexion->prepare("INSERT INTO postulaciones (nombre, correo, telefono, departamento, subdepartamento, archivo_pdf) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $nombre, $correo, $telefono, $departamento, $subdepartamento, $newFileName);

                if ($stmt->execute()) {
                    // ✅ Aquí activamos la sesión para mostrar Alertify
                    $_SESSION['postulacion_enviada'] = true;

                    header("Location: ../VIEWS/postulate_view.php");
                    exit();
                } else {
                    $_SESSION['postulacion_error'] = "Error al guardar en la base de datos.";
                    header("Location: ../VIEWS/postulate_view.php");
                    exit();
                }

                $stmt->close();

            } else {
                $_SESSION['postulacion_error'] = "Error al mover el archivo.";
                header("Location: ../VIEWS/postulate_view.php");
                exit();
            }
        } else {
            $_SESSION['postulacion_error'] = "Solo se permiten archivos PDF.";
            header("Location: ../VIEWS/postulate_view.php");
            exit();
        }
    } else {
        $_SESSION['postulacion_error'] = "No se recibió el archivo.";
        header("Location: ../VIEWS/postulate_view.php");
        exit();
    }
}
