<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/PHPMailer.php'; 
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/SMTP.php';
include 'conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $sugerencia = trim($_POST['sugerencia']);

    if (!empty($email) && !empty($sugerencia)) {
        $stmt = $conexion->prepare("INSERT INTO suggestions (email, sugerencia, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $sugerencia);

        if ($stmt->execute()) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'alcaldiadeveloper@gmail.com';
                $mail->Password   = 'ngqx rjdk tpcb tniy'; // Contraseña de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('alcaldiadeveloper@gmail.com', 'Alcaldia de Santiago');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Gracias por tu sugerencia';
                $mail->Body    = "
                    <h2>¡Gracias por tu sugerencia!</h2>
                    <p>La Alcaldía de Santiago ha recibido tu comentario y lo tomaremos en cuenta para mejorar nuestros servicios.</p>
                    <br>
                    <small>Este es un mensaje automático, por favor no responder.</small>
                ";

                $mail->send();
                $mensaje = "¡Gracias por tu sugerencia! Hemos enviado un correo de confirmación a $email.";

            } catch (Exception $e) {
                $mensaje = "Sugerencia guardada, pero no se pudo enviar el correo. Error: {$mail->ErrorInfo}";
            }
        } else {
            $mensaje = "Error al guardar la sugerencia.";
        }

        $stmt->close();
    } else {
        $mensaje = "Por favor completa todos los campos.";
    }
}

header("Location: ../VIEWS/buzon_view.php?mensaje=" . urlencode($mensaje));
exit();
