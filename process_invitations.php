<!-- procesar_invitaciones.php -->
<?php

// Verificar la sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../errores/error403.php");
    exit;
}

// Obtener el ID de la encuesta y correos electrónicos desde el formulario
if (isset($_POST['id_encuesta'])) {
    $id_encuesta = intval($_POST['id_encuesta']);
    $correos_destinatarios = explode(',', $_POST['emails']);

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

    // Lógica para enviar invitaciones a cada correo electrónico
    foreach ($correos_destinatarios as $correo) {
        // Aquí debes personalizar el contenido del correo electrónico
        $asunto = "Invitación a la encuesta";
        $mensaje = "¡Hola!\n\nHas sido invitado a participar en la encuesta. Haz clic en el siguiente enlace para votar:\n";
        $mensaje .= "https://tudominio.com/votar.php?id_encuesta={$id_encuesta}&correo={$correo}";

        // Enviar el correo electrónico
        $enviado = mail($correo, $asunto, $mensaje);

        // Puedes realizar acciones adicionales según si el correo se envió correctamente
        if ($enviado) {
            // Actualizar el estado de la invitación en la base de datos
            $stmt_update = $pdo->prepare("UPDATE invitaciones SET estado = 'enviado' WHERE id_encuesta = :id_encuesta AND correo_destinatario = :correo");
            $stmt_update->bindParam(':id_encuesta', $id_encuesta, PDO::PARAM_INT);
            $stmt_update->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt_update->execute();
        } else {
            // Manejar el caso en el que el correo no se pudo enviar
            echo "Error: No se pudo enviar la invitación a $correo";
        }
    }

    // Redirigir de nuevo a la página de detalles de la encuesta después de enviar invitaciones
    header("Location: graphics.php?id=$id_encuesta");
    exit;

} else {
    echo "Error: No se proporcionó el ID de la encuesta.";
}
?>