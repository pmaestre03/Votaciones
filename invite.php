<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Votar Encuesta</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
        <script src="./Utilidades/scripts.js"></script>
</head>
<?php
session_start();
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
        foreach ($email_array as $email_row) {
            $email = trim($email_row); // Limpiar espacios en blanco alrededor del correo electrónico
            
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
                $consulta_invitacion_user = 'INSERT INTO invitacion (id_encuesta, id_user, user_email, email, token_activo, token) VALUES (:id_encuesta, :id_user, :user_email, :email, TRUE, :token)';
                $stmt_invitacion = $pdo->prepare($consulta_invitacion_user);
                $stmt_invitacion->bindParam(':id_encuesta', $_SESSION['id_encuesta'], PDO::PARAM_INT);
            
                // Obtener su ID de usuario
                $id_user = $user['id_user'];
                $stmt_invitacion->bindParam(':id_user', $id_user, PDO::PARAM_INT);
                $stmt_invitacion->bindParam(':user_email', $email, PDO::PARAM_STR);
                $stmt_invitacion->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt_invitacion->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt_invitacion->execute();

                // Insertar el correo en la tabla email_invitacion
                $consulta_email = 'INSERT INTO email_invitacion (user_email) VALUES (:user_email)';
                $stmt_email = $pdo->prepare($consulta_email);
                $stmt_email->bindParam(':user_email', $email, PDO::PARAM_STR);
                $stmt_email->execute();
            }
            else { // Si el correo electrónico no existe en la tabla users,
                // Dejar el id_user y el email como NULL
                $consulta_invitacion = 'INSERT INTO invitacion (id_encuesta, id_user, user_email, email, token_activo, token) VALUES (:id_encuesta, NULL, :email, NULL, TRUE, :token)';
                $stmt_invitacion = $pdo->prepare($consulta_invitacion);
                $stmt_invitacion->bindParam(':id_encuesta', $_SESSION['id_encuesta'], PDO::PARAM_INT);
                $stmt_invitacion->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt_invitacion->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt_invitacion->execute();

                // Insertar el correo en la tabla email_invitacion
                $consulta_email_invitacion = 'INSERT INTO email_invitacion (user_email) VALUES (:user_email)';
                $stmt_email_invitacion = $pdo->prepare($consulta_email_invitacion);
                $stmt_email_invitacion->bindParam(':user_email', $email, PDO::PARAM_STR);
                $stmt_email_invitacion->execute();
            }
        }
        
    }catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
    header("Location: process_invitations.php");
    exit;
}
?>