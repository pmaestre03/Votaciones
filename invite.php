<?php
include("Utilidades/header.php");

// Verificar la sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../errores/error403.php");
    exit;
}

// Obtener el ID de la encuesta desde la URL
if (isset($_GET['id'])) {
    $id_encuesta = intval($_GET['id']);

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

        // Procesar cada correo electrónico
        foreach ($email_array as $email) {
            $email = trim($email); // Limpiar espacios en blanco alrededor del correo electrónico

            // Verificar si el correo electrónico existe en la tabla users
            $consulta_user = 'SELECT id_user FROM users WHERE email = :email';
            $stmt_user = $pdo->prepare($consulta_user);
            $stmt_user->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_user->execute();
            $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

            // Insertar datos en la tabla invitacion y email_invitacion
            $consulta_invitacion = 'INSERT INTO invitacion (id_encuesta, id_user, user_email, email, encuesta_activa) VALUES (:id_encuesta, :id_user, NULL, :email, TRUE)';
            $stmt_invitacion = $pdo->prepare($consulta_invitacion);
            $stmt_invitacion->bindParam(':id_encuesta', $id_encuesta, PDO::PARAM_INT);

            if ($user) {
                // Si el correo electrónico existe en la tabla users, obtener su ID de usuario
                $id_user = $user['id_user'];
                $stmt_invitacion->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            } else {
                // Si el correo electrónico no existe en la tabla users, dejar el id_user como NULL
                $stmt_invitacion->bindValue(':id_user', NULL, PDO::PARAM_NULL);

                // Insertar el correo en la tabla email_invitacion
                $consulta_email_invitacion = 'INSERT INTO email_invitacion (user_email) VALUES (:email)';
                $stmt_email_invitacion = $pdo->prepare($consulta_email_invitacion);
                $stmt_email_invitacion->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt_email_invitacion->execute();
            }

            $stmt_invitacion->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_invitacion->execute();

            if (!$stmt_invitacion->execute()) {
                $error_info = $stmt_invitacion->errorInfo();
                echo "Error al ejecutar la consulta: " . $error_info[2];
            }
        }

        unset($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Invitaciones</title>
    <link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
    <script src="./Utilidades/scripts.js"></script>
</head>

<body class="invite">
    
    <div id="notification-container"></div>

    <form method="post" action="" class="invite-form">
        <input type="hidden" name="id_encuesta" value="<?php echo $id_encuesta; ?>">
        <label for="emails">Direcciones de correo electrónico (separadas por coma):</label>
        <input type="text" id="emails" name="emails" required>
        <button type="submit">Enviar Invitaciones</button>
    </form>

    <?php include("Utilidades/footer.php") ?>
</body>
</html>