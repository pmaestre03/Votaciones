<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "vendor/autoload.php";
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$send_email = 'votapax@gmail.com';
$send_password = 'votacionesAXP24';

$query_5 = "SELECT user_email FROM invitacion LIMIT 5";
$stmt_email = $pdo->prepare($query_5);
$stmt_email->execute();
$email_array = $stmt_email->fetchAll(PDO::FETCH_ASSOC);

foreach ($email_array as $email) {
    $email = trim($email);

    $token = uniqid();
    $voting_link = "https://aws22.ieti.site/vote_poll.php?token=$token";

    // Agregar destinatario y contenido del mensaje
    $mail->AddAddress($email);
    $subjectmail = "Invitado a VotaPAX";
    $mail->Subject = $subjectmail;
    $bodymail = "¡Hola! Has sido invitado a votar en nuestra encuesta. Para votar, haz clic en el siguiente enlace:' <a href='$voting_link'>Enlace</a>'";
    $mail->MsgHTML($bodymail);
    // Enviar correo electrónico
    if (!$mail->Send()) {
        echo "Error al enviar correo a: $email<br>";
    } else {
    }
}
// Redirigir de nuevo a la página de detalles de la encuesta después de enviar invitaciones
header("Location: graphics.php?id=$id_encuesta");
exit;

?>
