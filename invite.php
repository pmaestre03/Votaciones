<script src="./Utilidades/scripts.js"></script>
<?php
include("Utilidades/header.php");
// Obtener el ID de la encuesta desde la URL
if (isset($_GET['id'])) {
    $_SESSION['id_encuesta'] = intval($_GET['id']);
}

?>
<body class="invite">
    
    <div id="notification-container"></div>

    <form method="post" action="" class="invite-form">
        <input type="hidden" name="id_encuesta" value="<?php $_SESSION['id_encuesta'] ?>">
        <label for="emails">Direcciones de correo electrónico (separadas por coma):</label>
        <input type="text" id="emails" name="emails" required>
        <button type="submit" class="invite-button">Enviar Invitaciones</button>
    </form>

    <?php include("Utilidades/footer.php") ?>
</body>
</html>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
// Verificar la sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../errores/error403.php");
    exit;
}




    // Obtener los correos electrónicos del formulario
    if (isset($_POST['emails'])) {
        $emails = $_POST['emails'];
        $email_array = explode(',', $emails);
        
        // Configuración de PHPMailer
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug  = 0;  
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "mgonzalezramirez.cf@iesesteveterradas.cat";
        $mail->Password   = "PlataNoEs18";
        $mail->SetFrom("mgonzalezramirez.cf@iesesteveterradas.cat", "VotaPAX");

        try {
            $hostname = "localhost";
            $dbname = "votaciones";
            $username = "userProyecto";
            $pw = "votacionesAXP24";
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $pw);
        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
        }

        try {
            // Procesar cada correo electrónico
            foreach ($email_array as $email) {
                $email = trim($email); // Limpiar espacios en blanco alrededor del correo electrónico
            
                // Generar un token aleatorio
                $token = uniqid();

                // Crear enlace de votación con el token
                $voting_link = "https://aws22.ieti.site/vote_poll.php?token=$token";
            
                // Agregar destinatario y contenido del mensaje
                $mail->AddAddress($email);
                $subjectmail = "Invitado a VotaPAX";
                $mail->Subject = $subjectmail;
                $bodymail = "¡Hola! Has sido invitado a votar en nuestra encuesta. Para votar, haz clic en el siguiente enlace:' <a href='$voting_link'>Enlace</a>'";
                //$mail->Body = $bodymail;
                $mail->MsgHTML($bodymail);
                // Enviar correo electrónico
                if (!$mail->Send()) {
                    echo "Error al enviar correo a: $email<br>";
                } else {
                    // Verificar si el correo electrónico existe en la tabla users
                    $consulta_user = 'SELECT id_user FROM users WHERE email = :email';
                    $stmt_user = $pdo->prepare($consulta_user);
                    $stmt_user->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt_user->execute();
                    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
                
                    if ($user) { // Si el correo electrónico existe en la tabla users
                        // Dejar el user_email como NULL
                        $consulta_invitacion_user = 'INSERT INTO invitacion (id_encuesta, id_user, user_email, email, token_activo, token) VALUES (:id_encuesta, :id_user, NULL, :email, TRUE, :token)';
                        $stmt_invitacion = $pdo->prepare($consulta_invitacion_user);
                        $stmt_invitacion->bindParam(':id_encuesta', $_SESSION['id_encuesta'], PDO::PARAM_INT);
                        
                        // Obtener su ID de usuario
                        $id_user = $user['id_user'];
                        $stmt_invitacion->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                        $stmt_invitacion->bindParam(':email', $email, PDO::PARAM_STR);
                        $stmt_invitacion->bindParam(':token', $token, PDO::PARAM_STR);
                        $stmt_invitacion->execute();
                    }
                    else { // Si el correo electrónico no existe en la tabla users,
                        // Insertar el correo en la tabla email_invitacion
                        $consulta_email_invitacion = 'INSERT INTO email_invitacion (user_email) VALUES (:email)';
                        $stmt_email_invitacion = $pdo->prepare($consulta_email_invitacion);
                        $stmt_email_invitacion->bindParam(':email', $email, PDO::PARAM_STR);
                        $stmt_email_invitacion->execute();

                        // Dejar el id_user y el email como NULL
                        $consulta_invitacion = 'INSERT INTO invitacion (id_encuesta, id_user, user_email, email, token_activo, token) VALUES (:id_encuesta, NULL, :email, NULL, TRUE, :token)';
                        $stmt_invitacion = $pdo->prepare($consulta_invitacion);
                        $stmt_invitacion->bindParam(':id_encuesta', $_SESSION['id_encuesta'], PDO::PARAM_INT);
                        $stmt_invitacion->bindParam(':email', $email, PDO::PARAM_STR);
                        $stmt_invitacion->bindParam(':token', $token, PDO::PARAM_STR);
                        $stmt_invitacion->execute();
                    }
                }
                // Limpiar los destinatarios para el próximo correo
                $mail->ClearAddresses();
            }
        }catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
?>
