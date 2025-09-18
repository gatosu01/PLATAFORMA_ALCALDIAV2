<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ajusta la ruta según tu proyecto
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $sugerencia = trim($_POST['sugerencia']);

    if (!empty($email) && !empty($sugerencia)) {
        // Guardar sugerencia en la base de datos
        $stmt = $conexion->prepare("INSERT INTO suggestions (email, suggestion, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $sugerencia);
        $stmt->execute();

        // Enviar correo de confirmación con PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración para Gmail SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'alcaldiadeveloper@gmail.com'; // Tu cuenta Gmail
            $mail->Password   = 'ngqx rjdk tpcb tniy'; // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente y destinatario
            $mail->setFrom('alcaldiadeveloper@gmail.com', 'Alcaldía de Santiago');
            $mail->addAddress($email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Gracias por tu sugerencia';
            $mail->Body    = "
                <h2>¡Gracias por tu sugerencia!</h2>
                <p>La Alcaldía de Santiago ha recibido tu comentario y lo tomaremos en cuenta para mejorar nuestros servicios.</p>
                <br>
                <small>Este es un mensaje automático, por favor no responder.</small>
            ";

            // Enviar el correo
            $mail->send();

            // Redirigir al usuario con confirmación
            header("Location: buzon.html?status=ok");
            exit();

        } catch (Exception $e) {
            echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "Por favor ingresa tu correo y sugerencia.";
    }
}
?>
