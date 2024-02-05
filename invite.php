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
            //$voting_link = "https://aws22.ieti.site/vote_poll.php?token=$token";

            // Verificar si el correo electrónico existe en la tabla users
            $consulta_user = 'SELECT id_user FROM users WHERE email = :email';
            $stmt_user = $pdo->prepare($consulta_user);
            $stmt_user->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_user->execute();
            $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
            
            if ($user) { // Si el correo electrónico existe en la tabla users
                // Insertar el correo en la tabla email_invitacion
                $consulta_email = 'INSERT INTO email_invitacion (user_email, token) VALUES (:email, token)';
                $stmt_email = $pdo->prepare($consulta_email);
                $stmt_email->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt_invitacion->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt_email->execute();
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
        // Limpiar los destinatarios para el próximo correo
        $mail->ClearAddresses();
        }
    }catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<?php
// Seleccionar los primeros 5 correos electrónicos de la tabla SEND_EMAIL
$query = "SELECT e.*, i.token FROM SEND_EMAIL e INNER JOIN invitation i ON e.email = i.guest_email LIMIT 5";
$stmt = $pdo->prepare($query);
$stmt->execute();
$emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($emails as $email) {
    // Crear una nueva instancia de PHPMailer
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";
    $mail->SMTPDebug  = 0;  
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = $senderEmail;
    $mail->Password   = $passwordEmail;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8'; 
    $mail->AddAddress($email['email']);
    $mail->SetFrom($senderEmail, "VOTAIETI");
    $mail->Subject = 'Invitacion para votar en una encuesta';
    $mail->AddEmbeddedImage('votaietilogo.png', 'logo_img');
    $mail->MsgHTML("Has sido invitado a participar en una encuesta en la plataforma VOTAIETI. Para votar, por favor haz clic en el siguiente enlace: <a href='https://aws21.ieti.site/accept_invitation.php?token=" . $email['token'] . "'>Acceder a la encuesta</a>. Tu voto es completamente anónimo. Gracias por tu participación.<br><img src='cid:logo_img'>");

    if($mail->send()) {
        // Eliminar el correo electrónico de la tabla SEND_EMAIL
        $query = "DELETE FROM SEND_EMAIL WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $email['id'], PDO::PARAM_INT);
        $stmt->execute();
    } else {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
    header  ('Location: dashboard.php');    
}
?>