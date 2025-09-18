<?php
namespace App\Controllers;
use App\Models\Suggestion;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class BuzonController {
    public function index() {
        require __DIR__ . '/../Views/buzon_view.php';
    }
    public function procesar() {
        session_start();
        $mensaje = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $sugerencia = trim($_POST['sugerencia'] ?? '');
            if (!empty($email) && !empty($sugerencia)) {
                $model = new Suggestion();
                $guardado = $model->guardar($email, $sugerencia);
                if ($guardado) {
                    require_once __DIR__ . '/../../phpmailer/src/PHPMailer.php';
                    require_once __DIR__ . '/../../phpmailer/src/Exception.php';
                    require_once __DIR__ . '/../../phpmailer/src/SMTP.php';
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'alcaldiadeveloper@gmail.com';
                        $mail->Password   = 'ngqx rjdk tpcb tniy';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;
                        $mail->setFrom('alcaldiadeveloper@gmail.com', 'Alcaldia de Santiago');
                        $mail->addAddress($email);
                        $mail->isHTML(true);
                        $mail->Subject = 'Gracias por tu sugerencia';
                        $mail->Body    = "<h2>¡Gracias por tu sugerencia!</h2><p>La Alcaldía de Santiago ha recibido tu comentario y lo tomaremos en cuenta para mejorar nuestros servicios.</p><br><small>Este es un mensaje automático, por favor no responder.</small>";
                        $mail->send();
                        $mensaje = "¡Gracias por tu sugerencia! Hemos enviado un correo de confirmación a $email.";
                    } catch (Exception $e) {
                        $mensaje = "Sugerencia guardada, pero no se pudo enviar el correo. Error: {$mail->ErrorInfo}";
                    }
                } else {
                    $mensaje = "Error al guardar la sugerencia.";
                }
            } else {
                $mensaje = "Por favor completa todos los campos.";
            }
        }
        header("Location: /Alcaldia/app/Views/buzon_view.php?mensaje=" . urlencode($mensaje));
        exit();
    }
}
