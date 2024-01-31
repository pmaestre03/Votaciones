<!DOCTYPE html>
<html lang="es">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Votar Encuesta</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="./Utilidades/styles.css?no-cache=<?php echo time(); ?>">
        <script src="Utilidades/scripts.js"></script>
</head>
<?php require('Utilidades/scripts2.php')?>
<?php include("Utilidades/header.php") ?>
<?php include("Utilidades/conexion.php") ?>
<body class="vote_poll">

<div id="notification-container"></div>
<?php
if (!isset($_SESSION['email'])) {
    header("Location: ../errores/error403.php");
        http_response(403);
        exit;
}

// Tabla email_invitacion
// -user_email PK
// Tabla votaciones_por_usuario
// -registro (boolean) 
// -user_email FK de la tabla email_invitacion(user_email)
// Tabla invitacion
// -id_invitacion PK
// -id_encuesta FK de la tabla encuestas(id_encuesta)
// -user_email FK de la tabla email_invitacion(user_email)
// -token
// -token_activo (BOOLEAN)
// Mostrar encuestas habilitadas (en la tabla de encuestas columna:habilitada)
// Si esta activa se podra votar
/* if ($fechaActual >= $inicioEncuesta && $fechaActual <= $finEncuesta) {
    echo "<td class='publica'>Activa</td>"; 
} if ($fechaActual < $inicioEncuesta) {
echo "<td class='oculta'>No Activa</td>";
} if ($fechaActual > $finEncuesta){
echo "<td class='finalizada'>Finalizada</td>";
} */
?>
<?php include("Utilidades/footer.php") ?>
</body>
</html>
