<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once("../../vendor/autoload.php");
require_once("../../vendor/phpmailer/phpmailer/src/Exception.php");
require_once("../../vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once("../../vendor/phpmailer/phpmailer/src/SMTP.php");
// mailer.php
function sendEmail(string $correoUsuario, string $zipName, int $vCount, int $totalCount): void {
    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP correcta para Mailtrap
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = '7631591b79bef9'; // Usuario Mailtrap
            $mail->Password   = 'f3831481a54de1'; // Contraseña Mailtrap
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 2525;

            $mail->setFrom('hello@demomailtrap.co', 'Analizador de Vulnerabilidades');
            $mail->addAddress($correoUsuario);

            $mail->isHTML(true);
            $mail->Subject = 'Reporte de Análisis de Vulnerabilidades';
            $mail->Body    = "
            <h4>¡Hola!</h4>
            <p>Tu archivo <strong>{$zipName}</strong> ha sido procesado correctamente.</p>
            <p>Se detectaron <strong>{$vCount}</strong> vulnerabilidades de un total de <strong>{$totalCount}</strong> archivos.</p>
            <p>Puedes descargar el ZIP corregido o los reportes PDF desde la plataforma.</p>
            <br><p>Gracias por usar nuestro sistema.</p>
        ";
            $mail->AltBody = strip_tags($mail->Body);

            $mail->send();
            echo "<p>📧 Correo enviado a <strong>{$correoUsuario}</strong>.</p>";
    } catch (Exception $e) {
        echo "<p class='text-danger'>Error correo: {$mail->ErrorInfo}</p>";
    }
}

?>